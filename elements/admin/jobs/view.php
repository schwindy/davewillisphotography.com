<?php
$row = $db->get_row("SELECT * FROM jobs WHERE id='$_REQUEST[id]'");
if (empty($row)) {
    redirect_to($route->home);
}
$obj = new Job($row);