if (typeof usesgraphcrt == "undefined" || !usesgraphcrt) {
    var usesgraphcrt = {};
}

usesgraphcrt.schedule = {

    init: function () {
        csrfToken = $('meta[name=csrf-token]').attr("content"),
            $ScheduleForm = $('[data-role=schedule-form]');
        $selectTargetModel = $('[data-role=targetModelList]'),
            $selectTargetId = $('[data-role=targetId]'),
            $addTime = $('[data-role=addTime]'),
            $timeInput = $('[data-role=getTime]'),
            $timeStartSelect = $('[name=setTimeStart]'),
            $timeStopSelect = $('[name=setTimeStop]'),
            $timeStart = {},
            $timeStop = {},
            $timeStart['time'] = $('[name=setTimeStart] option:selected').text(),
            $timeStop['time'] = $('[name=setTimeStop] option:selected').text(),
            $timeStart['id'] = $('[name=setTimeStart] option:selected').val(),
            $timeStop['id'] =  $('[name=setTimeStop] option:selected').val(),
            $timeRow = $('[data-role=time-row]'),
            $submitBtn = $('[data-role=submitBtn]'),
            $periodsArrayField = $('[data-role=periods-array]'),
            $removePeriodBtn = $('[data-role=removePeriod]'),
            $sendRecord = $('[data-role=send-record]');

        $timeStartSelect.on('change',function () {
            $timeStart['time'] = $('[name=setTimeStart] option:selected').text();
            $timeStart['id'] = $('[name=setTimeStart] option:selected').val();
        });

        $timeStopSelect.on('change',function () {
            $timeStop['time'] = $('[name=setTimeStop] option:selected').text();
            $timeStop['id'] =  $('[name=setTimeStop] option:selected').val();
        });

        $selectTargetModel.on('change',function () {
            usesgraphcrt.schedule.getTargetId($selectTargetModel.data('url'),$selectTargetModel.val(),$selectTargetId.data('id'));
        });

        $addTime.on('click',function () {
            time = '';
            if ($timeStart['id'] < $timeStop['id']) {
                time = $timeStart['time'] + ' - '+ $timeStop['time'];
                usesgraphcrt.schedule.addTime(time);
            } else {
                $('#alertBtn').click();
                // alert('Период задан не верно!');
            }
        });

        $(document).on('click','[data-role=removePeriod]',function () {
            console.log('test');
            $(this).parent()
                .addClass('hidden')
                .find('[data-role=schedule-day-item-status]')
                .attr('data-status','deleted');
        });

        $submitBtn.on('click',function (e) {
            e.preventDefault();
            var data = usesgraphcrt.schedule.getSchedulePeriod();
            $periodsArrayField.val(JSON.stringify(data));
            // console.log(data);
            $ScheduleForm.submit();
        });

        $sendRecord.on('click',function () {
            self = this;
            data = {
                scheduleId: $(self).data('schedule-id'),
                periodId: $(self).data('period-id')
            };
            if ($(self).data('status') == 'active') {
                console.log('active');
                console.log($(self).data('url'));
                $.ajax({
                    type: "POST",
                    url: $(self).data('url'),
                    data: {record:data},
                    success: function (response) {
                        if (response.status == 'success') {
                            $(self).text('Отменить заявку');
                            $(self).data('status', 'in process');
                            $(self).data('url',response.cancelUrl);
                        }
                    }
                });
            } else if (($(self).data('status') == 'in process') || ($(self).data('status') == 'confirmed')) {
                $.ajax({
                    type: "POST",
                    url: $(self).data('url'),
                    data: {record:data},
                    success: function (response) {
                        if (response.status == 'success') {
                            $(self).text('Подать заявку');
                            $(self).data('status', 'active');
                            $(self).data('url',response.saveUrl);
                        }
                    }
                });
            }
        });
    },

    addTime: function (time) {
        $('.tab-content .active [data-role=time-block]').append($('<div class="row"' +
            ' data-role="time-row" data-period-id=""></div>')
            .append('<span data-role="schedule-day-item" style="">'+time+'</span>')
            .append('<input type="text" data-role="schedule-day-item-amount">')
            .append('<input type="checkbox" data-role="schedule-day-item-status">')
            .append('<span class="btn glyphicon glyphicon-remove" data-role="removePeriod"></span><br>')
        );

    },

    getTargetId: function (url,model,targetId) {
        $.ajax({
                type: 'POST',
                url: url,
                data: {model:model,_csrf : csrfToken},
                success: function (response) {
                    if (response.status == 'success') {
                        $.each(response.list,function (key,val) {
                            $selectTargetId.append('<option value="'+key+'">'+val+'</option>');
                        });
                        if (targetId != '') {
                            $('#scheduleschedule-target_id option[value='+targetId+']').attr('selected', 'selected');
                        }
                    }
                }
            }
        );
    },

    getSchedulePeriod: function () {
        arrTime = {};
        $('[data-role=schedule-day-period] [data-role=time-block]').each(function ($key, $value) {
            arrTime[$key] = {};
            $(this).find('[data-role=time-row]').each(function ($index, $value) {
                if ($($value).data('period-id') == '') {
                    periodId = 'NULL';
                } else {
                    periodId = $($value).data('period-id');
                }
                time = $(this).find('[data-role=schedule-day-item]').text();
                amount = $(this).find('[data-role=schedule-day-item-amount]').val();
                if ($(this).find('[data-role=schedule-day-item-status]').prop('checked')){
                    if ($(this).find('[data-role=schedule-day-item-status]').data('status')=='deleted')
                    {
                        status = 'deleted';
                    }
                    else { status = 'active'; }
                } else {
                    if ($(this).find('[data-role=schedule-day-item-status]').data('status') == 'deleted') {
                        status = 'deleted';
                    }
                    else { status = 'inactive'; }
                }
                arrTime[$key][$index] = {
                    'periodId': periodId,
                    'time': time,
                    'amount': amount,
                    'status': status
                };
            });
        });
        console.log(arrTime);
        return arrTime;
    },

    sendPeriods: function (url,data) {
        $.ajax({
            type: 'POST',
            url: url,
            data: {periods:data,_csrf : csrfToken},
            success: function (response) {
                if (response.status == 'success') {
                    console.log(response);
                }
            }
        });
    },

};
usesgraphcrt.schedule.init();
usesgraphcrt.schedule.getTargetId($selectTargetModel.data('url'),$selectTargetModel.val(),$selectTargetId.data('id'));

