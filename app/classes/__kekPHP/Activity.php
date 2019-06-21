<?php

class Activity extends BaseClass
{
    var $path;
    var $user;

    function __construct($args, $action = 'view')
    {
        if (is_string($args)) {
            $db = Database::getInstance();
            $user = get_user();

            $db->insert('activity', [
                'id'      => generate_mysql_id(),
                'user_id' => empty($user) ? 'anon' : $user['id'],
                'path'    => CURRENT_ROUTE,
                'ip'      => $_SERVER['REMOTE_ADDR'],
                'action'  => $action,
            ]);
        }

        parent::__construct($args);
    }

    function path()
    {
        $this->request = [];
        if (strpos($this->path, "?") !== false) {
            $this->request = explode('&', substr($this->path, strpos($this->path, "?") + 1));
            $this->request_human = json_encode($this->request);

            return substr($this->path, 0, strpos($this->path, "?"));
        }

        return $this->path;
    }

    /** Search $this->table_name MySQL table
     * @param array $args
     * @return __search HTML Element
     */
    public static function search($args = [])
    {
        $search = __search($args);
        if (!empty($args['q'])) {
            $args['where'] = '';
        }
        if (empty($args['href'])) {
            $args['href'] = '/activities/view?id=$id';
        }

        $html = empty($search['elements']) ? $search['html'] : __html('table', [
            'elements' => $search['elements'],
            'headers'  => [
                'User'    => '',
                'IP'      => '',
                'Action'  => '',
                'Path'    => '',
                'Created' => '',
                'Updated' => '',
                'View'    => 'print_invisible',
            ],
            'fields'   => [
                'user_id' => ['class' => 'bold'],
                'ip'      => [],
                'action'  => [],
                'path'    => [],
                'created' => [],
                'updated' => [],
                'button'  => [
                    'class'  => 'button blue_bg white print_invisible',
                    'href'   => $args['href'],
                    'tokens' => ['$id' => 'id']
                ]
            ],
        ]);

        return $html . $search['paginator_html'];
    }

    function user()
    {
        if (empty($this->user_id) || $this->user_id === 'anon') {
            return 'anon';
        }
        $user = get_user($this->user_id);
        if (empty($user)) {
            return false;
        }

        $this->user_account_type = $user['account_type'];
        $this->user_email = $user['email'];
        $this->user_name = $user['display_name'];

        return $user;
    }
}