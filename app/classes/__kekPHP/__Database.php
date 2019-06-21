<?php

/*
 * kekPHP MySQLi/Redis Wrapper Class
 * @Author : Robert Mariano Schwindaman (Git: schwindy | get.schwindy@gmail.com)
 * @Version: 0.2.0
 * @Updated: 03/23/2017
 *
 * Example Initialization:
 *
 * // Define Database Host
 * define('DB_HOST', 'localhost');
 *
 * // Define Database User
 * define('DB_USER', 'root');
 *
 * // Define Database Password
 * define('DB_PASS', 'root');
 *
 * // Define Database Name
 * define('DB_NAME', 'kek_php');
 *
 * // Define Database Display MySQL Error Output Setting (bool)
 * define('DISPLAY_DEBUG', true);
 *
 * // Require this file
 * require_once('Database.php');
 *
 * // Initialize Instance
 * $db = new Database();
 *
 * // OR (preferred *static* instantiation | note: more efficient but very buggy in threads)
 * $db = Database::getInstance();
 *
 * // Have fun, Skippy (monkey)
 * $users = $db->get_rows("SELECT * FROM users");
*/

class Database
{
    public static $counter  = 0;
    public        $display_debug;
    public        $enable_redis;
    static        $instance = null;
    public        $mysqli;
    public        $redis;

    private $db_name;
    private $db_host;
    private $db_pass;
    private $db_user;

    function __construct($args = [])
    {
        $this->display_debug = $args['display_debug'] ?? $this->_defaults('DB_DISPLAY_DEBUG');

        $this->db_host = $args['hostname'] ?? $this->_defaults('DB_HOST');
        $this->db_name = $args['database_name'] ?? $this->_defaults('DB_NAME');
        $this->db_pass = $args['password'] ?? $this->_defaults('DB_PASS');
        $this->db_user = $args['username'] ?? $this->_defaults('DB_USER');

        $this->enable_redis = $args['enable_redis'] ?? $this->_defaults('DB_ENABLE_REDIS');

        $this->_connect();
    }

    function __destruct()
    {
        if ($this->mysqli) {
            $this->disconnect();
        }
    }

    /**
     * Error Callback
     *
     * @param string $error
     * @param string $query
     * @return false
     */
    function __error($error, $query)
    {
        $dictionary = [
            "broken pipe"          => "_handle_broken_connection",
            "server has gone away" => "_handle_broken_connection",
            "Connection timed out" => "_handle_broken_connection",
        ];

        foreach ($dictionary as $needle => $handler) {
            if (strpos($error, $needle) === false) {
                continue;
            }

            if (method_exists($this, $handler)) {
                $result = call_user_func([$this, $handler]);
                if ($result === true) {
                    return true;
                }
            } else {
                $message = "Warning: Database Error Handler Method ($handler) does not exist!";
                echo "\n\n<error:Database>$message</error:Database>\n\n";
            }
        }

        if (DB_DISPLAY_DEBUG === true) {
            $date = date('Y-m-d H:i:s');
            $message = "<p>Error at $date}:\n</p>";
            $message .= "Error: $error";
            $message .= "<p>Query: $query<br></p>";
            echo "\n\n<error:Database>$message</error:Database>\n\n";
        }

        return false;
    }

    /**
     * Output an Object containing stats about the current instance
     *
     * @return __Object
     */
    function __stats()
    {
        return new __Object
        ([
            'counter' => self::$counter,
        ]);
    }

    function _connect()
    {
        try {
            $this->mysqli = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
            $this->mysqli->set_charset("utf8");
        } catch (Exception $e) {
            $message = "Failed to connect to MySQL: " . $this->mysqli->connect_error;
            trigger_error($message, E_USER_ERROR);
            die($message);
        }
    }

    /**
     * Get a Default Configuration Value
     *
     * @param string $const
     * @return string|null
     */
    function _defaults($const)
    {
        return defined($const) ? constant($const) : null;
    }

    /**
     * Handle a Broken Connection Error
     *
     * @return bool
     */
    function _handle_broken_connection()
    {
        $this->_connect();

        return true;
    }

    /**
     * Clean a Query (note: this is not escaping, it merely cleans it up)
     *
     * @param string $data
     * @return string $data
     */
    function clean($data)
    {
        $data = stripslashes($data);
        $data = html_entity_decode($data, ENT_QUOTES, 'UTF-8');
        $data = nl2br($data);
        $data = urldecode($data);

        return $data;
    }

