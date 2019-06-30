<?php
if (!empty($user) && $user['id'] !== 'kek') {
    redirect_to('/logout');
}
if (empty($_REQUEST['plan'])) {
    //redirect_to('/plans');
}
?>
<div class="card text_center">
    <a href="/" title="" alt=""><img class="text_center" src="/img/logo/circle_icon_sm.png"></a>
    <h1>Sign Up</h1>
    <h4>Create an Account</h4>
    <p>Already have an account? <a href="/login">Log In</a></p>
</div>

<?php
echo __html('form', [
    'fields' => [
        'run'          => [
            'type'  => 'hidden',
            'value' => 'user/signup',
        ],
        'email'        => [
            'type'        => 'email',
            'placeholder' => 'example@website.com',
            'title'       => 'Email',
        ],
        'display_name' => [
            'type'        => 'text',
            'placeholder' => 'Your Name',
            'title'       => 'Name',
        ],
        'username'     => [
            'type'        => 'text',
            'placeholder' => 'cryptolytics',
            'title'       => 'Username',
        ],
        'password'     => [
            'type'        => 'password',
            'placeholder' => '@sdf1234!',
            'title'       => 'Password',
        ],
    ],
]);
?>
<script>
    var signup_busy = false;
    $('.kek_form').on('submit', function(e){
        e.preventDefault();
        if(!signup_busy) attempt_signup();
    });

    document.onkeypress = function(e){
        var key = window.event?e.keyCode:e.which;
        if(key=='13') if(!signup_busy) attempt_signup();
    };

    function attempt_signup()
    {
        var data = {};
        var invalid = false;

        $('.kek_form').children('input').each(function(){
            if(empty($(this).val()))
            {
                invalid = true;
                swal
                (
                    {
                        title:"Error!",
                        text:"Please enter Your "+$(this).prev().html()+'.',
                        type:"error",
                        confirmButtonColor:"#5890ff",
                        confirmButtonText:"Ok"
                    }
                );
                return false;
            }

            data[$(this).attr('name')] = $(this).val();
        });

        if(invalid) return false;
        signup_busy = true;

        data["account_type"] = '<?php echo $_REQUEST['plan'] ?? "" ?>';

        ajax
        (
            PATH_PORTAL,
            data,
            function(response){
                if(!response.status)
                {
                    __alert(response.message);
                    return false;
                }

                swal
                (
                    {
                        title:"Success!",
                        text:response.message,
                        type:"success",
                        confirmButtonColor:"#5890ff",
                        confirmButtonText:"Ok"
                    },
                    function(){
                        kek.SESSION.val("Signup.email", data.email);
                        location.href = '/login';
                    }
                );

                signup_busy = false;
                return false;
            },
            function(){
                signup_busy = false;
            }
        );
    }
</script>