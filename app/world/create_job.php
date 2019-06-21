<?php

function create_job()
{
    if (empty($_REQUEST['method'])) {
        new APIResponse(0, "Missing parameter: method", $_REQUEST);
    }

    if (empty($_REQUEST['args'])) {
        new APIResponse(0, "Missing parameter: args", $_REQUEST);
    }

    $_REQUEST['status'] = empty($_REQUEST['status']) ? 'waiting' : $_REQUEST['status'];

    $db = Database::getInstance();

    $filter = "ORDER BY created DESC LIMIT 100";
    $id = generate_job_id();
    $method = $_REQUEST['method'];
    $where = "WHERE method='$method' AND status='complete'";

    $cache = $db->get_rows("SELECT * FROM jobs $where $filter");
    if (!empty($cache)) {
        $ttl = get_date('-30 Minutes');

        foreach ($cache as $job) {
            if ($job['method'] === 'Graph::get_json') {
                $args_raw = $_REQUEST['args'];
                $args = json_decode($args_raw);
                $job_args_raw = $job['args'];
                $job_args = json_decode($job_args_raw);

                if ($job_args->model === $args->model ) {
                    if ($job_args->id === $args->id) {
                        $interval = empty($args->interval) ? null : (int)$args->interval;
                        $job_interval = empty($job_args->interval) ? null : (int)$job_args->interval;

                        if ($job_interval === $interval) {
                            if ($job_args->field === $args->field) {
                                $start = empty($args->start) ? null : (int)$args->start;
                                $job_start = empty($job_args->stop) ? null : (int)$job_args->start;

                                $stop = empty($args->stop) ? null : (int)$args->stop;
                                $job_stop = empty($job_args->stop) ? null : (int)$job_args->stop;

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

    if (!$db->insert('jobs', [
        'method' => $_REQUEST['method'],
        'id'     => $id,
        'args'   => $_REQUEST['args'],
        'status' => $_REQUEST['status'],
    ])) {
        new APIResponse(0, "Unable to Create Job...", $_REQUEST);
    };

    session_write_close();

    if (!empty($_REQUEST['local'])) {
        $response = call_user_func(['InvestorJobs', $_REQUEST['method']], json_decode($_REQUEST['args']));
        new APIResponse($response->status, $response->message, $response->data);
    }

    if (empty($_REQUEST['handle'])) {
        new APIResponse(1, "Job Created Successfully", ['job_id' => $id]);
    }

    $start = microtime(true);
    $counter = 0;
    $max = 120;
    $interval = 1;
    while ($counter < $max) {
        $job = $db->get_row("SELECT * FROM jobs WHERE id='$id' LIMIT 1");
        if (empty($job)) {
            new APIResponse(0, "Job not found in Database...", $_REQUEST);
        }
        if ($job['status'] === 'complete') {
            new APIResponse(1, "Job Complete", json_decode($job['response']));
        }

        $counter = $counter + $interval;
        if ($counter < $max) {
            time_sleep_until($start + $counter);
        } else {
            break;
        }
    }

    new APIResponse(0, "Request Timed Out...", ['job_id' => $id]);
}