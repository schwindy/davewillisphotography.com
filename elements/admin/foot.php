<?php
$_SESSION['last_page'] = empty($_SESSION['last_page']) ? 'admin/home' : CURRENT_PAGE;
foreach ($_LOG as $log) {
    echo __log($log[0], $log[1]);
}
foreach ($_ALERT as $alert) {
    echo __alert($alert);
}

if (CURRENT_ROUTE === '/admin/login') {
    return false;
}

if ($user['id'] === 'kek') {
//    echo __html
//    (
//        'menu',
//        [
//            'title'=>'kekPHP > Main Menu',
//            'buttons'=>
//            [
//                'Configuration'=>
//                [
//                    'href'=>'/admin/kek/config',
//                    'text'=>'Manage Configuration',
//                ],
//                'Elements'=>
//                [
//                    'href'=>'/admin/kek/elements',
//                    'text'=>'Manage Elements',
//                ],
//                'Pages'=>
//                [
//                    'href'=>'/admin/kek/pages',
//                    'text'=>'Manage Pages',
//                ],
//                'Sales'=>
//                [
//                    'href'=>'/admin/sales',
//                    'text'=>'Analyze Sales',
//                ],
//            ],
//        ]
//    );
}