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
        deleteRecord = '[data-role=delete-record]';
        recordToDateShowModalButton = '[data-role=show-record-to-date-modal]';
        signRecordToDateButton = '[data-role=sign-record-to-date]';
        $datePickerInput = $('[data-role=records-date-value]');

        $ownerSignObjectShowModalButton = $('[data-role=show-sign-object-modal]');
        $ownerSignObjectModal = $('[data-role=sign-object-modal]');


        $ownerSignCustomObjectShowModalButton = $('[data-role=show-sign-custom-object-modal]');
        $ownerSignCustomObjectModal = $('[data-role=sign-custom-object-modal]');
        $signCustomButton = $ownerSignCustomObjectModal.find('[data-role=sign-custom-object]');

        $ownerSignCustomObjectShowModalButton.on('click', function() {
            var $self = $(this),
                timeTitle = $self.data('time-title'),
                scheduleId = $self.data('schedule-id'),
                periodId = $self.data('period-id');

            $ownerSignCustomObjectModal.find('[data-role=custom-record-time-label]').html(timeTitle);
            $signCustomButton.data('period-id', periodId).data('schedule-id', scheduleId);
            $ownerSignCustomObjectModal.modal('toggle');
        });

        $(document).on('click',recordToDateShowModalButton, function() {
            var $self = $(this),
                timeTitle = $self.data('time-title'),
                scheduleId = $self.data('schedule-id'),
                periodId = $self.data('period-id'),
                date = $self.data('date');

            $(document).find('[data-role=record-to-date-time-label]').html(timeTitle);
            $(document).find('[data-role=record-date]').val(date);
            $(document).find('[data-role=sign-record-to-date]').data('period-id', periodId).data('schedule-id', scheduleId);
            $(document).find('[data-role=record-to-date-modal]').modal('toggle');
        });

        $(document).on('click',signRecordToDateButton, function() {
            var $self = $(this),
                url = $self.data('url'),
                date = $(document).find('[data-role=record-date]').val(),
                name = $(document).find('[data-role=record-to-date-name]').val(),
                text =  $(document).find('[data-role=record-to-date-text]').val(),
                scheduleId = $self.data('scheduleId'),
                periodId = $self.data('periodId');

            if (name != '') {
                $.when(
                    usesgraphcrt.schedule.addCustomRecord(url, name, text, scheduleId, periodId, 'confirmed', date)
                ).done(function(response) {
                    if (response !== false && response !== 'undefined') {
                        $block = $(document).find('[data-period-id=' + periodId + ']').find('.record-list');
                        $places = $(document).find('[data-period-id=' + periodId + ']').find('[data-role=places]');

                        val = +$places.html();
                        $places.html(--val);

                        $block.prepend('<div class="user-record"><label>' + name + '</label>' +
                            '<span data-role="target"' +
                            'data-schedule-id="' + scheduleId +
                            '" data-period-id="' + periodId +
                            '" data-url="'+ response.updateUrl +'"> ' +
                            '( <a class="record" data-record-id="' + response.recordId +
                            '" data-status="canceled" data-role="update-record">Отменить</a> | ' +
                            '<a class="record" data-record-id="' + response.recordId + '" data-role="delete-record" data-url="'+ response.cancelUrl +'"> '+
                            'Удалить</a>)</span></div>');

                        $(document).find('[data-role=record-to-date-modal]').modal('hide');
                    }
                });
            }

        });

        $timeStartSelect.on('change', function() {
            var self = this,
                selectedIndex = $(self).prop('selectedIndex');
            $timeStopSelect.prop('selectedIndex', selectedIndex + 1) ;
            $timeStopSelect.prop('selectedIndex', selectedIndex + 1) ;
        });

        $ownerSignObjectShowModalButton.on('click', function() {
            var self = this;
            $ownerSignObjectModal.modal('toggle');
            $ownerSignObjectModal.find('.modal-body').load('/schedule/schedule/client-choose-ajax?scheduleId=' + $(self).data('schedule-id') + '&periodId=' + $(self).data('period-id'));
        });

        $signCustomButton.on('click', function() {
            var $self = $(this),
                url = $self.data('url'),
                name = $ownerSignCustomObjectModal.find('[data-role=custom-record-name]').val(),
                text =  $ownerSignCustomObjectModal.find('[data-role=custom-record-text]').val(),
                scheduleId = $self.data('scheduleId'),
                periodId = $self.data('periodId');

            if (name != '') {
                $.when(
                    usesgraphcrt.schedule.addCustomRecord(url, name, text, scheduleId, periodId, 'confirmed')
                ).done(function(response) {
                    if (response !== false && response !== 'undefined') {
                        $block = $('[data-period-id=' + periodId + ']').find('.record-list');
                        $places = $('[data-period-id=' + periodId + ']').find('[data-role=places]');

                        val = +$places.html();
                        $places.html(--val);

                        $block.prepend('<div class="user-record"><label>' + name + '</label>' +
                                '<span data-role="target"' +
                                'data-schedule-id="' + scheduleId +
                                '" data-period-id="' + periodId +
                                '" data-url="'+ response.updateUrl +'"> ' +
                                '( <a class="record" data-record-id="' + response.recordId +
                                '" data-status="canceled" data-role="update-record">Отменить</a> | ' +
                                '<a class="record" data-record-id="' + response.recordId +
                                '" data-status="denied" data-role="update-record">Заблокировать</a> | ' +
                                '<a class="record" data-record-id="' + response.recordId + '" data-role="delete-record" ' +
                                    'data-url="'+ response.cancelUrl +'"> '+
                                'Удалить</a>)</span></div>');

                        $ownerSignCustomObjectModal.modal('hide');
                    }
                });
            }
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

                    $block.prepend('<div class="user-record"><label>' + label + '</label>' +
                            '<span data-role="target"' +
                            'data-schedule-id="' + scheduleId +
                            '" data-period-id="' + periodId +
                            '" data-url="'+ response.updateUrl +'"> ' +
                            '( <a class="record" data-record-id="' + response.recordId +
                            '" data-status="canceled" data-role="update-record">Отменить</a> | ' +
                            '<a class="record" data-record-id="' + response.recordId +
                            '" data-status="denied" data-role="update-record">Заблокировать</a> | ' +
                            '<a class="record" data-record-id="' + response.recordId + '" data-role="delete-record" data-url="/schedule/record/delete"> '+
                            'Удалить</a>)</span></div>');

                    $ownerSignObjectModal.modal('hide');
                }
            });
        });

        $(document).on('click', deleteRecord, function() {
            var self = this,
                url = $(self).data('url'),
                recordId = $(self).data('record-id');
            if (confirm('Вы уверены, что хотите удалить заявку пользователя?')) {
                $.when(
                    usesgraphcrt.schedule.deleteRecord(url, recordId)
                ).done(function(response) {
                    if (response !== false) {
                        console.log('test');
                        $(self).closest('.user-record').fadeOut('slow', function() {
                            $(this).remove();
                        });
                    }
                });
            }
        });

        $datePickerInput.on('change',function () {
            var self = $(this),
                url = self.data('url'),
                date = self.val(),
                scheduleId = $('[data-role=schedules-list] option:selected').val();

            usesgraphcrt.schedule.renderScheduleDay(url,date,scheduleId);

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

                selectedIndex = $timeStartSelect.prop('selectedIndex');
                $timeStopSelect.prop('selectedIndex', selectedIndex + 1) ;


            } else {
                $('#alertBtn').click();
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
            $ScheduleForm.submit();
        });

        $(document).on('click', updateRecord,function(){
            self = this;
            if ($(self).closest('[data-role=target]').data('record-date') != undefined) {
                date = $(self).closest('[data-role=target]').data('record-date');
            } else {
                date = null;
            }
            data= {
                recordId: $(self).data('record-id'),
                status: $(self).data('status'),
                periodId: $(self).closest('[data-role=target]').data('period-id'),
                scheduleId: $(self).closest('[data-role=target]').data('schedule-id'),
                date: date,
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
                type: 'GET',
                url: url,
                data: {model:model},
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
            key = $($value).data('target-id');
            arrTime[key] = {};
            $(this).find('[data-role=time-row]').each(function ($index, $value) {
                if ($($value).data('period-id') == '') {
                    periodId = 'NULL';
                } else {
                    periodId = $($value).data('period-id');
                }
                time = $(this).find('[data-role=schedule-day-item]').text();
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
                arrTime[key][$index] = {
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

    /*
    *
    */
    addCustomRecord: function(url, name, text, scheduleId, periodId, status, date) {
        if  (date == 'undefined') {
            date = null;
        }
        return $.ajax({
            type: "POST",
            url: url,
            data: {scheduleId: scheduleId, periodId: periodId, recordName: name, recordText: text, status: status, date: date},
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

    deleteRecord: function (url, recordId) {
        return $.ajax({
            type: "POST",
            url: url,
            data: {recordId: recordId},
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

    renderScheduleDay: function (url,date,scheduleId) {

        $('[data-role=schedule-on-day]').load(url,{date:date, scheduleId: scheduleId});

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
