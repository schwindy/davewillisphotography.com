<?php
acl_require_admin(CURRENT_PATH);

if (!empty($_REQUEST['COMPANY_NAME'])) {
    save_config();
}

echo __html('form', [
    'title'  => 'Edit Configuration',
    'method' => 'post',
    'fields' => [
        'COMPANY_NAME'          => [
            'placeholder' => 'Company Name',
            'value'       => __config('COMPANY_NAME'),
        ],
        'COMPANY_NAME_LONG'     => [
            'placeholder' => 'Company Full Name (Google, Inc.)',
            'value'       => __config('COMPANY_NAME_LONG'),
        ],
        'COMPANY_TYPE'          => [
            'placeholder' => 'Business Type (LLC, INC, etc.)',
            'value'       => __config('COMPANY_TYPE'),
        ],
        'COMPANY_ADDRESS'       => [
            'placeholder' => 'Company Address',
            'value'       => __config('COMPANY_ADDRESS'),
        ],
        'COMPANY_LOCATION'      => [
            'placeholder' => 'Company City/State/Zip',
            'value'       => __config('COMPANY_LOCATION'),
        ],
        'SITE_NAME'             => [
            'placeholder' => 'Website Name',
            'value'       => __config('SITE_NAME'),
        ],
        'SITE_URL'              => [
            'placeholder' => 'Website URL (http://example.com)',
            'value'       => __config('SITE_URL'),
        ],
        'SITE_PROTOCOL'         => [
            'placeholder' => 'Protocol (http OR https)',
            'value'       => __config('SITE_PROTOCOL'),
        ],
        'SITE_URL_NAME'         => [
            'placeholder' => 'Website URL Name (example.com)',
            'value'       => __config('SITE_URL_NAME'),
        ],
        'SITE_KEYWORDS'         => [
            'placeholder' => 'Website Keywords (CSV)',
            'value'       => __config('SITE_KEYWORDS'),
        ],
        'SITE_HOME_DESCRIPTION' => [
            'placeholder' => 'Website Home Description (Landing Page)',
            'value'       => __config('SITE_HOME_DESCRIPTION'),
        ],
        'GOOGLE_ANALYTICS_ID'   => [
            'placeholder' => 'Google Analytics ID',
            'value'       => __config('GOOGLE_ANALYTICS_ID'),
        ],
        'SALES_EMAIL'           => [
            'placeholder' => 'Sales Email',
            'value'       => __config('SALES_EMAIL'),
        ],
        'SALES_PHONE'           => [
            'placeholder' => 'Sales Phone #',
            'value'       => __config('SALES_PHONE'),
        ],
        'SUPPORT_EMAIL'         => [
            'placeholder' => 'Support Email',
            'value'       => __config('SUPPORT_EMAIL'),
        ],
        'SUPPORT_PHONE'         => [
            'placeholder' => 'Support Phone #',
            'value'       => __config('SUPPORT_PHONE'),
        ],
        'SHOP_PROCESSING_FEE'   => [
            'placeholder' => 'Shop Processing Fee',
            'value'       => __config('SHOP_PROCESSING_FEE'),
        ],
    ],
]);
?>
<script>$('title').html($('title').html().replace('Index', 'Configuration'));</script>