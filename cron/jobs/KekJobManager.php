<?php

// The KekJobManager will retrieve Jobs from the kekPHP Application Layer and store them in Cache
function KekJobManager($interval = 3)
{
    if (get_global_val('JobManager.status') !== 'running') {
        return false;
    }

    $redis = new _Redis(['client' => phpiredis_pconnect('/tmp/redis.sock')]);
    $start = microtime(true);
    $max = 60;
    $time_elapsed = 0;
    define('JOB_GET_QUERY', "?run=get_job");
    define('JOB_UPDATE_QUERY', "?run=update_job");
    define('JOB_URL', 'http://refracted.consulting/php/world');

    function finish($redis)
    {
        phpiredis_disconnect($redis);
        exit;
    }

    function process($job)
    {
        try {
            if (!is_object($job) || empty($job->id)) {
                return update([
                    'id'       => 'error-' . generate_mysql_id(),
                    'response' => new Response(0, 'Error: Invalid Job - Missing id', $job),
                    'status'   => 'complete',
                ]);
            }

            $id = $job->id;
            echo "KekJobManager::process(): Processing Job ($id):" . vpre($job);
            $run = explode("::", $job->method);
            $class = $run[0] . 'Task';
            $method = $run[1];

            $task = new $class($method, json_decode($job->args), json_decode($job->headers, true));

            if (!method_exists($task, $method)) {
                echo "KekJobManager::process(): Error: Method does not exist: $class::$method()\n";

                return update([
                    'id'       => $id,
                    'response' => new Response(0, 'Error: Method does not exist!', false),
                    'status'   => 'complete',
                ]);
            }

            echo "KekJobManager::process(): Invoking $class::$method()\n";
            $response = update([
                'id'       => $id,
                'response' => $task->$method(),
                'status'   => 'complete',
            ]);

            if (!$response->status) {
                echo "KekJobManager::process(): Error Updating Job:";
                echo vpre($response);

                return false;
            }

            echo "KekJobManager::process(): Job Complete: $id\n";

            return $response;
        } catch (Exception $e) {
            return new Response(-1, "Fatal: An uncaught exception occurred!", $e);
        }

    }

    function update($response)
    {
        echo "KekJobManager::update(): Sending Job::update() Request:";
        echo vpre($response);
        $headers = ['World: Skippy-1'];
        $result = json_decode(curl_post(JOB_URL . JOB_UPDATE_QUERY, request_to_str($response), $headers));
        echo "KekJobManager::update(): Response:" . vpre($result);

        return $result;
    }

    while ($time_elapsed < $max) {
        $raw = curl_post(JOB_URL . JOB_GET_QUERY, [], [
            'World: Skippy-1',
        ]);

        $response = json_decode($raw, true);

        if (empty($response)) {
            echo "KekJobManager::retrieve(): Job Response is empty!" . vpre($raw);
        } else {
            if (!is_array($response)) {
                $type = gettype($response);
                echo "\nKekJobManager::retrieve(): Warning: Job Response is non-array ($type):";
            } else {
                $jobs = json_decode(json_encode($response['data']));
                if (empty($jobs)) {
                    echo "\nKekJobManager::retrieve(): No Jobs Found:" . vpre($jobs);
                } else {
                    foreach ($jobs as $key => $job) {
                        if (empty($job)) {
                            continue;
                        }

                        process($job);
                    }
                }
            }
        }

        $time_elapsed = $time_elapsed + $interval;
        if ($time_elapsed < $max) {
            time_sleep_until($start + $time_elapsed);
        }
    }

    finish($redis);
}