<?php
// @SEE: ./app/lib/__html.php
echo __html('card', [
    'text' => __html('h1', ['text' => "Search"]) . __html('search', ['action' => '/admin/support/search'])
]);