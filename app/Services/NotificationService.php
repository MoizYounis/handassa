<?php

namespace App\Services;

use App\Abstracts\BaseService;
use App\Contracts\NotificationContract;
use App\Models\Notification;
use App\Models\ProfessionalProjectImage;
use App\Models\User;

class NotificationService extends BaseService implements NotificationContract
{
    protected $project_image;
    public function __construct()
    {
        $this->model = new Notification();
        $this->project_image = new ProfessionalProjectImage();
    }
    public function index($user_id)
    {
        $notifications = $this->model->with('sender:id,name,image', 'receiver:id,name,image')->where('receiver_id', $user_id)->get();
        return $notifications;
    }

    public function delete($id)
    {
        $this->model->find($id)->delete();
        return true;
    }

    public function deleteImage($id)
    {
        $this->project_image->find($id)->delete();
        return true;
    }
}
