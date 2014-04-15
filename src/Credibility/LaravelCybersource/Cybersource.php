<?php namespace Credibility\LaravelCybersource;

use Credibility\LaravelCybersource\Exceptions\CybersourceException;
use Credibility\LaravelCybersource\models\CybersourceResponse;
use Credibility\LaravelCybersource\models\CybersourceSOAPModel;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\App;

class Cybersource {

    /**
     * @var Illuminate\Foundation\Application
     */
    public $app;
    /**
     * @var SOAPRequester
     */
    private $requester;
    public $timeout = 10;

    public $avsCodes = array(
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

    public $cvnCodes = array(
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

    public $cardTypes = array(
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

    /**
     * @param $subscriptionId
     * @return CybersourceResponse
     */
    public function getSubscriptionStatus($subscriptionId)
    {
        $request = $this->createSubscriptionRequest($subscriptionId);
        $rawResponse = $this->requester->send($request);
        $response = $this->convertToResponse($rawResponse);

        return $response;
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
            $this->app->make('config')->get('laravel-cybersource::merchant_id'),
            'MRC-123'
        );

        $subscriptionRetrieveRequest = new CybersourceSOAPModel();
        $subscriptionRetrieveRequest->run = 'true';

        $request->paySubscriptionRetrieveService = $subscriptionRetrieveRequest;

        $subscriptionInfo = new CybersourceSOAPModel();
        $subscriptionInfo->subscriptionID = $subscriptionId;

        $request->recurringSubscriptionInfo = $subscriptionInfo;

        return $request;
    }

    private function convertToResponse(CybersourceSOAPModel $response)
    {
        $response = array();
        if($response->decision == 'ACCEPT') {

        } else {

        }

        $responseObj = new CybersourceResponse($response);

        /*
         * if(!$this->isValid()) {
            $message = 'Fatal Error - No Response code returned from Cybersource';
            if(array_key_exists($model->reasonCode, $this->resultCodes)) {
                $message = $this->resultCodes[$model->reasonCode];
            }
            $this->response = array(
                'code' => $model->reasonCode,
                'message' => $message,
                'metadata' => array(
                    'requestID' => $model->requestID,
                    'requestToken' => $model->requestToken
                )
            );
        } else {

        }
         */

        return $responseObj;
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