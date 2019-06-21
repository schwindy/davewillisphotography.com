$(function(){
    $('.kek_button').each(function(){
        $(this).click(function(){
            ajax
            (
                '/php/'+$(this).attr('method'),
                {
                    'run':$(this).attr('run')
                },
                function(response){
                    console.log(response.message);
                }
            );
        });
    });

    $('.kek_touch_button').each(function(){
        $(this).on('touchstart', function(){
            ajax
            (
                '/php/'+$(this).attr('method'),
                {
                    'run':$(this).attr('run_start')
                },
                function(response){
                    console.log(response.message);
                }
            );
        });

        $(this).on('touchend', function(){
            ajax
            (
                '/php/'+$(this).attr('method'),
                {
                    'run':$(this).attr('run_stop')
                },
                function(response){
                    console.log(response.message);
                }
            );
        });
    });

    $('.kek_confirm_button').each(function(){
        $(this).click(function(){
            var href = $(this).attr('href');

            swal
            (
                {
                    title:"Danger!",
                    text:"Are you sure you want to do this?",
                    type:"warning",
                    confirmButtonColor:"#fe2208",
                    confirmButtonText:"Yes",
                    cancelButtonText:"Cancel",
                    showCancelButton:true,
                },
                function(){
                    location.href = href;
                }
            );

            return false;
        });
    });

    $('.kek_disabled_button').each(function(){
        $(this).click(function(e){
            e.preventDefault();

            swal
            (
                {
                    title:"Oops!",
                    text:"Sorry...\n\nThis feature is currently disabled...",
                    type:"error",
                    confirmButtonColor:"#fe2208",
                    confirmButtonText:"Okay",
                }
            );

            return false;
        });
    });
});