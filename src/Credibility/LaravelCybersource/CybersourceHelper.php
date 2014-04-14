<?php namespace Credibility\LaravelCybersource;

class CybersourceHelper {

    public static function sign(array $params, $secretKey) {
        return static::signData(static::buildDataToSign($params), $secretKey);
    }

    public static function signData($data, $secretKey) {
        return base64_encode(hash_hmac('sha256', $data, $secretKey, true));
    }

    public static function buildDataToSign($params) {
        $signedFieldNames = static::csvToArray($params["signed_field_names"]);
        $signMe = '';
        foreach ($signedFieldNames as $field) {
            $signMe .= $field . '=' . $params[$field] . ',';
        }

        return rtrim($signMe, ',');
    }

    public static function csvToArray($csv) {
        return explode(",", $csv);
    }

    public static function arrayToCsv(array $array) {
        return implode(',', $array);
    }

} 