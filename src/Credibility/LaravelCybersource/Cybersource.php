<?php namespace Credibility\LaravelCybersource;

/**
 * Class Cybersource
 * @package Credibility\LaravelCybersource
 */
class Cybersource {

    private $requester;

    public function __construct($requester)
    {
        $this->requester = $requester;
    }

    public function getSubscriptionStatus()
    {

    }

    public static function sign(array $params, $secretKey) {
        return self::signData(self::buildDataToSign($params), $secretKey);
    }

    public static function signData($data, $secretKey) {
        return base64_encode(hash_hmac('sha256', $data, $secretKey, true));
    }

    public static function buildDataToSign($params) {
        $signedFieldNames = ArrayHelper::csvToArray($params["signed_field_names"]);
        $signMe = '';
        foreach ($signedFieldNames as $field) {
            $signMe .= $field . '=' . $params[$field] . ',';
        }

        return rtrim($signMe, ',');
    }


} 