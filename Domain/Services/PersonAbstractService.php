<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:41:12
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:52:09
 */

namespace Domain\Services;

abstract class PersonAbstractService
{
    public function store(array $data)
    {
        $repo = $this->repo->store($data);
        $this->storeUser($data, $repo);

        $repo->load('user');

        return $repo;
    }

    public function update(array $data, $id)
    {
        $repo = $this->repo->update($data, $id);
        $user = $repo->user;
        if (isset($user->id)) {
            $this->updateUser($data, $user->id);
        } else {
            $data['user']['password'] = uniqid();
            $this->storeUser($data, $repo);
        }
        $repo->load('user');

        return $repo;
    }

    private function storeUser(array $data, $repo)
    {
        $userData = [
            'owner_id' => $repo->id,
            'owner_type' => get_class($repo),
            'username' => $data['user']['username'],
            'email' => $data['user']['email'],
            'password' => bcrypt($data['user']['password']),
        ];

        return $this->user->store($userData);
    }

    private function updateUser(array $data, $id)
    {
        $userData = [
            'username' => $data['user']['username'],
            'email' => $data['user']['email'],
        ];

        return $this->user->update($userData, $id);
    }
}
