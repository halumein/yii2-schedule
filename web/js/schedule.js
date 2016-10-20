
$(function () {


    var $selectTargetModel = $('[data-role=targetModelList]'),
        $selectTargetId = $('[data-role=targetId]'),
        $addTime = $('[data-role=addTime]'),
        $timeInput = $('[data-role=getTime]'),
        $arrTime = [],
        $testButton = $('[data-role=saveSchedule]');

    $testButton.on('click',function () {
       schedule.send($('meta[name=csrf-token]').attr("content"));
    });

    $selectTargetModel.on('change',function () {
        schedule.getTargetId($selectTargetModel.data('url'),$selectTargetModel.val(),$('meta[name=csrf-token]').attr("content"));
    });

    $addTime.on('click',function () {
        var url = window.location.href,
            time = $timeInput.val();
            (time == '') ? alert('Не задан период!') : schedule.setTime(url,time);
    });

    var schedule = {

        setTime: function (url,time) {
          $.ajax({
              type: 'POST',
              url: url,
              data: {data:time},
              success: function () {
                  console.log(time);
                  $('.tab-content .active [data-role=time]').append('<div class="col-md-2 fade in" data-role="schedule-item">'+time+'</div><br>');
                  $timeInput.val("");
              }
          });
        },

        getTargetId: function (url,model,csrfToken) {
            $.ajax({
                type: 'POST',
                url: url,
                data: {model:model,_csrf : csrfToken},
                success: function (response) {

                    if (response.status == 'success') {
                        console.log(response);
                         $.each(response.list,function (key,val) {
                             $selectTargetId.append('<option value="'+key+'">'+val+'</option>');
                         });
                    }
                }
            });
        },
        
        send: function (csrfToken) {
            $.ajax({
                type: 'POST',
                data: {_csrf : csrfToken},
                success: function (response) {
                        $('[data-role=schedule-day-period] [data-role=time]').each(function () {
                            alert($(this).text()+" / "+$(this).data('target'));
                        });
                    console.log($arrTime);
                }
            });
        }

    };

schedule.getTargetId($selectTargetModel.data('url'),$selectTargetModel.val(),$('meta[name=csrf-token]').attr("content"));
});
