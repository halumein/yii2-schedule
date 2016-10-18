
$(function () {


    var $testButton = $('[data-role=sendButton]'),
        $selectTargetModel = $('[data-role=targetModelList]'),
        $selectTargetId = $('[data-role=targetId]');


    $selectTargetModel.on('change',function () {
        schedule.getTargetId($selectTargetModel.data('url'),$selectTargetModel.val(),$('meta[name=csrf-token]').attr("content"));
    });

    $testButton.on('click',function () {
        var url = $testButton.data('url'),
            content = $testButton.html();
            schedule.send(url,content);
    });

    var schedule = {

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
                    console.log(response);
                }
            });
        }

    };

schedule.getTargetId($selectTargetModel.data('url'),$selectTargetModel.val(),$('meta[name=csrf-token]').attr("content"));
});
