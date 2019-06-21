<?php

function job()
{
    if (empty($_REQUEST['args'])) {
        new APIResponse(0, "Missing parameter: args", $_REQUEST);
    }

    if (empty($_REQUEST['method'])) {
        new APIResponse(0, "Missing parameter: method", $_REQUEST);
    }

    /* Check Cached Jobs First */
    check_job_cache();

    /**
     * Create a Job
     *
     * @example PHP
        $job = new Job([
            'args' => [
                'field' => 'omegalul',
                'id' => 'kek',
                'interval' => 300,
                'model' => 'slime',
                'start' => 1509926400,
                'stop' => -60
            ],
            'method' => 'Graph::get_json',
        ]);
     */
    $job = new Job($_REQUEST);

    /* Save Job */
    $job->save();

    /* Handle Job | Respond with Job Layer Response (if the request requires it) or timeout */
    $job->handle();
}

function check_job_cache()
{
    read_job_cache(get_job_cache($_REQUEST['method']));
}

function get_job_cache($method)
{
    $filter = "ORDER BY created DESC LIMIT 100";
    $where = "WHERE method='$method' AND status='complete'";

    return Database::getInstance()->get_rows("SELECT * FROM jobs $where $filter");
}

function read_job_cache($cache)
{
    if (!empty($cache)) {
        $ttl = get_date('-30 Minutes');

        foreach ($cache as $job) {
            if ($job['method'] === 'Graph::get_json') {
                $args_raw = $_REQUEST['args'];
                $args = json_decode($args_raw);
                $job_args_raw = $job['args'];
                $job_args = json_decode($job_args_raw);

                if ($job_args->model === $args->model) {
                    if ($job_args->id === $args->id) {
                        $interval = (int)($args->interval ?? null);
                        $job_interval = (int)($job_args->interval ?? null);

                        if ($job_interval === $interval) {
                            if ($job_args->field === $args->field) {
                                $start = (int)($args->start ?? null);
                                $job_start = (int)($job_args->start ?? null);

                                $stop = (int)($args->stop ?? null);
                                $job_stop = (int)($job_args->stop ?? null);

                                if ($job_start === $start) {
                                    if ($job_stop === $stop) {
                                        if ($job['created'] > $ttl) {
                                            $response = json_decode($job['response']);
                                            new APIResponse(1, "Job Complete (cached)", $response);

                                            /* Debugging Usage */
                                            // new APIResponse(1, "Job Complete (cached)", [
                                            //     'args' => $args,
                                            //     'job_args' => $job_args,
                                            //     'result' => $response,
                                            // ]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}