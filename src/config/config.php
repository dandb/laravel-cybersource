<?php

return array(

    /**
     * The organization ID when creating the cybersource account
     */
    'organization_id' => '',

    /**
     * The Endpoint to hit
     * Change between test and prod environments
     */
    'wsdl_endpoint' => 'https://ics2wstest.ic3.com/commerce/1.x/transactionProcessor',
//    'https://ics2wstest.ic3.com/commerce/1.x/transactionProcessor/CyberSourceTransaction_1.26.wsdl'

    'outbound_merchant_id' => '',


    /**
     * Reports Endpoints
     * Change between test and prod environments
     */
    'reports' => array(
        'endpoint' => 'ebctest.cybersource.com/ebctest',
        'version' => '0.1',
        'api_version' =>  '2011-03',
    ),

    /**
     * Both the merchant and transaction IDs
     */
    'merchant_id' => '',
    'transaction_id' => '',

    /**
     * Timeout for requests
     */
    'timeout' => '',

    /**
     * Cybersource Username/Password info
     */
    'username' => '',
    'password' => '',


);

