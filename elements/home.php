<?php
if (!empty($user)) {
    if ($user['account_type'] === 'admin') {
        redirect_to('/admin/home');
    } elseif ($user['account_type'] === 'dealer') {
        redirect_to('/dealers/home');
    } else {
        redirect_to('/');
    }
} else {
    redirect_to('/');
}