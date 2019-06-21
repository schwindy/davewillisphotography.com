<?php

function __kekPHP_garbage()
{
    // Enable Standard PHP Garbage Collection
    // @Note: This *has no effect* if already enabled via INI setting: zend.gc_enable
    if (!defined('__kekPHP_gc_enabled')) {
        gc_enable();
        define('__kekPHP_gc_enabled', 'true');
    }
}

function __kekPHP_garbage_force()
{
    // Invoke Standard PHP Forced Garbage Collection
    gc_collect_cycles();

    // Collect Zend Memory
    gc_mem_caches();
}

function gc_force() { __kekPHP_garbage_force(); }

// __kekPHP_garbage();