    /**
     * Count the number of rows in a query result
     *
     * @param string $from
     * @param string $where
     * @return int
     */
    function count($from, $where = '')
    {
        if (!empty($where)) {
            $where = "WHERE $where";
        }
        $query = "SELECT COUNT(id) AS count FROM $from $where";
        if (empty(__kek_escape($query))) {
            return false;
        }

        return (int)$this->get_row($query)['count'];
    }

    /**
     * Delete Row(s) from a Table
     *
     * Example usage:
     * $where = ['user_id'=>44, 'email'=>'someotheremail@email.com'];
     * $db->delete('users', $where);
     *
     * @param string $table
     * @param array $where
     * @param string $limit
     * @return bool (False = Error)
     */
    function delete($table, $where = [], $limit = '')
    {
        if (empty($where)) {
            return false;
        }

        $sql = "DELETE FROM $table";
        $clause = [];
        foreach ($where as $field => $value) {
            $clause[] = "$field = '$value'";
        }

        $sql .= " WHERE " . implode(' AND ', $clause);
        if (!empty($limit)) {
            $sql .= " LIMIT $limit";
        }

        if (empty(__kek_escape($sql))) {
            return false;
        }
        $this->query($sql);
        if ($this->mysqli->error) {
            return $this->__error($this->mysqli->error, $sql);
        }

        return true;
    }

    /**
     * Delete a Hash from a Redis Set
     *
     * @param array $key
     * @param array $fields
     * @return Response|bool
     */
    function delete_redis($key, $fields = [])
    {
        if (!$this->enable_redis) {
            return false;
        }
        $redis = new _Redis();
        if (empty($key)) {
            return false;
        }
        foreach ($fields as $field => $val) {
            $result = $redis->hdel($key, $field);
            if (!$result->status) {
                return $result;
            }
        }

        return true;
    }

    /**
     * Disconnect from Database Server(s)
     * @Note: Called automatically by $this->__destruct() | Fix your garbage collection instead of using this. :D
     */
    function disconnect()
    {
        $thread = $this->mysqli->thread_id;
        $this->mysqli->kill($thread);
        $this->mysqli->close();
    }

    /**
     * Get Static Database Instance
     *
     * Example usage:
     * $db = Database::getInstance();
     *
     * @param array $args
     * @return Database
     */
    public static function getInstance($args = [])
    {
        if (!empty($args['fresh'])) {
            return new self($args);
        }
        if (self::$instance === null) {
            self::$instance = new self($args);
        }

        return self::$instance;
    }

