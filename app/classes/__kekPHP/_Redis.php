<?php

/** kekPHP Redis Client (phpiredis)
 *
 * @Author: Robert Mariano Schwindaman (Git: schwindy | get.schwindy@gmail.com)
 * @Version: 0.1
 * @Note: PHP must be compiled with --enable-phpiredis for this class to work.
 *
 * @StatusCodes:
 * -1: Fatal   - A Fatal Exception was caught (Exception Object stored in $this->data)
 *  0: Error   - An Application Error has occurred (data may be available in $this->data, depends on the dev)
 *  1: Success - Code Execution Completed Successfully
 *  2: Warning - Code Execution Completed Successfully BUT something should "probably" be looked at and fixed
 *  3: Notice  - Code Execution Completed Successfully BUT this may be useful for debugging broken things
 *  4: Info    - Code Execution Completed Successfully BUT this may give more details about how exec went
 *
 * These status codes allow for "halt execution" logic as so:
 *
 * if(!$response->status)return $response;
 *
 * You can log a Response to your Browser Javascript Console.
 * How? By using the global kekPHP method, console(), in whatever code is running the _Redis method like so:
 *
 * console("Response: ", $response);
 *
 * @Example: JSON
 * {
 *     "status":1,
 *     "message": "Method run successfully!",
 *     "data":"ASDFGH-123456",
 *     "timestamp": 123456789,
 * }
 */
class _Redis
{
    public static $host = '/tmp/redis.sock';

    function __construct($args = [])
    {
        $this->client = empty($args['client']) ? $this->get_client() : $args['client'];
        $this->DELIM_KEY = '-_-';
    }

    function _respond($obj = 0, $message = "ERROR", $data = [])
    {
        // THIS BACKTRACE ACTUALLY CAUSES MEMORY CORRUPTION IN PHP 7.2.0 (in threaded context) @______@
        // $caller = debug_backtrace()[1]['function'];
        $caller = 'unknown_method_zend_heap_corrupt_af';
        $message = __CLASS__ . "::$caller(): $message";
        if (!is_object($obj)) {
            return new Response($obj, $message, $data);
        }
        $obj->message = $message;
        $obj->data = $data;

        return new Response($obj);
    }

    function compress($str = "", $level = 1)
    {
        try {
            if (empty($str)) {
                return $this->_respond(2, "Warning: param(str) is empty!", $str);
            }

            return $this->_respond(1, "Success!", gzencode($str, $level));
        } catch (Exception $e) {
            return $this->_respond(-1, "Exception (Fatal Error) Caught!", $e);
        }
    }

    function decode($str = "")
    {
        try {
            return $this->_respond(1, "Success!", explode($this->DELIM_KEY, $str));
        } catch (Exception $e) {
            return $this->_respond(-1, "Exception (Fatal Error) Caught!", $e);
        }
    }

    function decompress($str = "")
    {
        try {
            return $this->_respond(1, "Success!", gzdecode($str));
        } catch (Exception $e) {
            return $this->_respond(-1, "Exception (Fatal Error) Caught!", $e);
        }
    }

    function del($key = [])
    {
        try {
            $key = $this->encode($key);
            if (!$key->status) {
                return $key;
            }
            if (!$this->exists($key->data)) {
                $this->_respond(2, "Error: key does not exist!", $key->data);
            }

            return $this->_respond(1, "Success!", $this->exec("del $key->data"));
        } catch (Exception $e) {
            return $this->_respond(-1, "Exception (Fatal Error) Caught!", $e);
        }
    }

    function encode($arr = [])
    {
        try {
            if (empty($arr)) {
                return $this->_respond(0, "Error: param(arr) is empty!", debug_backtrace()[1]['function']);
            }
            if (!is_array($arr)) {
                return $this->_respond(2, "Warning: param(arr) is the wrong type, expected arr", $arr);
            }
            if (is_string($arr)) {
                return $this->_respond(3, "Notice: Skipped | Got str param, expected arr", $arr);
            }
            if (is_object($arr)) {
                return $this->_respond(1, "Success: Skipped | Got obj param, expected arr", $arr);
            }

            return $this->_respond(1, "Success!", implode($this->DELIM_KEY, $arr));
        } catch (Exception $e) {
            return $this->_respond(-1, "Exception (Fatal Error) Caught!", $e);
        }
    }

    function exec($cmd)
    {
        // echo vpre($this->client);
        $result = phpiredis_command($this->get_client(), $cmd);

        // phpiredis_disconnect($this->client);
        return $result;
    }

    function exists($key = [])
    {
        try {
            $key = $this->encode($key);
            if (!$key->status) {
                return $key;
            }

            return $this->_respond(1, "Success!", $this->exec("exists $key->data"));
        } catch (Exception $e) {
            return $this->_respond(-1, "Exception (Fatal Error) Caught!", $e);
        }
    }

    function flushall($key = [])
    {
        try {
            $key = $this->encode($key);
            if (!$key->status) {
                return $key;
            }

            return $this->_respond(1, "Success!", $this->exec("flushall"));
        } catch (Exception $e) {
            return $this->_respond(-1, "Exception (Fatal Error) Caught!", $e);
        }
    }

