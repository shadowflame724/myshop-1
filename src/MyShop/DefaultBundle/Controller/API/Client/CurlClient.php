<?php

namespace MyShop\DefaultBundle\Controller\API\Client;


class CurlClient
{
    private $host;

    public function __construct($host)
    {
        $this->host = $host;
    }

    public function send($jsonDataString, $uri = "")
    {
        $curl = curl_init($this->host . $uri);
        if ($curl === false) {
            throw new \Exception("Can't initialize curl lib");
        }

        // если сервер выполнил переадресацию, то идем за ним
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);

        // для того чтобы curl вернул в скрипт то что отдал сервер
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

        // задаем POST параметры
        curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonDataString);

        $response = curl_exec($curl);

        if ($response === false) {
            $message = curl_error($curl);
            throw new \Exception("Curl error: " . $message);
        }

        curl_close($curl);

        return $response;
    }
}