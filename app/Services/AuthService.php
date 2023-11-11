<?php

namespace App\Services;

use App\Abstracts\BaseService;
use App\Contracts\AuthContract;
use App\Helpers\Constant;
use App\Models\Category;
use App\Models\Location;
use App\Models\ProfessionalProjectImage;
use App\Models\Service;
use App\Models\User;
use App\Traits\ImageUpload;

class AuthService extends BaseService implements AuthContract
{
    use ImageUpload;
    protected $locations;
    protected $services;
    protected $categories;
    protected $professional_project_image;

    public function __construct()
    {
        $this->model = new User();
        $this->locations = new Location();
        $this->services = new Service();
        $this->categories = new Category();
        $this->professional_project_image = new ProfessionalProjectImage();
    }
    public function register($data)
    {
        $model = $this->model;
        $account = $this->prepareData($model, $data, true);
        return $account;
    }

    public function login($mobile_number)
    {
        $user = $this->model->where('mobile_number', $mobile_number)->first();
        return $user;
    }

    private function prepareData($model, $data, $new_record = false)
    {
        if (isset($data['role']) && $data['role']) {
            $model->role = $data['role'];
        }

        if (isset($data['type']) && $data['type']) {
            $model->type = $data['type'];
        }

        if (isset($data['experience']) && $data['experience']) {
            $model->experience = $data['experience'];
        }

        if (isset($data['total_project']) && $data['total_project']) {
            $model->total_project = $data['total_project'];
        }

        if (isset($data['project_done_by_app']) && $data['project_done_by_app']) {
            $model->project_done_by_app = $data['project_done_by_app'];
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

        if (isset($data['services']) && $data['services']) {
            $model->services()->sync($data['services']);
        }

        if (isset($data['categories']) && $data['categories']) {
            $model->categories()->sync($data['categories']);
        }

        if (isset($data['project_images']) && $data['project_images']) {
            foreach ($data['project_images'] as $key => $value) {
                $project_image = $this->upload($value);
                $this->professional_project_image::create([
                    "professional_id" => $model->id,
                    "image" => $project_image
                ]);
            }
        }

        return $model;
    }

    public function locations()
    {
        $locations = $this->locations->select('id', 'location')->whereStatus(Constant::TRUE)->get();
        return $locations;
    }

    public function services()
    {
        $services = $this->services->select('id', 'name')->whereStatus(Constant::TRUE)->get();
        return $services;
    }

    public function categories()
    {
        $categories = $this->categories->select('id', 'name', 'icon')->whereStatus(Constant::TRUE)->get();
        return $categories;
    }

    public function uploadImage($image)
    {
        if (isset($image) && $image) {
            $image = $this->upload($image);
            return $image;
        }
    }
}
