<?php namespace Credibility\LaravelCybersource;

use Credibility\LaravelCybersource\Exceptions\CybersourceException;
use Credibility\LaravelCybersource\models\CybersourceSOAPModel;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\App;

class Cybersource {

    /**
     * @var Illuminate\Foundation\Application
     */
    public $app;

    private $requester;

    public $timeout = 10;

    public $avs_codes = array(
        'A' => 'Partial match: Street address matches, but 5-digit and 9-digit postal codes do not match.',
        'B' => 'Partial match: Street address matches, but postal code is not verified.',
        'C' => 'No match: Street address and postal code do not match.',
        'D' => 'Match: Street address and postal code match.',
        'E' => 'Invalid: AVS data is invalid or AVS is not allowed for this card type.',
        'F' => 'Partial match: Card member\'s name does not match, but billing postal code matches.',
        'G' => 'Not supported: Non-U.S. issuing bank does not support AVS.',
        'H' => 'Partial match: Card member\'s name does not match, but street address and postal code match.',
        'I' => 'No match: Address not verified.',
        'K' => 'Partial match: Card member\'s name matches, but billing address and billing postal code do not match.',
        'L' => 'Partial match: Card member\'s name and billing postal code match, but billing address does not match.',
        'M' => 'Match: Street address and postal code match.',
        'N' => 'No match: Card member\'s name, street address, or postal code do not match.',
        'O' => 'Partial match: Card member\'s name and billing address match, but billing postal code does not match.',
        'P' => 'Partial match: Postal code matches, but street address not verified.',
        'R' => 'System unavailable.',
        'S' => 'Not supported: U.S. issuing bank does not support AVS.',
        'T' => 'Partial match: Card member\'s name does not match, but street address matches.',
        'U' => 'System unavailable: Address information is unavailable because either the U.S. bank does not support non-U.S. AVS or AVS in a U.S. bank is not functioning properly.',
        'V' => 'Match: Card member\'s name, billing address, and billing postal code match.',
        'W' => 'Partial match: Street address does not match, but 9-digit postal code matches.',
        'X' => 'Match: Street address and 9-digit postal code match.',
        'Y' => 'Match: Street address and 5-digit postal code match.',
        'Z' => 'Partial match: Street address does not match, but 5-digit postal code matches.',
        '1' => 'Not supported: AVS is not supported for this processor or card type.',
        '2' => 'Unrecognized: The processor returned an unrecognized value for the AVS response.',
    );

    public $cvn_codes = array(
        'D' => 'The transaction was determined to be suspicious by the issuing bank.',
        'I' => 'The CVN failed the processor\'s data validation check.',
        'M' => 'The CVN matched.',
        'N' => 'The CVN did not match.',
        'P' => 'The CVN was not processed by the processor for an unspecified reason.',
        'S' => 'The CVN is on the card but waqs not included in the request.',
        'U' => 'Card verification is not supported by the issuing bank.',
        'X' => 'Card verification is not supported by the card association.',
        '1' => 'Card verification is not supported for this processor or card type.',
        '2' => 'An unrecognized result code was returned by the processor for the card verification response.',
        '3' => 'No result code was returned by the processor.',
    );

    public $result_codes = array(
        '100' => 'Successful transaction.',
        '101' => 'The request is missing one or more required fields.',
        '102' => 'One or more fields in the request contains invalid data.',
        '110' => 'Only a partial amount was approved.',
        '150' => 'Error: General system failure.',
        '151' => 'Error: The request was received but there was a server timeout.',
        '152' => 'Error: The request was received, but a service did not finish running in time.',
        '200' => 'The authorization request was approved by the issuing bank but declined by CyberSource because it did not pass the Address Verification Service (AVS) check.',
        '201' => 'The issuing bank has questions about the request.',
        '202' => 'Expired card.',
        '203' => 'General decline of the card.',
        '204' => 'Insufficient funds in the account.',
        '205' => 'Stolen or lost card.',
        '207' => 'Issuing bank unavailable.',
        '208' => 'Inactive card or card not authorized for card-not-present transactions.',
        '209' => 'American Express Card Identification Digits (CID) did not match.',
        '210' => 'The card has reached the credit limit.',
        '211' => 'Invalid CVN.',
        '221' => 'The customer matched an entry on the processor\'s negative file.',
        '230' => 'The authorization request was approved by the issuing bank but declined by CyberSource because it did not pass the CVN check.',
        '231' => 'Invalid credit card number.',
        '232' => 'The card type is not accepted by the payment processor.',
        '233' => 'General decline by the processor.',
        '234' => 'There is a problem with your CyberSource merchant configuration.',
        '235' => 'The requested amount exceeds the originally authorized amount.',
        '236' => 'Processor failure.',
        '237' => 'The authorization has already been reversed.',
        '238' => 'The authorization has already been captured.',
        '239' => 'The requested transaction amount must match the previous transaction amount.',
        '240' => 'The card type sent is invalid or does not correlate with the credit card number.',
        '241' => 'The request ID is invalid.',
        '242' => 'You requested a capture, but there is no corresponding, unused authorization record.',
        '243' => 'The transaction has already been settled or reversed.',
        '246' => 'The capture or credit is not voidable because the capture or credit information has laready been submitted to your processor. Or, you requested a void for a type of transaction that cannot be voided.',
        '247' => 'You requested a credit for a capture that was previously voided.',
        '250' => 'Error: The request was received, but there was a timeout at the payment processor.',
        '520' => 'The authorization request was approved by the issuing bank but declined by CyberSource based on your Smart Authorization settings.',
    );