    function get($key = [])
    {
        try {
            $key = $this->encode($key);
            if (!$key->status) {
                return $key;
            }

            return $this->_respond(1, "Success!", $this->exec("get $key->data"));
        } catch (Exception $e) {
            return $this->_respond(-1, "Exception (Fatal Error) Caught!", $e);
        }
    }

    function getall($key = [])
    {
        return $this->hgetall($key);
    }

    function get_client()
    {
        if (empty($this->client)) {
            // echo "_Redis::exec(): Warning: client is corrupted, instantiating new one as emergency repair!";
            $this->client = phpiredis_connect(_Redis::$host);
        }

        return $this->client;
    }

    function hdel($key = [], $prop = "")
    {
        try {
            $key = $this->encode($key);
            if (!$key->status) {
                return $key;
            }
            if (empty($prop)) {
                return $this->_respond(0, "Error: Param(field) is empty!", $prop);
            }

            if (!$this->hexists($key->data, $prop->data)) {
                $this->_respond(2, "Warning: key does not exist!", $key->data);
            }

            return $this->_respond(1, "Success!", $this->exec("hdel $key->data $prop"));
        } catch (Exception $e) {
            return $this->_respond(-1, "Exception (Fatal Error) Caught!", $e);
        }
    }

    function hexists($key = [], $prop = "")
    {
        try {
            $key = $this->encode($key);
            if (!$key->status) {
                return $key;
            }
            if (empty($prop)) {
                return $this->_respond(0, "Error: Param(prop) is empty!", $prop);
            }

            return $this->_respond(1, "Success!", $this->exec("hexists $key->data $prop") ? true : false);
        } catch (Exception $e) {
            return $this->_respond(-1, "Exception (Fatal Error) Caught!", $e);
        }
    }

    function hget($key = [], $prop = "")
    {
        try {
            $key = $this->encode($key);
            if (!$key->status) {
                return $key;
            }
            if (empty($prop)) {
                return $this->_respond(0, "Error: Param(prop) is empty!", $prop);
            }

            return $this->_respond(1, "Success!", $this->exec("hget $key->data $prop"));
        } catch (Exception $e) {
            return $this->_respond(-1, "Exception (Fatal Error) Caught!", $e);
        }
    }

    function hgetall($key = [])
    {
        try {
            $key = $this->encode($key);
            if (!$key->status) {
                return $key;
            }

            $cmd = "hgetall $key->data";
            $response = $this->exec($cmd, $this->client);
            $result = [];
            $prop = "";
            $value = "";
            foreach ($response as $key => $val) {
                if ($key === 0) {
                    $prop = $val;
                } else {
                    if ($key % 2 === 0) {
                        if (!empty($prop) && isset($value)) {
                            $result[$prop] = $value;
                        }
                        $prop = $val;
                        $value = "";
                    } else {
                        $value = $val;
                    }
                }
            }

            if (!empty($prop) && !isset($result[$prop])) {
                $result[$prop] = $value;
            }

            return $this->_respond(1, "Success! $cmd", $result);
        } catch (Exception $e) {
            return $this->_respond(-1, "Exception (Fatal Error) Caught!", $e);
        }
    }

    function hset($key = [], $prop = "", $val = "")
    {
        try {
            $key = $this->encode($key);
            if (!$key->status) {
                return $key;
            }
            if (empty($prop)) {
                return $this->_respond(0, "Error: Param(prop) is empty!", $prop);
            }
            if (empty($val)) {
                return $this->_respond(0, "Error: Param(val) is empty!", $val);
            }

            return $this->_respond(1, "Success!", $this->exec("hset $key->data '$prop' '$val'", $this->client));
        } catch (Exception $e) {
            return $this->_respond(-1, "Exception (Fatal Error) Caught!", $e);
        }
    }

    function hset_multi($key = [], $args = [])
    {
        try {
            $key = $this->encode($key);
            if (!$key->status) {
                return $key;
            }
            if (empty($args)) {
                return $this->_respond(0, "Error: Param(args) is empty!", $args);
            }

            $str = "";
            foreach ($args as $prop => $val) {
                if (!empty($prop) && (!empty($val) || $val === 0)) {
                    $str .= "$prop $val ";
                }
            }

            return $this->_respond(1, "Success!", $this->exec("hmset $key->data $str"));
        } catch (Exception $e) {
            return $this->_respond(-1, "Exception (Fatal Error) Caught!", $e);
        }
    }

    function lindex($key = [], $id = "")
    {
        try {
            $key = $this->encode($key);
            if (!$key->status) {
                return $key;
            }

            return $this->_respond(1, "Success!", $this->exec("lindex $key->data $id"));
        } catch (Exception $e) {
            return $this->_respond(-1, "Exception (Fatal Error) Caught!", $e);
        }
    }

