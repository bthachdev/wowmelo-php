<?php

namespace Wowmelo;

class Wowmelo
{
    public static $clientId;
    public static $clientSecret;

    public static $accessToken = null;
    public static $accessTokenExpiresIn = null;

    public static $env = null;
    public static $apiBase = null;
    public static $apiVersion = null;

    public static $productApiBase = 'https://wowmelo.com';
    public static $sandboxApiBase = 'https://sandbox.wowmelo.com';

    /**
     * @param $clientId
     * @param $clientSecret
     * @param string $env
     */
    public static function setup($clientId, $clientSecret, $env)
    {
        self::setClientId($clientId);
        self::setClientSecret($clientSecret);
        self::setEnv($env);

        self::setAccessToken(null);
        self::setAccessTokenExpiresIn(null);

        self::authorize();
    }

    /**
     * @param $clientId
     */
    public static function setClientId($clientId)
    {
        self::$clientId = $clientId;
    }

    /**
     * @param $clientSecret
     */
    public static function setClientSecret($clientSecret)
    {
        self::$clientSecret = $clientSecret;
    }

    /**
     * @param $env
     */
    public static function setEnv($env)
    {
        if ($env === 'sandbox') {
            self::$apiBase = self::$sandboxApiBase;
        }
        else if ($env === 'production') {
            self::$apiBase = self::$productApiBase;
        }

        self::$env = $env;
    }

    /**
     * @param $accessToken
     */
    public static function setAccessToken($accessToken)
    {
        self::$accessToken = $accessToken;
    }

    /**
     * @param $accessTokenExpiresIn
     */
    public static function setAccessTokenExpiresIn($accessTokenExpiresIn)
    {
        self::$accessTokenExpiresIn = $accessTokenExpiresIn;
    }

    /**
     * @param $apiVersion
     */
    public static function setApiVersion($apiVersion)
    {
        self::$apiVersion = $apiVersion;
    }

    /**
     *
     */
    public static function authorize()
    {
        if (!is_null(self::$accessToken)
            && !is_null(self::$accessTokenExpiresIn)
            && date('Y-m-d H:i:s') < self::$accessTokenExpiresIn)
        {
            return;
        }

        $api = '/oauth/token';
        $client = new CurlClient();
        $client->init(self::$apiBase . $api);

        $params = [
            "grant_type" => "client_credentials",
            "client_id" => self::$clientId,
            "client_secret" => self::$clientSecret
        ];

        $response = $client->execute($params);

        self::$accessToken = $response->access_token;
        self::$accessTokenExpiresIn = date('Y-m-d H:i:s',strtotime("+"."$response->expires_in"."second"));
    }

    /**
     * @param $params
     * @return bool|string
     */
    public static function validateCustomer($params)
    {
        self::authorize();

        $api = '/api/validator';
        $client = new CurlClient();
        $client->init(self::$apiBase . $api);
        return $client->execute($params, self::$accessToken);
    }

    /**
     * @param $params
     * @return bool|string
     */
    public static function placeOrder($params)
    {
        self::authorize();

        $api = '/api/orders';
        $client = new CurlClient();
        $client->init(self::$apiBase . $api);
        return $client->execute($params, self::$accessToken);
    }

    /**
     * @param $params
     * @return bool|string
     */
    public static function cancelOrder($params)
    {
        self::authorize();

        $api = '/api/cancel';
        $client = new CurlClient();
        $client->init(self::$apiBase . $api);
        return $client->execute($params, self::$accessToken);
    }
}