<?php

function get_job()
{
    $db = Database::getInstance();
    $page_size = 3;
    $jobs = $db->get_rows("SELECT * FROM jobs WHERE status='waiting' ORDER BY id DESC LIMIT $page_size;");

    if (empty($jobs)) {
        new APIResponse(0, "No Jobs In Queue! Time to die...RIP", $jobs);
    }

    foreach ($jobs as $job) {
        $db->update('jobs', [
            'status' => 'working',
        ], [
            'id' => $job['id']
        ]);
    }

    new APIResponse(1, count($jobs) . " Job(s) Retrieved Successfully", $jobs);
}