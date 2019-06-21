<?php

// The JobManager will retrieve Jobs from the Application Layer and store them in Cache
function JobManager($interval = 3)
{
    if (get_global_val('JobManager.status') !== 'running') {
        return false;
    }
    $redis = new _Redis(['client' => phpiredis_pconnect('/tmp/redis.sock')]);
    $start = microtime(true);
    $max = 60;
    $time_elapsed = 0;
    define('JOB_URL', 'http://kek.io/api/v1/jobs/');

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
            echo "JobManager::process(): Processing Job ($id):" . vpre($job);
            $run = explode("::", $job->method);
            $class = $run[0] . 'Task';
            $method = $run[1];
            $task = new $class($method, json_decode($job->args), json_decode($job->headers, true));

            if (!method_exists($task, $method)) {
                echo "JobManager::process(): Error: Method does not exist: $class::$method()\n";

                return update([
                    'id'       => $id,
                    'response' => new Response(0, 'Error: Method does not exist!', false),
                    'status'   => 'complete',
                ]);
            }

            echo "JobManager::process(): Invoking $class::$method()\n";
            $response = update([
                'id'       => $id,
                'response' => $task->$method(),
                'status'   => 'complete',
            ]);

            if (!$response->status) {
                echo "JobManager::process(): Error Updating Job:";
                echo vpre($response);

                return false;
            }

            echo "JobManager::process(): Job Complete: $id\n";

            return $response;
        } catch (Exception $e) {
            return new Response(-1, "Fatal: An uncaught exception occurred!", $e);
        }
    }

    function update($response)
    {
        echo "JobManager::update(): Sending Job::update() Request:";
        echo vpre($response);
        $headers = ['World: Skippy-1'];
        $result = json_decode(curl_post(JOB_URL, request_to_str($response), $headers));
        echo "JobManager::update(): Response:";
        echo vpre($result);

        return $result;
    }

    while ($time_elapsed < $max) {
        $response = json_decode(curl_get2(JOB_URL, [
            'World: Skippy-1',
        ]), true);

        if (empty($response)) {
            echo "JobManager::retrieve(): Job Response is empty!\n";
        } else {
            if (!is_array($response)) {
                $type = gettype($response);
                echo "\nJobManager::retrieve(): Warning: Job Response is non-array ($type):";
            } else {
                $response['data'] = json_decode(json_encode($response['data']));
                // echo "\nJobManager::retrieve(): Response Data:";echo vpre($response['data']);
                if (empty($response['data'])) {
                    echo "\nJobManager::retrieve(): No Jobs Found:";
                    echo vpre($response['data']);
                } else {
                    foreach ($response['data'] as $key => $job) {
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