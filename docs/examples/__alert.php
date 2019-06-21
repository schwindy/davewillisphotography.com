<?php
// @SEE: ./app/lib/__alert.php

// Place this code anywhere to create an alert (SweetAlert) in the Browser :D
__alert('kekPHP!');

// Redirect Example (go back)
echo __alert("Order has already been filled!", "back");

// Redirect Example (route home)
echo __alert("Order has already been filled!", $route->home);