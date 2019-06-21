<?php
if (!empty($user) && $user['account_type'] !== 'admin') {
    redirect_to('/user/');
}
echo __html('css', ['href' => '/css/login.css']);
?>

<div class="card text_center">
    <a href="/"><img class="text_center" src="/img/logo/circle_icon_sm.png"></a>
    <h1>Login</h1>
    <p>Need an account? <a href="/signup">Sign Up</a></p>
</div>

<div class="card text_center">
    <form class="kek_form">
        <h2 class="text_center">Username</h2>
        <input id="email" class="text_center email" type="email" placeholder="Email" autofocus>

        <h2 class="text_center">Password</h2>
        <input id="password" class="text_center password" type="password" placeholder="Password" autofocus>

        <div id="submit" class="button submit_button theme_color hover_opacity transition" style="padding: 20px;">
            <p>Go</p>
        </div>
    </form>
</div>

<script>
    $(function(){
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

        var data =
            {
                'run':'admin/login',
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

                location.href = empty('<?php echo $_REQUEST['redirect'] ?? ''?>')?'/admin/':'<?php echo $_REQUEST['redirect'] ?? ''?>';
            },
            error:function(response){
                console.log(response);
                __alert('An error occurred...sorry');
            }
        });
    }
</script>