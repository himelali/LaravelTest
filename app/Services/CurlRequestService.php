<?php


namespace App\Services;


use App\Interfaces\NoSqlServiceInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class CurlRequestService implements NoSqlServiceInterface
{

    /**
     * Create a Document
     *
     * @param array $data Data
     * @return boolean
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function create(array $data)
    {
        dd($data);
        try {
            $client = new Client();
            $res = $client->request('post', 'http://exercise.api.rebiton.com/auth/register', [
                'form_params' => [
                    "code" => $data['code'],
                    "email" => $data['email'],
                    "password" => $data['password'],
                    "agreement" => (bool) $data['agreement']
                ],
                'headers' => [
                    'Content-Type:' => 'application/json'
                ]
            ]);
            $code = $res->getStatusCode();
            $body = $res->getBody();
            $body = json_decode($body);
            return isset($body->data) ? $body->data : false;
        } catch (ClientException $exception) {
            session()->flash("curl_error", $exception->getResponse()->getBody());
            return false;
        }
    }

    /**
     * Update a Document
     *
     * @param string $collection Collection/Table Name
     * @param mix $id Primary Id
     * @param array $document Document
     * @return boolean
     */
    public function update($id, array $document)
    {
        // TODO: Implement update() method.
    }

    /**
     * Delete a Document
     *
     * @param string $collection Collection/Table Name
     * @param mix $id Primary Id
     * @return boolean
     */
    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    /**
     * Search Document(s)
     *
     * @param array $credentials
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function login(array $credentials)
    {
        try {
            $client = new Client();
            $res = $client->request('post', 'https://exercise.api.rebiton.com/auth/login', [
                'form_params' => [
                    "email" => $credentials['email'],
                    "password" => $credentials['password']
                ],
                'headers' => [
                    'Content-Type:' => 'application/json'
                ]
            ]);
            $code = $res->getStatusCode();
            if($code != 200)
                return false;
            $body = $res->getBody();
            $body = json_decode($body);
            return isset($body->data) ? $body->data : false;
        } catch (ClientException $exception) {
            session()->flash("curl_error", $exception->getResponse()->getBody());
            return null;
        }
    }

    /**
     * Search Document(s)
     *
     * @param $token
     * @return array|boolean
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get($token)
    {
        try {
            $client = new Client();
            $res = $client->request('get', 'https://exercise.api.rebiton.com/user', [
                "headers" => [
                    'authorization' => 'Bearer ' . $token,
                    'accept'        => 'application/json',
                ]
            ]);
            $code = $res->getStatusCode();
            if($code != 200)
                return false;
            $body = $res->getBody();
            $body = json_decode($body);
            return isset($body->data) ? $body->data : false;
        } catch (ClientException $exception) {
            session()->flash("curl_error", $exception->getResponse()->getBody());
            return false;
        }
    }

    /**
     * Search Document(s)
     *
     * @return array|bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function language()
    {
        try {
            $client = new Client();
            $res = $client->request('get', 'https://exercise.api.rebiton.com/language');
            $code = $res->getStatusCode();
            if($code != 200)
                return false;
            $body = $res->getBody();
            $body = json_decode($body);
            return isset($body->data) ? $body->data : false;
        } catch (ClientException $exception) {
            session()->flash("curl_error", $exception->getResponse()->getBody());
            return false;
        }
    }
}