    /**
     * Get a Table's Column Names represented in an array.
     *
     * @param string $table
     * @param string $database
     * @return array
     */
    function get_columns($table, $database = "")
    {
        if (empty($database)) {
            $database = $this->_defaults("DB_NAME");
        }

        return $this->query("SELECT COLUMN_NAME 
            FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_SCHEMA='$database' 
            AND TABLE_NAME='$table'");
    }

    /**
     * Execute a MySQLi query and Return an Associative Array that represents the single result
     *
     * Example usage:
     * $row = $db->get_row("SELECT * FROM users WHERE id='kek'");
     *
     * @param string $query
     * @return array|false
     */
    function get_row($query)
    {
        if (empty(__kek_escape($query))) {
            return false;
        }
        $row = $this->query($query);
        if ($this->mysqli->error) {
            return $this->__error($this->mysqli->error, $query);
        }

        return mysqli_fetch_assoc($row);
    }

    /**
     * Execute a MySQLi query and Return an Array of Associative Arrays that represent the results
     *
     * Example usage:
     * $row = $db->get_rows("SELECT * FROM users");
     *
     * @param string $query
     * @return array|false
     */
    function get_rows($query)
    {
        if (empty(__kek_escape($query))) {
            return false;
        }

        return $this->get_rows_assoc($query);
    }

    /**
     * Execute a MySQLi Query and Fetch an Associative Array from the mysqli result (Use get_row or get_rows)
     *
     * @param string $query
     * @return array|bool
     */
    function get_rows_assoc($query)
    {
        try {
            $results = $this->get_rows_mysql($query);
            $rows = [];
            if (empty($results)) {
                return false;
            }
            while ($r = $results->fetch_assoc()) {
                $rows[] = $r;
            }

            return $rows;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Execute a MySQLi Query *directly* (Use get_row or get_rows)
     *
     * @param string $query
     * @return mysqli_result|bool
     */
    function get_rows_mysql($query)
    {
        $rows = $this->query($query);
        if ($this->mysqli->error) {
            return $this->__error($this->mysqli->error, $query);
        }

        return $rows;
    }

    /**
     * Insert data into database table
     *
     * Example usage:
     * $user =
     * [
     *      'name' => 'Bennett',
     *      'email' => 'email@address.com',
     *      'active' => 1
     * ];
     *
     * $db->insert('users', $user);
     *
     * @param string $table
     * @param array $variables
     * @return bool
     *
     */
    function insert($table, $variables = [])
    {
        if (empty($variables)) {
            return false;
        }

        $sql = "INSERT INTO $table";
        $fields = [];
        $values = [];
        foreach ($variables as $field => $value) {
            $fields[] = $field;
            $values[] = "'" . $this->mysqli->real_escape_string($value) . "'";
        }

        $fields = ' (' . implode(', ', $fields) . ')';
        $values = '(' . implode(', ', $values) . ')';
        $sql .= "$fields VALUES $values";
        if (empty(__kek_escape($sql))) {
            return false;
        }
        $this->query($sql);

        if ($this->mysqli->error) {
            return $this->__error($this->mysqli->error, $sql);
        }

        return true;
    }

    /**
     * Set a Hash in a Redis Set
     *
     * @param array $key
     * @param array $fields
     * @return Response|bool
     */
    function insert_redis($key, $fields = [])
    {
        if (!$this->enable_redis) {
            return false;
        }
        //$redis = new _Redis();
        $key = implode("@_@", $key);
        if (empty($key)) {
            return false;
        }
        foreach ($fields as $field => $val) {
            if ($field === 'id') {
                continue;
            }
            if (empty($val)) {
                continue;
            }

            $one = "/var/www/skippy.thewashplant.com/thewashplant-kek/lib/redis-2.8.19/src/redis-cli";
            $cmd = "$one hset $key $field $val";
            $result = shell_exec($cmd);

            $cmd = "$one hget $key $field";
            $result = shell_exec($cmd);
            //$result = $redis->hset($key, $field, $val);
            //$hget = $redis->hget($key, $field);
            //echo "hget: $hget->data\n";
            //if(!$result->status)return false;
        }

        return true;
    }

    /**
     * Execute a Query
     *
     * @param string $query
     * @return mixed
     *
     */
    function query($query)
    {
        if (empty(__kek_escape($query))) {
            return false;
        }
        $result = $this->mysqli->query($query);
        if ($this->mysqli->error) {
            return $this->__error($this->mysqli->error, $query);
        }
        self::$counter++;

        return $result;
    }

    /**
     * Truncate a Table
     *
     * @param string $table
     * @return bool
     *
     */
    function truncate($table)
    {
        $query = "TRUNCATE TABLE $table";
        if (empty(__kek_escape($query))) {
            return false;
        }
        $this->query($query);
        if ($this->mysqli->error) {
            return false;
        }
        self::$counter++;

        return true;
    }

    /**
     * Update a MySQL Table
     *
     * Example usage:
     * $variables = ['name'=>'kek', 'email'=>'hue@kek.io'];
     * $where = ['id'=>44, 'name'=>'Bennett'];
     * $db->update('users', $variables, $where);
     *
     * @param string $table
     * @param array $variables
     * @param array $where
     * @param string $limit
     * @return bool
     */
    function update($table, $variables = [], $where = [], $limit = '')
    {
        if (empty($variables)) {
            return false;
        }

        $sql = "UPDATE $table SET ";
        $updates = [];
        foreach ($variables as $field => $value) {
            $value = $this->mysqli->real_escape_string($value);
            $updates[] = "`$field` = '$value'";
        }

        $sql .= implode(', ', $updates);

        $clause = [];
        if (!empty($where)) {
            foreach ($where as $field => $value) {
                $clause[] = "$field = '$value'";
            }
            $sql .= ' WHERE ' . implode(' AND ', $clause);
        }

        if (!empty($limit)) {
            $sql .= " LIMIT $limit";
        }
        if (empty(__kek_escape($sql))) {
            return false;
        }
        $this->query($sql);
        if ($this->mysqli->error) {
            return $this->__error($this->mysqli->error, $sql);
        }

        return true;
    }

    /**
     * Inserts or Updates a record based on the record existing
     *
     * @param string $table The name of the table to update
     * @param array $variables column => column value
     * @param string $id_field The name of the key field. This gets removed from the update values.
     * @return bool
     */
    function update_or_insert($table, $variables = [], $id_field = 'id')
    {
        $query = "SELECT * FROM $table WHERE $id_field='" . $variables[$id_field] . "'";
        if (empty(__kek_escape($query))) {
            return false;
        }
        $match = $this->get_row($query);
        if (empty($match)) {
            $this->insert($table, $variables);
        } else {
            $this->update($table, $variables, [$id_field => $variables[$id_field]]);
        }

        return !$this->mysqli->error;
    }
}