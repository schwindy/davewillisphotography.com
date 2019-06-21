<?php

class Action extends BaseClass
{
    var $user;

    function __construct($args)
    {
        if (is_string($args)) {
            $db = Database::getInstance();

            $db->insert('actions', [
                'id'      => generate_mysql_id(),
                'user_id' => get_user()['id'],
                'event'   => $args,
                'created' => get_date(),
            ]);
        }

        parent::__construct($args);
    }

    function user()
    {
        $db = Database::getInstance();
        $row = $db->get_row("SELECT * FROM users WHERE id='" . $this->user_id . "'");
        if (empty($row)) {
            return false;
        }

        $this->user_account_type = $row['account_type'];
        $this->user_email = $row['email'];
        $this->user_name = $row['display_name'];

        return $row;
    }
}