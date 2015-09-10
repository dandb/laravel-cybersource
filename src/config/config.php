<?php

return array(

    /**
     * The timezone to be used by cybersource
     */
    'env' => 'test',

    /**
     * The timezone to be used by cybersource
     */
    'timezone' => 'America/Los_Angeles',

    /**
     * The organization ID when creating the cybersource account
     */
    'organization_id' => '',

    /**
     * The Endpoint to hit
     * Change between test and prod environments
     */
    'wsdl_endpoint' => 'https://ics2wstest.ic3.com/commerce/1.x/transactionProcessor/CyberSourceTransaction_1.26.wsdl',

    /**
     * Probably not necessary - currently not being used
     */
    'outbound_merchant_id' => '',

    /**
     * The currency format
     */
    'currency' => 'USD',

    /**
     * Reports Endpoints
     * Change between test and prod environments
     */
    'reports' => array(
        'endpoint' => 'ebctest.cybersource.com/ebctest',
        'version' => '0.1',
        'api_version' =>  '2011-03',
        'username' => '',
        'password' => '',
    ),

    /**
     * Both the merchant and transaction IDs
     */
    'merchant_id' => '',
    'merchant_reference_code' => '',
    'transaction_id' => '',

    /**
     * Timeout for requests
     */
    'timeout' => '10',

    /**
     * Cybersource Username/Password info
     */
    'username' => '',
    'password' => '',


);

