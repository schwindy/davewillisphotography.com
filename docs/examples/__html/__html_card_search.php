<?php
// @SEE: ./app/lib/__html.php
echo __html('card', [
    'text' => __html('h3', "Search") . __html('search', ['action' => '/admin/support/search'])
]);