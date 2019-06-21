<?php
acl_require_admin(CURRENT_PATH);
$user = new User($user);

echo __html('card', [
    'class' => 'text_left',
    'text'  => __html('h1',
            ['text' => 'My Account', 'prop' => ['class' => 'text_left']]) . __html('br') . __html('edit_button', [
            'field'         => "username",
            'obj'           => $user,
            'run'           => "user/change_username",
            'subtext'       => "Enter a new Username",
            'title'         => "Username",
            'var'           => "Username",
            'val'           => $user->username,
            'input_default' => $user->username,
        ]) . __html('br') . __html('edit_button', [
            'field'         => "email",
            'obj'           => $user,
            'run'           => "user/change_email",
            'subtext'       => "Enter your new email",
            'title'         => "Email",
            'var'           => "Email",
            'val'           => $user->email,
            'input_default' => $user->email,
        ]) . __html('br') . __html('edit_button', [
            'field'         => "display_name",
            'obj'           => $user,
            'run'           => "user/change_display_name",
            'subtext'       => "Enter your full name",
            'title'         => "Name",
            'var'           => "Name",
            'val'           => $user->display_name,
            'input_default' => $user->display_name,
        ]) . __html('br') . __html('edit_button', [
            'field'   => "password",
            'obj'     => $user,
            'run'     => "user/change_password",
            'subtext' => "Enter your new password",
            'title'   => "Password",
            'var'     => "Password",
            'val'     => "",
        ])
]);