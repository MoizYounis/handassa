<?php

namespace App\Contracts;

interface AuthContract
{
    public function register($data);
    public function login($mobile_number);
    public function locations();
    public function services();
    public function categories();
    public function uploadImage($image);
    public function updateProfile($id, $data);
    public function deleteAccount($id);
}
