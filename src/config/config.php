<?php

return [
    #to fill app type (uat or production)
    'APP_TYPE' => null,
    #to fill kbz uat url 
    'uat' => [
            #to fill kbz precreate uat url 
            'precreate' => null,
            #to fill kbz redirect uat url 
            'redirect_url' => null,
        ],
    #to fill kbz production url 
    'production' => [
        #to fill kbz precreate production url 
        'precreate' => null,
        #to fill kbz redirect production url 
        'redirect_url' => null,
    ],     
    #to fill appid   
    'appid' => null,
    #to fill merch_code   
    'merch_code' => null,
    #to fill app_key   
    'app_key' => null,
    #to fill merchnotify_url_code   
    'notify_url' => null,
    #to fill method   
    'method' => null,
    #to fill version   
    'version' => null,
    #this trade type for mobile and web
    'trade_type' => [
        'mobile' => 'PWAAPP',
        'web' => 'PAY_BY_QRCODE'
    ],
];

?>