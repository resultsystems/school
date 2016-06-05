<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-07 08:40:48
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:16
 */

namespace Domain\Auth;

use Auth;
use Illuminate\Routing\Controller;

class AuthController extends Controller
{
    /**
     * @var AuthService
     */
    protected $auth;

    /**
     * Construct.
     * @param AuthService $auth
     */
    public function __construct(AuthService $auth)
    {
        $this->auth = $auth;
    }

    public function login(AuthRequest $request)
    {
        $data = $request->only(['username', 'password']);
        $type = $request->get('type');
        $type = "Domain\\${type}\\${type}";
        $remember = $request->get('remember');

        $response = $this->auth->byCredentials($data, $type, $remember);

        if (isset($response['error'])) {
            return response()->json($response, 403);
        }

        return response()->json($response);
    }

    public function logout()
    {
        if ($this->auth->logout()) {
            return response(['logout' => true]);
        }

        return response(['logout' => false]);
    }
}
