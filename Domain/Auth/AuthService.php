<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-11 10:30:21
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:17
 */

namespace Domain\Auth;

use Auth;
use Illuminate\Contracts\Auth\Authenticatable;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthService
{
    /**
     * @param array  $credentials
     *
     * @param string $type
     *
     * @return array
     */
    public function byCredentials(array $credentials, $type)
    {
        $email = array_get($credentials, 'username');
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = $email;
            unset($credentials['username']);
        }
        $credentials['owner_type'] = $type;

        try {
            // attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials)) {
                return ['error' => 'invalid_credentials'];
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return ['error' => 'could_not_create_token'];
        }

        return $this->getUser($token);
    }

    /**
     * @param Authenticatable $user
     * @param bool            $remember
     *
     * @return  array
     */
    public function login(Authenticatable $user, $remember = false)
    {
        $token = JWTAuth::fromUser($user);
        $user->load('owner');

        return compact('token', 'user');
    }

    /**
     * @return bool
     */
    public function logout($token = null)
    {
        if (is_null($token)) {
            return JWTAuth::invalidate(JWTAuth::getToken());
        }

        return JWTAuth::invalidate($token);
    }

    /**
     * Get user authenticate.
     *
     * @param  string $token
     * @return array
     */
    private function getUser($token)
    {
        $user = Auth::User();
        $user->load('owner');

        return compact('token', 'user');
    }
}
