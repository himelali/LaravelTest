<?php


namespace App\Providers;


use App\Services\CurlRequestService;
use App\User as UserAlias;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;

class CurlUserProvider implements UserProvider
{

    private $curl;

    /**
     * Create a new database user provider.
     *
     * @param CurlRequestService $curl
     */
    public function __construct(CurlRequestService $curl)
    {
        $this->curl = $curl;
    }


    /**
     * Retrieve a user by their unique identifier.
     *
     * @param mixed $identifier
     * @return array|bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function retrieveById($identifier)
    {
        return $this->retrieveByToken($identifier);
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param mixed $identifier
     * @param string $token
     * @return array|bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function retrieveByToken($identifier, $token = null)
    {
        return $this->curl->get($identifier) ?? null;
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param string $token
     * @return void
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        // TODO: Implement updateRememberToken() method.
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param array $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials) ||
            (count($credentials) === 1 &&
                array_key_exists('password', $credentials))) {
            return null;
        }
        $user = $this->curl->login($credentials);
        if(!$user)
            return null;
        $user = $this->getGenericUser($user);
        return $user ?? null;
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param array $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return true;
    }

    /**
     * Get the generic user.
     *
     * @param  mixed  $user
     * @return UserAlias
     */
    protected function getGenericUser($user)
    {
        if (! is_null($user)) {
            return new UserAlias((array) $user);
        }
    }

}
