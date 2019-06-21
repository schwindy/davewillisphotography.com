<?php
if (!empty($user) && $user['account_type'] !== 'admin') {
    redirect_to('/user/');
}
?>

<div class="card text_center">
    <a href="/"><img class="text_center" src="/img/logo/circle_icon_sm.png"></a>
    <h1>Log In</h1>
    <h4>Access Your Cryptolytics Account</h4>
    <p>Do you need an account? <a href="/plans">Sign Up</a> is free!</p>
</div>

<div class="card">
    <form class="kek_form">
        <h3 class="text_center">Email / Username</h3>
        <input id="email" class="text_center email" type="email" placeholder="Username / Email" autofocus>

        <h3>Password</h3>
        <input id="password" class="text_center password" type="password" placeholder="Password">

        <div id="submit" class="button submit_button theme_color hover_opacity transition" style="padding: 20px;">
            <p>Go</p>
        </div>
    </form>
</div>

<script>
    $(function(){
        if(!empty(kek.SESSION.get("Signup.email"))) $('#email').val(kek.SESSION.get("Signup.email"));

        $('.kek_form').on('submit', function(e){
            e.preventDefault();
            attemptLogin();
        });

        $('#submit').click(function(){
            attemptLogin();
        });

        document.onkeypress = function(e){
            var key = window.event?e.keyCode:e.which;
            if(key=='13') attemptLogin();
        };
    });

    function loginCheck()
    {
        if(empty($('#email').val()))
        {
            __alert("Please enter your email address.");
            return false;
        }

        if(empty($('#password').val()))
        {
            __alert("Please enter your password.");
            return false;
        }

        return true;
    }

    function attemptLogin()
    {
        if(!loginCheck()) return false;

        var data = {
            'run':'user/login',
            'username':$('#email').val(),
            'password':$('#password').val(),
        };

        $.ajax
        ({
            type:"POST",
            url:"/php/portal",
            data:data,
            dataType:"json",
            success:function(response){
                if(!response.status)
                {
                    __alert(response.message);
                    return false;
                }

                location.href = empty('<?php echo $_REQUEST['redirect'] ?? ''?>')?'/user/':'<?php echo $_REQUEST['redirect'] ?? ''?>';
            },
            error:function(response){
                console.log(response);
                __alert('An error occurred...sorry');
            }
        });
    }
</script>