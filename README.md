KBZ Payment Integration Package
============

<!-- [![Latest Stable Version](https://packagist.org/packages/kennebula/kbzpaymentintegration)] -->

Requirements
------------

* PHP >= 8.0;
* composer;

Features
--------

* PSR-4 autoloading compliant structure.
* Easy to use with Laravel framework.
* Useful tools for better code included.

Installation
============

    composer require kennebula/kbzpaymentintegration

Set Up Tools
============

Running Command:
--------------------------

    php artisan vendor:publish --provider="KenNebula\kbzPaymentIntegration\PackageServiceProvider" --tag="config"

Config Output
----------

    return [
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

* This command will create KBZ.php file inside config folder like this, 

* Important - You need fill the KBZ info in this config file for package usage.

Package Usage
------------

Send Payment (to get redirect url) :
----------------

    use KenNebula\kbzPaymentIntegration\kbz;

    KBZ::sendPayment(@multidimensionalArray $items,@String $customer_name, @Int $total_amount, @String $merchant_order_no);
* Note 

* items array must be include name, amount, quantity.
* customerName must be string.
* totalAmount must be integer.
* merchantOrderId must be string.

Load Output 
---------

* This will generate a kbz prebuild form url.    

Extract Callback Data:
----------------

    use KenNebula\kbzPaymentIntegration\kbz;

    kbz::callback(@String $paymentResult,@String $checkSum);

* Note 

* paymentResult must be string.
* checkSum must be string.

Callback Output 
------

* This will return decrypted data array include payment information.  

License
=======

KenNebula Reserved Since 2024.