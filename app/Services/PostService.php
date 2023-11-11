<?php

namespace App\Services;

use App\Abstracts\BaseService;
use App\Contracts\PostContract;
use App\Helpers\Constant;
use App\Models\ClientRating;
use App\Models\Post;
use App\Models\PostProposal;
use App\Models\ProfessionalRating;
use Illuminate\Support\Facades\Log;

class PostService extends BaseService implements PostContract
{
    protected $post_proposal;
    protected $professional_rating;
    protected $client_rating;
    public function __construct()
    {
        $this->model = new Post();
        $this->post_proposal = new PostProposal();
        $this->professional_rating = new ProfessionalRating();
        $this->client_rating = new ClientRating();
    }
    public function index($user, $args)
    {
        $posts = $this->model
            ->with('service:id,name', 'category:id,name', 'proposals')
            ->when($user->role == Constant::CLIENT, function ($query) use ($user, $args) {
                return $query->where('client_id', $user->id)
                    ->where('status', $args['status']);
            })
            ->when($user->role == Constant::PROFESSIONAL, function ($query) use ($user, $args) {
                if ($args["status"] == Constant::NEW) {
                    $query->where('status', Constant::NEW)
                        ->whereIn('service_id', $args["services"])
                        ->orWhereIn('category_id', $args["categories"]);
                } else {
                    $query->where('status', $args['status'])
                        ->where('professional_id', $user->id);
                }
            })
            ->orderBy('id', 'DESC')
            ->get();
        return $posts;
    }

    public function proposals($id)
    {
        $post = $this->model
            ->with('proposals')
            ->find($id);
        $proposals = $post->proposals()->with('professional:id,name,image')->get();
        return $proposals;
    }

    public function store($client_id, $data)
    {
        $model = $this->model;
        $data['client_id'] = $client_id;
        $post = $this->prepareData($model, $data, true);
        return $post;
    }

    private function prepareData($model, $data, $new_record = false)
    {
        if (isset($data['client_id']) && $data['client_id']) {
            $model->client_id = $data['client_id'];
        }

        if (isset($data['service_id']) && $data['service_id']) {
            $model->service_id = $data['service_id'];
        }

        if (isset($data['category_id']) && $data['category_id']) {
            $model->category_id = $data['category_id'];
        }

        if (isset($data['title']) && $data['title']) {
            $model->title = $data['title'];
        }

        if (isset($data['description']) && $data['description']) {
            $model->description = $data['description'];
        }

        if (isset($data['image']) && $data['image']) {
            $model->image = $data['image'];
        }

        $model->save();
        return $model;
    }

    public function proposal($data)
    {
        if (isset($data['post_id']) && $data['post_id']) {
            $this->post_proposal->post_id = $data['post_id'];
        }

        if (isset($data['professional_id']) && $data['professional_id']) {
            $this->post_proposal->professional_id = $data['professional_id'];
        }

        if (isset($data['price']) && $data['price']) {
            $this->post_proposal->price = $data['price'];
        }

        if (isset($data['description']) && $data['description']) {
            $this->post_proposal->description = $data['description'];
        }

        $this->post_proposal->save();
        return $this->post_proposal;
    }

    public function proposalStatus($data)
    {
        $proposal = $this->post_proposal->find($data['id']);
        $proposal->update([
            'status' => Constant::ACCEPTED
        ]);
        $this->model->find($proposal->post_id)->update([
            "professional_id" => $proposal->professional_id,
            'status' => Constant::PROCESSING
        ]);
        return true;
    }

    public function postStatus($data)
    {
        $this->model->find($data['id'])->update([
            'status' => $data['status']
        ]);
        return true;
    }

    public function rating($data)
    {
        $rating_model = $data["role"] == Constant::CLIENT ? $this->professional_rating : $this->client_rating;
        $rating_post = $this->model->find($data['post_id']);
        $rating_model->create([
            "post_id" => $rating_post->id,
            "professional_id" => $rating_post->professional_id,
            "client_id" => $rating_post->client_id,
            "rating" => $data['rating'],
            "review" => $data['review']
        ]);
        return true;
    }
}
