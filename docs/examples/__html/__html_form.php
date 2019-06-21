<?php
// @SEE: ./app/lib/__html.php
echo __html('form', [
    'title'  => 'Contact Form',
    'fields' => [
        'customer_name'  => [
            'type'  => 'hidden',
            'value' => 'kek',
            'prop'  => [
                'id' => 'kek'
            ]
        ],
        'customer_email' => [
            'type'        => 'email',
            'value'       => 'kek@hue.kek',
            'placeholder' => 'Email',
        ],
        'customer_phone' => [
            'type'        => 'text',
            'value'       => '574-274-0410',
            'placeholder' => 'Phone # (xxx-xxx-xxxx)',
        ],
    ],
]);