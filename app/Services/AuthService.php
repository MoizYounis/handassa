<?php

namespace App\Services;

use App\Abstracts\BaseService;
use App\Contracts\AuthContract;
use App\Models\User;

class AuthService extends BaseService implements AuthContract
{
    public function __construct()
    {
        $this->model = new User();
    }
    public function register($data)
    {
        $model = $this->model;
        $account = $this->prepareData($model, $data, true);
        return $account;
    }

    private function prepareData($model, $data, $new_record = false)
    {
        if (isset($data['role']) && $data['role']) {
            $model->role = $data['role'];
        }

        if (isset($data['type']) && $data['type']) {
            $model->type = $data['type'];
        }

        if (isset($data['image']) && $data['image']) {
            $model->image = $data['image'];
        }

        if (isset($data['username']) && $data['username']) {
            $model->username = $data['username'];
        }

        if (isset($data['name']) && $data['name']) {
            $model->name = $data['name'];
        }

        if (isset($data['mobile_number']) && $data['mobile_number']) {
            $model->mobile_number = $data['mobile_number'];
        }

        if (isset($data['phone_number']) && $data['phone_number']) {
            $model->phone_number = $data['phone_number'];
        }

        if (isset($data['location']) && $data['location']) {
            $model->location = $data['location'];
        }

        if (isset($data['cr_copy']) && $data['cr_copy']) {
            $model->cr_copy = $data['cr_copy'];
        }

        if (isset($data['id_copy']) && $data['id_copy']) {
            $model->id_copy = $data['id_copy'];
        }

        $model->save();
        return $model;
    }
}
