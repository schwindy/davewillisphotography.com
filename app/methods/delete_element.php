<?php

function delete_element($redirect = '/admin/kek/elements')
{
    if (empty($_REQUEST['id'])) {
        $_ALERT[] = "Missing id";

        return false;
    }

    $db = Database::getInstance();

    $elements = [];
    $pages = $db->get_rows("SELECT * FROM pages WHERE INSTR(body, '$_REQUEST[id]') > 0");
    foreach ($pages as $page) {
        $page = new Page($page);

        foreach ($page->body['elements'] as $val) {
            $match = false;
            foreach ($elements as $element) {
                if ($element === $val) {
                    $match = true;
                    break;
                }
            }

            if ($val === $_REQUEST['id'] || $match) {
                continue;
            }
            $elements[] = $val;
        }

        $db->update("pages", [
            'body' => json_encode(['elements' => $elements]),
        ], [
            'id' => $page->id,
        ]);
    }

    $db->delete("elements", [
        'id' => $_REQUEST['id'],
    ]);

    redirect_to($redirect);
}