
$(function () {


    var $testButton = $('[data-role=sendTime]'),
        $selectTargetModel = $('[data-role=targetModelList]'),
        $selectTargetId = $('[data-role=targetId]'),
        $addTime = $('[data-role=addTime]');


    $selectTargetModel.on('change',function () {
        schedule.getTargetId($selectTargetModel.data('url'),$selectTargetModel.val(),$('meta[name=csrf-token]').attr("content"));
    });

    $addTime.on('click',function () {
        var url = window.location.href,
            time = $('.tab-content .active [data-role=getTime]').val();
        schedule.setTime(url,time);
    });

    var schedule = {

        setTime: function (url,time) {
          $.ajax({
              type: 'POST',
              url: url,
              data: {data:time},
              success: function () {
                  console.log(time);
                  $('.tab-content .active [data-role=time]').text(time);
                  $('.tab-content .active [data-role=getTime]').val("");
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
        
        send: function (url,data, csrfToken) {
            $.ajax({
                type: 'POST',
                url: url,
                data: {data:data, _csrf : csrfToken},
                success: function (response) {
                    console.log(data);
                }
            });
        }

    };

schedule.getTargetId($selectTargetModel.data('url'),$selectTargetModel.val(),$('meta[name=csrf-token]').attr("content"));
});
