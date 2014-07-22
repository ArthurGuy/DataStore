<?php namespace Data\Extensions;

use Illuminate\Auth\GenericUser;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\UserProviderInterface;

class HackAuthProvider implements UserProviderInterface {

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $id
     * @return \Illuminate\Auth\UserInterface|null
     */
    public function retrieveById($id)
    {
        return $this->dummyUser();
    }

    /**
     * Retrieve a user by the given credentials.
     * DO NOT TEST PASSWORD HERE!
     *
     * @param  array  $credentials
     * @return \Illuminate\Auth\UserInterface|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        return $this->dummyUser();
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Auth\UserInterface  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(UserInterface $user, array $credentials)
    {
        if (($credentials['username'] == $_ENV['USERNAME']) && (\Hash::check($credentials['password'], $_ENV['PASSWORD_HASH'])))
        {
            return true;
        }
        return false;
    }

    /**
     * Return a generic fake user
     */
    protected function dummyUser()
    {
        $attributes = array(
            'id' => 1,
            'username' => $_ENV['USERNAME'],
            'password' => $_ENV['PASSWORD_HASH'],
            'name' => 'Arthur',
        );
        return new GenericUser($attributes);
    }


    /**
     * Needed by Laravel 4.1.26 and above
     */
    public function retrieveByToken($identifier, $token)
    {
        return new \Exception('not implemented');
    }

    /**
     * Needed by Laravel 4.1.26 and above
     */
    public function updateRememberToken(UserInterface $user, $token)
    {
        return new \Exception('not implemented');
    }
} 