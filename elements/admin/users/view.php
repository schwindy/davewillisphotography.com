<?php
$row = $db->get_row("SELECT * FROM users WHERE id='$_REQUEST[id]'");
if (empty($row)) {
    redirect_to($route->home);
}
$user = new User($row);

echo __html('card', [
    'class' => 'text_left',
    'text'  => __html('h1', ['text' => "User: " . $user->id, 'prop' => ['class' => 'text_left']]) . __html('p',
            '<b>Name: </b>' . $user->display_name) . __html('p', '<b>Email: </b>' . $user->email) . __html('p',
            '<b>Created: </b>' . $user->created) . __html('p',
            '<b>Updated: </b>' . $user->updated) . __html('br') . __html('h3', [
            'text' => "Account Settings",
            'prop' => ['class' => 'text_left']
        ]) . __html('br') . __html('edit_button', [
            'field'         => "username",
            'obj'           => $user,
            'run'           => "user/change_username",
            'subtext'       => "Enter a new Username",
            'title'         => "Username",
            'var'           => "Username",
            'val'           => $user->username,
            'input_default' => $user->username,
        ]) . __html('br') . __html('edit_button', [
            'field'         => "password",
            'obj'           => $user,
            'run'           => "user/change_password",
            'subtext'       => "Enter a new Password",
            'title'         => "Password",
            'var'           => "Password",
            'val'           => "",
            'input_default' => '',
        ]) . __html('br') . __html('edit_button', [
            'field'         => "account_type",
            'obj'           => $user,
            'run'           => "user/change_account_type",
            'subtext'       => "Enter an Account Type (Free or Standard)",
            'title'         => "Account Type",
            'var'           => "Account Type",
            'val'           => $user->account_type,
            'input_default' => $user->account_type,
        ])
]);