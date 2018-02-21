<?php

namespace AppBundle\Service;

use \Firebase\JWT\JWT;

class ZoomClient
{
    private $url = 'https://api.zoom.us/v2';
    private $key;
    private $secret;

    public function __construct($key, $secret)
    {
        $this->key = $key;
        $this->secret = $secret;
    }

    public function getUsers() {
        $response = self::get('/users');

        return $response->users;
    }

    public function getUser($userId) {
        $response = self::get('/users/'.$userId);

        return $response;
    }

//    public function getUser() {
//        $ch = curl_init($this->url.'/users');
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//
//        // add token to the authorization header
//        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//            'Authorization: Bearer ' . self::generateJWT()
//        ));
//        $response = curl_exec($ch);
//        $response = json_decode($response);
//
//        return $response->users;
//    }

    public function getMeetings($userId) {
        $response = self::get('/users/'.$userId.'/meetings');

        return $response->meetings;
    }

    public function createMeeting($userId)
    {
        $data = array("topic" => "Test");
        $data_string = json_encode($data);

        $ch = curl_init($this->url.'/users/'.$userId.'/meetings');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

        // add token to the authorization header
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . self::generateJWT(),
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string),
        ));
        $response = curl_exec($ch);
        $response = json_decode($response);

        return $response;
    }

    private function get($entrypoint)
    {
        $ch = curl_init($this->url.$entrypoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // add token to the authorization header
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . self::generateJWT()
        ));
        $response = curl_exec($ch);
        $response = json_decode($response);

        return $response;
    }

    private function generateJWT() {
        $token = array(
            "iss" => $this->key,
            // The benefit of JWT is expiry tokens, we'll set this one to expire in 1 minute
            "exp" => time() + 60
        );
        return JWT::encode($token, $this->secret);
    }
}
