<?php
acl_require_admin(CURRENT_PATH);
if (empty($_REQUEST['id'])) {
    redirect_to('/admin/kek/pages');
}

delete_element('/admin/kek/shop');