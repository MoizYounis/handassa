<?php

namespace App\Contracts;

interface NotificationContract
{
    public function index($user_id);
    public function delete($id);
    public function deleteImage($id);
}
