$(function(){
    $('.kek_checkbox').each(function(){
        $(this).click(function(){
            var icon = $(this).html()==='check_box'?'check_box_outline_blank':'check_box';
            ajax
            (
                PATH_PORTAL,
                {
                    'run':$(this).attr('run'),
                    'id':$(this).prop('id'),
                    'val':icon==='check_box'?1:0,
                },
                function(response){
                    if(!response.status) return __alert(response.message);
                    $(this).html(icon);
                    swal
                    (
                        {
                            title:"Settings Changed",
                            text:"Settings were changed successfully!",
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