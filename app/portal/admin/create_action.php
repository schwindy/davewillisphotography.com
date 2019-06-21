<?php

function create_action()
{
    $db = Database::getInstance();

    new APIResponse(1, "Activity updated successfully");
}