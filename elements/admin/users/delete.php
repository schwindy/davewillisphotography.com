<?php
if (empty($_REQUEST['id'])) {
    redirect_to($route->home);
}

$db->delete('users', [
    'id' => $_REQUEST['id'],
]);

redirect_to($route->home);