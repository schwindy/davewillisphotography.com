<?php

if (KEK_LOG_LEVEL === 'debug') {
    echo __log('kekPHP|> Routes: ', $routes);
    echo __log('kekPHP|> Route->account: ', $route->account);
    echo __log('kekPHP|> Route->acl: ', $route->acl);
    echo __log('kekPHP|> Route->current_page: ', $route->current_page);
    echo __log('kekPHP|> Route->current_path: ', $route->current_path);
    echo __log('kekPHP|> Route->home: ', $route->home);
    echo __log('kekPHP|> Request: ', $_REQUEST);
    echo __log('kekPHP|> Title: ', $page->title($routes));
    echo __log('kekPHP|> Head: ', $page->head($routes));
    echo __log('kekPHP|> Nav: ', $page->nav($routes));
    echo __log('kekPHP|> Foot: ', $page->foot($routes));
    echo __log('kekPHP|> LOG_LEVEL: ' . KEK_LOG_LEVEL);
    echo __log('kekPHP|> ENVIRONMENT: ' . ENVIRONMENT);

    $message = 'kekPHP|> Elements: ';
    $body = $page->body;
    $elements = is_object($body) && !empty($body->elements) ? $body->elements : null;
    if (empty($elements)) {
        $message .= "none";
        $elements = [];
    }

    echo __log($message, $elements);
}

echo __log('kekPHP|> Complete');