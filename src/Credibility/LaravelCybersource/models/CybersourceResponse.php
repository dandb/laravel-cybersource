<?php namespace Credibility\LaravelCybersource\models;

use Credibility\LaravelCybersource\Exceptions\CybersourceException;

class CybersourceResponse {

    private $valid;

    /** @var array */
    private $response;
    private $reasonCode;

    private $request;

    private $resultCodes = array(
        '100' => 'Successful transaction.',
        '101' => 'The request is missing one or more required fields.',
        '102' => 'One or more fields in the request contains invalid data.',
        '104' => 'The access key and transaction uuid fields for this authorization request matches the access_key and transaction_uuid of another authorization request that you sent within the past 15 minutes.',
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
        '222' => 'Account frozen.',
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
        '475' => 'The cardholder is enrolled for payer authentication.',
        '476' => 'Payer authentication could not be authenticated.',
        '520' => 'The authorization request was approved by the issuing bank but declined by CyberSource based on your Smart Authorization settings.',
    );

    /**
     * Response object constructor method
     *
     * @param $response Array|CybersourceSoapModel [required]
     * @throws \Credibility\LaravelCybersource\Exceptions\CybersourceException
     */
    public function __construct($response)
    {
        if ($response instanceof CybersourceSOAPModel) {
            $response = $response->toArray();
        }

        $this->reasonCode = $this->getReasonCode($response);

        if(is_null($this->reasonCode)) {
            throw new CybersourceException('Response Code Not Provided');
        }

        if(!isset($response['decision'])) {
            throw new CybersourceException('Decision Not Provided');
        }

        if(!isset($this->resultCodes[$this->reasonCode])) {
            throw new CybersourceException('Invalid Response Code Provided');
        }
        $this->valid = $response['decision'] == 'ACCEPT' ? true : false;
        $this->response = $response;
        $this->response['message'] = $this->resultCodes[$this->reasonCode];
    }

    private function getReasonCode($responseArray) {
        $code = null;

        if (isset($responseArray['reasonCode'])) {
            $code = $responseArray['reasonCode'];
        } elseif (isset($responseArray['reason_code'])) {
            $code = $responseArray['reason_code'];
        }

        return $code;
    }

    // @codeCoverageIgnoreStart
    public function __set($name, $value)
    {
        $this->response[$name] = $value;
    }

    public function __get($name)
    {
        if(isset($this->response[$name])) {
            return $this->response[$name];
        }
        return null;
    }
    // @codeCoverageIgnoreEnd

    public function setRequest($request) {
        if($request instanceof CybersourceSOAPModel) {
            $this->request = $request->toArray();
        } else {
            $this->request = $request;
        }
    }

    public function getRequestData() {
        return $this->request;
    }

    /**
     * Checks whether the request was successful or failed
     * @return bool
     */
    public function isValid()
    {
        return $this->valid;
    }

    public function error() {
        if ($this->isValid()) {
            return false;
        } else {
            return !empty($this->response['message']) ? $this->response['message'] : false;
        }
    }

    /**
     * Returns an array of response data
     * @return array|mixed
     */
    public function getDetails()
    {
        return $this->response;
    }

}