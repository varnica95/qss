<?php

namespace Qss\QSymfonySkeletonApi;

use Qss\Container;
use Qss\Includes\Session;

class QssApiService
{

    const API_URL = "https://symfony-skeleton.q-tests.com/api/v2/";

    /** @var $container */
    private $container;

    /**
     * Undocumented function
     *
     * @param Container $container
     * @return void
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Calling api
     *
     * @param string $url
     * @param string $method
     * @param array $headers
     * @param [type] $data
     * @return void
     */
    private function callCUrl(string $url, string $method, array $headers = [], $data = null)
    {
        $ret = array();
        $ret["message"] = null;
        $ret["error"] = true;
        $ret["code"] = 200;
        $ret["response"] = null;

        try {

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_POSTFIELDS => $data,
                CURLOPT_HTTPHEADER => $headers,
            ));

            $response = curl_exec($curl);
            curl_close($curl);

        } catch (\Exception $e) {
            $ret["message"] = $e->getMessage();
        }
        

        $responseArray = json_decode($response, TRUE);

        if(isset($responseArray["error"])){
            $ret["message"] = $responseArray["error"];
            $ret["code"] = $responseArray["code"];
            return $ret;
        }

        $ret["error"] = false;
        $ret["response"] = $responseArray;
            
        return $ret;
    }

    /**
     * Undocumented function
     *
     * @param [type] $email
     * @param [type] $password
     * @return void
     */
    public function auth($email, $password)
    {
      
        $url = self::API_URL . "token";
        $data = array("email" => $email, "password" => $password);
        $dataJson = json_encode($data);

        $response = $this->callCUrl($url, "POST", [], $dataJson);

        return $response;
    }

    public function getCurrentlyLoggedUser()
    {
        $url = self::API_URL . "me";

        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . Session::get('session_token')
        );

        $response = $this->callCUrl($url, "GET", $headers);

        return $response;
    }

    /**
     * Undocumented function
     *
     * @param [type] $queryString
     * @return void
     */
    public function fetchAuthorList($queryString = null)
    {
        $url = self::API_URL . "authors" . $queryString;

        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . Session::get('session_token')
        );

        $response = $this->callCUrl($url, "GET", $headers);

        return $response;
    }

    /**
     * Undocumented function
     *
     * @param [type] $authorId
     * @return void
     */
    public function getAuthorDetails($authorId)
    {
        $url = self::API_URL . "authors/" . $authorId;

        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . Session::get('session_token')
        );

        $response = $this->callCUrl($url, "GET", $headers);

        return $response;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function addNewBook(array $data)
    {
        $url = self::API_URL . "books";

        $dataJson = json_encode($data);

        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . Session::get('session_token')
        );

        $response = $this->callCUrl($url, "POST", $headers, $dataJson);

        return $response;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function deleteBook(string $bookId)
    {
        $url = self::API_URL . "books/" . $bookId;

        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . Session::get('session_token')
        );

        $response = $this->callCUrl($url, "DELETE", $headers);
        
        return $response;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function deleteAuthor(string $authorId)
    {
        $url = self::API_URL . "authors/" . $authorId;

        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . Session::get('session_token')
        );

        $response = $this->callCUrl($url, "DELETE", $headers);
        
        return $response;
    }
}