    function llen($key = [])
    {
        try {
            $key = $this->encode($key);
            if (!$key->status) {
                return $key;
            }

            return $this->_respond(1, "Success!", $this->exec("llen $key->data"));
        } catch (Exception $e) {
            return $this->_respond(-1, "Exception (Fatal Error) Caught!", $e);
        }
    }

    function lpop($key = [])
    {
        try {
            $key = $this->encode($key);
            if (!$key->status) {
                return $key;
            }

            return $this->_respond(1, "Success!", $this->exec("lpop $key->data"));
        } catch (Exception $e) {
            return $this->_respond(-1, "Exception (Fatal Error) Caught!", $e);
        }
    }

    function lpush($key = [], $args = [])
    {
        try {
            $key = $this->encode($key);
            if (!$key->status) {
                return $key;
            }
            if (empty($args)) {
                return $this->_respond(0, "Error: Param(args) is empty!", $args);
            }

            if (is_string($args)) {
                $str = $args;
            } else {
                $str = "";
                foreach ($args as $prop => $val) {
                    if (!empty($prop) && !empty($val)) {
                        $str .= "$prop $val";
                    }
                }
            }

            return $this->_respond(1, "Success!", $this->exec("lpush $key->data $str"));
        } catch (Exception $e) {
            return $this->_respond(-1, "Exception (Fatal Error) Caught!", $e);
        }
    }

    function lrange($key = [], $start = "0", $end = "-1")
    {
        try {
            $key = $this->encode($key);
            if (!$key->status) {
                return $key;
            }

            return $this->_respond(1, "Success!", $this->exec("lrange $key->data $start $end"));
        } catch (Exception $e) {
            return $this->_respond(-1, "Exception (Fatal Error) Caught!", $e);
        }
    }

    function lrem($key = [], $str = "", $limit = "0")
    {
        try {
            $key = $this->encode($key);
            if (!$key->status) {
                return $key;
            }

            return $this->_respond(1, "Success!", $this->exec("lrem $key->data $limit $str"));
        } catch (Exception $e) {
            return $this->_respond(-1, "Exception (Fatal Error) Caught!", $e);
        }
    }

    function sadd($key = [], $str = "")
    {
        try {
            $key = $this->encode($key);
            if (!$key->status) {
                return $key;
            }

            return $this->_respond(1, "Success!", $this->exec("sadd $key->data $str"));
        } catch (Exception $e) {
            return $this->_respond(-1, "Exception (Fatal Error) Caught!", $e);
        }
    }

    function set($key = [], $val = "")
    {
        try {
            $key = $this->encode($key);
            if (!$key->status) {
                return $key;
            }

            return $this->_respond(1, "Success!", $this->exec("set $key->data $val"));
        } catch (Exception $e) {
            return $this->_respond(-1, "Exception (Fatal Error) Caught!", $e);
        }
    }

    function smembers($key = [])
    {
        try {
            $key = $this->encode($key);
            if (!$key->status) {
                return $key;
            }

            return $this->_respond(1, "Success!", $this->exec("smembers $key->data"));
        } catch (Exception $e) {
            return $this->_respond(-1, "Exception (Fatal Error) Caught!", $e);
        }
    }

    function srem($key = [], $str = "")
    {
        try {
            $key = $this->encode($key);
            if (!$key->status) {
                return $key;
            }

            return $this->_respond(1, "Success!", $this->exec("srem $key->data $str"));
        } catch (Exception $e) {
            return $this->_respond(-1, "Exception (Fatal Error) Caught!", $e);
        }
    }

    function test()
    {
        $key = [
            'currency',
            'BTC',
        ];

        $hkey = [
            'history',
            '1490071561',
            'currency',
            'BTC',
        ];

        $encode = $this->encode($key);
        echo vpre($encode);
        nl();

        $decode = $this->decode($encode->data);
        echo vpre($decode);
        nl();

        $set = $this->set($key, 'kek');
        echo vpre($set);
        nl();

        $exists = $this->exists($key);
        echo vpre($exists);
        nl();

        $get = $this->get($key);
        echo vpre($get);
        nl();

        $del = $this->del($key);
        echo vpre($del);
        nl();

        $exists = $this->exists($key);
        echo vpre($exists);
        nl();

        $get = $this->get($key);
        echo vpre($get);
        nl();

        $hset = $this->hset($hkey, "last", "420");
        echo vpre($hset);
        nl();

        $hset = $this->hset($hkey, "last", "421");
        echo vpre($hset);
        nl();

        $hset = $this->hset($hkey, "last", 420);
        echo vpre($hset);
        nl();

        $hget = $this->hget($hkey, "last");
        echo vpre($hget);
        nl();

        $getall = $this->getall($hkey);
        echo vpre($getall);
        nl();

        $hset = $this->hset($hkey);
        echo vpre($hset);
        nl();

        $hset = $this->hset($key, "last");
        echo vpre($hset);
        nl();

        $hexists = $this->hexists($hkey, "last");
        echo vpre($hexists);
        nl();

        $hdel = $this->hdel($hkey, "last");
        echo vpre($hdel);
        nl();

        $hexists = $this->hexists($hkey, "last");
        echo vpre($hexists);
        nl();
    }
}