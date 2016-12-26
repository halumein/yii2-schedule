if (typeof usesgraphcrt == "undefined" || !usesgraphcrt) {
    var usesgraphcrt = {};
}

usesgraphcrt.schedule = {

    init: function () {
        csrfToken = $('meta[name=csrf-token]').attr("content");
        $ScheduleForm = $('[data-role=schedule-form]');
        $selectTargetModel = $('[data-role=targetModelList]');
        $selectTargetId = $('[data-role=targetId]');
        $addTime = $('[data-role=addTime]');
        $timeInput = $('[data-role=getTime]');
        $timeStartSelect = $('[name=setTimeStart]');
        $timeStopSelect = $('[name=setTimeStop]');
        $timeStart = {};
        $timeStop = {};
        $timeStart['time'] = $('[name=setTimeStart] option:selected').text();
        $timeStop['time'] = $('[name=setTimeStop] option:selected').text();
        $timeStart['id'] = $('[name=setTimeStart] option:selected').val();
        $timeStop['id'] =  $('[name=setTimeStop] option:selected').val();
        $timeRow = $('[data-role=time-row]');
        $submitBtn = $('[data-role=submitBtn]');
        $periodsArrayField = $('[data-role=periods-array]');
        $removePeriodBtn = $('[data-role=removePeriod]');
        $sendRecord = $('[data-role=send-record]');
        updateRecord = '[data-role=update-record]';

        $ownerSignObjectShowModalButton = $('[data-role=show-sign-object-modal]');
        $ownerSignObjectModal = $('[data-role=sign-object-modal]');


        $ownerSignObjectShowModalButton.on('click', function() {
            var self = this;
            $ownerSignObjectModal.modal('toggle');
            $ownerSignObjectModal.find('.modal-body').load('/schedule/schedule/client-choose-ajax?scheduleId=' + $(self).data('schedule-id') + '&periodId=' + $(self).data('period-id'));
        });


        /*
        *   Записи объекта на время админом
        */
        $(document).on('click', '[data-role=sign-object]', function() {
            var self = this,
                label = $(self).data('label'),
                url = $(self).data('url'),
                scheduleId = $(self).data('schedule-id'),
                periodId = $(self).data('period-id'),
                clientModel = $(self).data('client-model'),
                clientId = $(self).data('client-id');

            $.when(
                usesgraphcrt.schedule.sendRequest(url, scheduleId, periodId, clientModel, clientId, 'confirmed')
            ).done(function(response) {
                if (response !== false) {
                    $block = $('[data-period-id=' + periodId + ']').find('.record-list');

                    $places = $('[data-period-id=' + periodId + ']').find('[data-role=places]');

                    val = +$places.html();
                    $places.html(--val);
                    console.log($places);

                    $block.prepend('<div class="user-record"><label>' + label + '</label>' +
                            '<span data-role="target"' +
                            'data-schedule-id="' + scheduleId +
                            '" data-period-id="' + periodId +
                            '" data-url="/schedule/record/update">' +
                            '( <a class="record" data-record-id="' + response.recordId +
                            '" data-status="canceled" data-role="update-record">Отменить</a> | ' +
                            '<a class="record" data-record-id="' + response.recordId +
                            '" data-status="denied" data-role="update-record">Заблокировать</a> )</span></div>');

                    $ownerSignObjectModal.modal('hide');
                }
            });
        });


        $selectTargetModel.on('change',function () {
            usesgraphcrt.schedule.getTargetId($selectTargetModel.data('url'),$selectTargetModel.val(),$selectTargetId.data('id'));
        });

        $addTime.on('click',function () {
            time = '';

            $timeStart['time'] = $('[name=setTimeStart] option:selected').text();
            $timeStart['id'] = $('[name=setTimeStart] option:selected').val();

            $timeStop['time'] = $('[name=setTimeStop] option:selected').text();
            $timeStop['id'] =  $('[name=setTimeStop] option:selected').val();

            if (+$timeStart['id'] < +$timeStop['id']) {
                time = $timeStart['time'] + ' - '+ $timeStop['time'];
                usesgraphcrt.schedule.addTime(time);
                $timeStartSelect.val($timeStop['id']);
            } else {
                $('#alertBtn').click();
                // alert('Период задан не верно!');
            }
        });

        $(document).on('click','[data-role=removePeriod]',function () {
            $(this).closest('.added-period')
                .addClass('hidden')
                .find('[data-role=schedule-day-item-status]')
                .attr('data-status','deleted');
        });

        $submitBtn.on('click',function (e) {
            e.preventDefault();
            var data = usesgraphcrt.schedule.getPeriod();
            $periodsArrayField.val(JSON.stringify(data));
            // console.log(data);
            $ScheduleForm.submit();
        });

        $(document).on('click', updateRecord,function(){
            self = this;
            data= {
                recordId: $(self).data('record-id'),
                status: $(self).data('status'),
                periodId: $(self).closest('[data-role=target]').data('period-id'),
                scheduleId: $(self).closest('[data-role=target]').data('schedule-id'),
            };
            url = $(self).closest('[data-role=target]').data('url');
            if ($(self).data('status') != 'denied') {
                usesgraphcrt.schedule.updateRecord(url,data,self);
            } else if ($(self).data('status') == 'denied') {
                if (confirm('Вы уверены, что хотите заблокировать заявку пользователя?')) {
                    usesgraphcrt.schedule.updateRecord(url,data,self);
                }
            }
        });

        $sendRecord.on('click',function () {
            var self = this,
                scheduleId = $(self).data('schedule-id'),
                periodId = $(self).data('period-id'),
                url = $(self).data('url');

            switch ($(self).data('status')) {
                case 'active':
                    $.when(
                        usesgraphcrt.schedule.sendRequest(url, scheduleId, periodId)
                    ).done(function(response) {
                        if (response !== false) {
                            console.log(response);
                            $(self).closest('.user-record').find('.text-status').text('Заявка отправлена.  | ');
                            $(self).text('Отменить');
                            $(self).data('status', 'in process');
                            $(self).data('url',response.cancelUrl);
                        }
                    });
                    break;

                case 'in process':
                case 'confirmed':
                    $.when(
                        usesgraphcrt.schedule.cancelRequest(url, scheduleId, periodId)
                    ).done(function(response) {
                        if (response !== false) {
                            $(self).closest('.user-record').find('.text-status').text('');
                            $(self).text('Подать заявку');
                            $(self).data('status', 'active');
                            $(self).data('url',response.saveUrl);
                        }
                    });
                    break;
            }
        });
    },

    addTime: function (time) {
        $('.tab-content .active [data-role=time-block]').append($('<div class="row added-period"' +
            ' data-role="time-row" data-period-id=""></div>')
            .append('<div><span class="form-control btn btn-danger" data-role="removePeriod">X</span></div>')
            .append('<div><input class="form-control" type="checkbox" data-role="schedule-day-item-status" checked></div>')
            .append('<div><input class="form-control" type="text" data-role="schedule-day-item-amount" placeholder="Места" style="width:100px;"></div>')
            .append('<div><span data-role="schedule-day-item" style="">'+time+'</span></div>')
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
                            $('#Schedule-target_id option[value='+targetId+']').attr('selected', 'selected');
                        }
                    }
                }
            }
        );
    },

    getPeriod: function () {
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
                // console.log($(this).find('[data-role=schedule-day-item]'));
                amount = $(this).find('[data-role=schedule-day-item-amount]').val();
                if ($(this).find('[data-role=schedule-day-item-status]').prop('checked')){
                    if ($(this).find('[data-role=schedule-day-item-status]').data('status') == 'deleted')
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

    /*
    *   clientModel - модель объекта записи
    *   clientId - ид объекта записи
    */

    sendRequest: function (url, scheduleId, periodId, clientModel, clientId, status = false) {

        return $.ajax({
            type: "POST",
            url: url,
            data: {scheduleId: scheduleId, periodId: periodId, clientModel: clientModel, clientId: clientId, status: status},
            success: function (response) {
                if (response.status == 'success') {
                    return response;
                } else {
                    return false;
                }
            },
            fail: function() {
                return false;
            }
        });
    },

    cancelRequest: function (url, scheduleId, periodId) {

        return $.ajax({
            type: "POST",
            url: url,
            data: {scheduleId: scheduleId, periodId: periodId},
            success: function (response) {
                if (response.status == 'success') {
                    return response;
                } else {
                    return false;
                }
            },
            fail: function() {
                return false;
            }
        });
    },

    updateRecord: function (url,data,self) {
        $.ajax({
            type: "POST",
            url: url,
            data: {updateRecord:data},
            success: function (response) {
                if ((response.status == 'success') || (response.status == 'true')) {
                    switch ($(self).data('status')) {
                        case 'confirmed':
                            $(self).text('Отменить');
                            $(self).data('status', 'canceled');
                            $(self).closest('.period-row').find('[data-role=places]').text(response.places);
                            break;
                        case 'canceled':
                            $(self).text('Подтвердить');
                            $(self).data('status', 'confirmed');
                            $(self).closest('.period-row').find('[data-role=places]').text(response.places);
                            break;
                        case 'denied':
                            $(self).closest('.period-row').find('[data-role=places]').text(response.places);
                            $(self).closest('.user-record').remove();
                            break;
                    }
                }
            }
        });
    }

};

$(function () {
    usesgraphcrt.schedule.init();
    usesgraphcrt.schedule.getTargetId($selectTargetModel.data('url'),$selectTargetModel.val(),$selectTargetId.data('id'));
});
