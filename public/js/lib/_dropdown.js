$(function(){
    $('.kek_dropdown').each(function(){
        $(this).on('change', function(){
            ajax
            (
                PATH_PORTAL,
                {
                    'run':$(this).attr('run'),
                    'id':$(this).prop('id'),
                    'val':$(this).val(),
                },
                function(response){
                    if(!response.status) return __alert(response.message);
                    swal
                    (
                        {
                            title:"Success",
                            text:"Settings were changed successfully!\n\nThis page will now refresh.",
                            type:"success",
                            confirmButtonColor:"#5890ff",
                            confirmButtonText:"Ok"
                        },
                        function(){
                            location.reload();
                        }
                    );
                }
            );
        });
    });
});