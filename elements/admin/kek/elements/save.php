<?php
if (empty($_REQUEST['id'])) {
    redirect_to('/admin/kek/pages');
}
save_element();