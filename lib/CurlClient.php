<?php

namespace Wowmelo;

use Wowmelo\Wowmelo;

class CurlClient
{
    protected $curlHandle = null;

    public function __destruct()
    {
        $this->closeCurlHandle();
    }

    /**
     * Initializes the curl handle. If already initialized, the handle is closed first.
     */
    public function init($url)
    {
        $this->closeCurlHandle();
        $this->curlHandle = curl_init($url);
    }

    /**
     * Executes the curl handle.
     *
     * @param $params
     * @param null $token
     * @return bool|string
     */
    public function execute($params, $token = null)
    {
        $headers = [ "Accept: application/json" ];

        if (!is_null($token)) {
            $headers = [
                "Accept: application/json",
                "Content-Type: application/json",
                "Authorization: Bearer " . $token
            ];
        }

        curl_setopt($this->curlHandle, CURLOPT_POST, true);
        curl_setopt($this->curlHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curlHandle, CURLOPT_POSTFIELDS, $params);
        curl_setopt($this->curlHandle, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($this->curlHandle);
        $this->closeCurlHandle();

        return json_decode($response);
    }

    /**
     * Closes the curl handle if initialized. Do nothing if already closed.
     */
    private function closeCurlHandle()
    {
        if (!is_null($this->curlHandle)) {
            curl_close($this->curlHandle);
            $this->curlHandle = null;
        }
    }
}