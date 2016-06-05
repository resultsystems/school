<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-11 10:30:21
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:50:42
 */

namespace Domain\Auth;

use App;
use Domain\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use TypeError;

class AuthServiceTest extends \TestCase
{
    use DatabaseTransactions;

    public function test_cant_login()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt('teste123'),
        ]);

        $data = [
            'username' => uniqid(),
            'password' => 'teste123',
        ];

        $type = get_class($user->owner);

        $auth = App::make(AuthService::class);
        $logged = $auth->byCredentials($data, $type);
        $this->assertEquals($logged, ['error' => 'invalid_credentials']);
    }

    public function test_login_by_username()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt('teste123'),
        ]);

        $data = [
            'username' => $user->username,
            'password' => 'teste123',
        ];
        $type = get_class($user->owner);

        $auth = App::make(AuthService::class);
        $logged = $auth->byCredentials($data, $type);
        $this->assertTrue(isset($logged['token']));
        $this->assertTrue(isset($logged['user']['id']));
        $this->assertTrue(isset($logged['user']['owner']));
    }

    public function test_login_by_email()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt('teste123'),
        ]);

        $data = [
            'username' => $user->email,
            'password' => 'teste123',
        ];
        $type = get_class($user->owner);

        $auth = App::make(AuthService::class);
        $logged = $auth->byCredentials($data, $type);
        $response = $logged;
        $this->assertTrue(isset($response['token']));
        $this->assertTrue(isset($response['user']['id']));
    }

    public function test_login_by_user()
    {
        $user = factory(User::class)->create();

        $auth = App::make(AuthService::class);
        $logged = $auth->login($user);
        $this->assertTrue(isset($logged['token']));
        $this->assertTrue(isset($logged['user']['id']));
        $this->assertTrue(isset($logged['user']['owner']));
    }

    public function test_logout()
    {
        $user = factory(User::class)->create();

        $auth = App::make(AuthService::class);
        $logged = $auth->login($user);
        $this->assertTrue(isset($logged['token']));
        $this->assertTrue($auth->logout($logged['token']));
    }

    public function test_login_falied_exception()
    {
        $user = [];

        $auth = App::make(AuthService::class);
        $this->setExpectedException(TypeError::class);
        $auth->login($user);
    }
}
