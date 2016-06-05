<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-04-24 18:28:23
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:50:39
 */
use App\Domains\Employee\Employee;
use App\Domains\User\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $employee = factory(Employee::class)->create([
            'name' => 'Adminstrador', ]
        );
        factory(User::class)->create([
            'username' => 'admin',
            'owner_type' => Employee::class,
            'owner_id' => $employee->id,
        ]
        );
    }
}