    public $card_types = array(
        'Visa' => '001',
        'MasterCard' => '002',
        'American Express' => '003',
        'Discover' => '004',
        'Diners Club' => '005',
        'Carte Blanche' => '006',
        'JCB' => '007',
    );

    private $report_types = array(
        'payment_submission_detail' 	=> 'PaymentSubmissionDetailReport',
        'subscription_detail' 			=> 'SubscriptionDetailReport',
        'transaction_detail' 			=> 'TransactionDetailReport',
        'transaction_exception_detail' 	=> 'TransactionExceptionDetailReport',
    );


    public function __construct($requester, Application $app)
    {
        $this->requester = $requester;
        $this->app = $app;
    }

    public function getSubscriptionStatus($subscriptionId)
    {
        $request = $this->createSubscriptionRequest($subscriptionId);

        return $this->requester->send($request, '', '', '');
    }

    public function updateSubscription($subscriptionId)
    {

    }

    public function cancelSubscription($subscriptionId)
    {

    }

    public function createSubscriptionRequest($subscriptionId)
    {
        $request = new CybersourceSOAPModel(
            'PHP', phpversion(),
            $this->app->environment(),
            $this->app->make('config')->get('laravel-cybersource::merchant_id')
        );

        $subscriptionRetrieveRequest = new CybersourceSOAPModel();

        $request->paySubscriptionRetrieveService = $subscriptionRetrieveRequest;
        $subscriptionRetrieveRequest->run = 'true';

        $subscriptionInfo = new CybersourceSOAPModel();
        $subscriptionInfo->subscriptionID = $subscriptionId;

        $request->recurringSubscriptionInfo = $subscriptionInfo;

        return $request;
    }

    // Reports
    public function getSubscriptions($date)
    {
        return $this->sendReportRequest('SubscriptionDetailReport', $date);
    }

    public function getPaymentSubmissions($date)
    {
        return $this->sendReportRequest('PaymentSubmissionDetailReport', $date);
    }

    public function getTransactions($date)
    {
        return $this->sendReportRequest('TransactionDetailReport', $date);
    }

    public function getTransactionException($date)
    {
        return $this->sendReportRequest('TransactionExceptionDetailReport', $date);
    }

    private function sendReportRequest($report_name, $date)
    {
        $merchant_id = $this->app->make('config')->get('laravel-cybersource::merchant_id');
        $endpoint = $this->app->make('config')->get('laravel-cybersource::reports.endpoint');
        $username = $this->app->make('config')->get('laravel-cybersource::username');
        $password = $this->app->make('config')->get('laravel-cybersource::password');

        if ( !$date instanceof \DateTime ) {
            $date = new \DateTime($date);
        }

        // get the right host and substitute in our username and password for http basic authentication
        $url =
            'https://' .
            $username . ':' .
            $password . '@' .
            $endpoint .
            '/DownloadReport/' .
            $date->format('Y/m/d/') .
            $merchant_id . '/' .
            $report_name . '.csv';

        $result = @file_get_contents( $url );

        if ( $result === false ) {

            // this would be a lot easier if we could just have an error handler that throws exceptions, but here it is...
            $error = error_get_last();

            if ( isset( $error['message'] ) ) {

                // try to parse out the specific message, minus the function and crap
                $message = $error['message'];

                preg_match( '/failed to open stream: (.*)/', $message, $matches );

                if ( isset( $matches[1] ) ) {
                    $message = $matches[1];
                }

                if ( strpos( $message, 'The report requested cannot be found on this server' ) !== false ) {
                    throw new CybersourceException( $message, 400 );		// code 400? it's an HTTP 400 error. get it?
                }
                else {
                    // we don't know exactly what type of error, throw a generic error
                    throw new CybersourceException( $message );
                }

            }

            // something happened, but we dont' know what - die!
            throw new CybersourceException();

        }

        // parse out the results
        // but first, remove the first line - it's a header
        $result = substr( $result, strpos( $result, "\n" ) + strlen( "\n" ) );

        $records = CybersourceHelper::str_getcsv($result);

        return $records;

    }

} 