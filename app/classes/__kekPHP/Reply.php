<?php

class Reply extends BaseClass
{
    var $docs = 'json_array';
    var $docs_html;

    function __construct($array = [])
    {
        parent::__construct($array);
    }

    function docs_html()
    {
        if (empty($this->docs)) {
            return false;
        }
        $docs = $this->docs;

        $html = "<h3 style='color:#000;text-align:left;'>Attachments</h3>";
        $i = 1;
        foreach ($this->docs as $id) {
            $doc = new Doc($id);
            $link =
                "<a target='_blank' style='color: blue;' href='" . SITE_URL . $doc->file_url . "'>" . $doc->display_name . "</a>";
            $html .= "<p style='color:#000;font-size:12pt;'>#$i: $link</p>";
            $i++;
        }

        return $html;
    }
}