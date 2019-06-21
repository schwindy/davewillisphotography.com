<?php

class Element extends BaseClass
{
    var $data_clean;
    var $options    = 'json_array';
    var $properties = 'json_array';

    function __construct($array = [])
    {
        if (is_string($array)) {
            $db = Database::getInstance();
            $array = $db->get_row("SELECT * FROM elements WHERE id='$array'");
        }

        parent::__construct($array);
    }

    /** Output Clean Data (HTML) for Element IDE
     * @return string
     */
    function data_clean()
    {
        $this->data_clean = $this->data;

        if (strpos($this->data_clean, "</script>") !== false) {
            $this->data_clean = str_replace("<script>", "_script_", $this->data_clean);
            $this->data_clean = str_replace("</script>", "_/script_", $this->data_clean);
        }

        if (strpos($this->data_clean, '"') === false) {
            return $this->data_clean;
        }

        $this->data_clean = str_replace("\n", "<br>", $this->data_clean);
        $this->data_clean = str_replace("\r", "", $this->data_clean);

        return trim(str_replace('"', "'", $this->data_clean));
    }

    function options()
    {
        if (empty($this->options)) {
            return new __Object();
        }

        return $this->options;
    }

    /** Process Element Properties
     * @return array
     */
    function properties()
    {
        $id = $this->id;
        $row = Database::getInstance()->get_row("SELECT * FROM elements WHERE id='$id'");

        $properties = $this->properties;
        $properties['display_name'] = $row['display_name'];
        $properties['type'] = $row['type'];
        $properties['id'] = $row['id'];

        return $properties;
    }
}