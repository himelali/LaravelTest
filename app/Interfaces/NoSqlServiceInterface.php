<?php


namespace App\Interfaces;


interface NoSqlServiceInterface
{
    /**
     * Create a Document
     *
     * @param array  $document   Document
     * @return boolean
     */
    public function create(array $document);

    /**
     * Update a Document
     *
     * @param mix    $id         Primary Id
     * @param array  $document   Document
     * @return boolean
     */
    public function update($id, array $document);

    /**
     * Delete a Document
     *
     * @param mix    $id         Primary Id
     * @return boolean
     */
    public function delete($id);

    /**
     * Search Document(s)
     *
     * @param $credentials
     * @return array
     */
    public function login(array $credentials);

    /**
     * Search Document(s)
     *
     * @param $token
     * @return array
     */
    public function get($token);

    /**
     * Search Document(s)
     *
     * @return array
     */
    public function language();

}
