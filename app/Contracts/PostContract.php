<?php

namespace App\Contracts;

interface PostContract
{
    public function index($user, $args);
    public function store($client_id, $data);
    public function proposal($data);
    public function proposalStatus($data);
    public function postStatus($data);
}
