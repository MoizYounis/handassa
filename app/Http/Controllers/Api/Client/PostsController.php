<?php

namespace App\Http\Controllers\Api\Client;

use App\Contracts\PostContract;
use App\Enum\ResponseMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\PostRequest;
use App\Http\Requests\Professional\PostProposalRequest;
use App\Http\Resources\PostResourceCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PostsController extends Controller
{
    protected $post;
    protected $_user;
    protected $_services;
    protected $_categories;
    public function __construct(PostContract $post)
    {
        $this->middleware(function ($request, $next) {
            if (auth()->check()) {
                $this->_user = auth()->user();
                $this->_services = auth()->user()->services->pluck('id')->toArray();
                $this->_categories = auth()->user()->categories->pluck('id')->toArray();
            }
            return $next($request);
        });
        $this->post = $post;
    }

    /**
     * @OA\GET(
     *     path="/api/posts",
     *     tags={"Posts"},
     *     summary=" All Posts",
     *     operationId="allPosts",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="new",
     *         )
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Success"
     *     ),
     * )
     */
    public function index(Request $request)
    {
        try {
            $args = [
                "status" => $request->status,
                "services" => $this->_services,
                "categories" => $this->_categories,
            ];
            $user = $this->_user;
            $posts = $this->post->index($user, $args);
            $posts = new PostResourceCollection($posts);
            return $this->sendJson(true, "Success", $posts);
        } catch (\Throwable $th) {
            logMessage("client/posts", "", $th->getMessage());
            return $this->sendJson(false, ResponseMessages::MESSAGE_500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/client/save/post",
     *     tags={"Posts"},
     *     summary="Save Post",
     *     operationId="savePost",
     *     security={ {"sanctum": {} }},
     *      @OA\RequestBody(
     *         description="Save Post",
     *         required=true,
     *      @OA\JsonContent(
     *               required={"service_id", "category_id", "title", "description", "image"},
     *               @OA\Property(property="service_id", type="integer", format="integer", example="1"),
     *               @OA\Property(property="category_id", type="integer", format="integer", example="1"),
     *               @OA\Property(property="title", type="string", format="string", example="title"),
     *               @OA\Property(property="description", type="string", format="string", example="description"),
     *               @OA\Property(property="image", type="string", format="string", example="image.jpg")
     *           ),
     *      ),
     *     @OA\Response(
     *         response="default",
     *         description="Success"
     *     ),
     * )
     */
    public function store(PostRequest $request)
    {
        try {
            DB::beginTransaction();
            $client_id = auth()->user()->id;
            $post = $this->post->store($client_id, $request->prepareRequest());
            if ($post) {
                DB::commit();
                return $this->sendJson(true, "Post added successfully!");
            }
            return $this->sendJson(false, ResponseMessages::MESSAGE_500);
        } catch (\Throwable $th) {
            DB::rollBack();
            logMessage("client/save/post", $request->input(), $th->getMessage());
            return $this->sendJson(false, ResponseMessages::MESSAGE_500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/professional/post/proposal",
     *     tags={"Posts"},
     *     summary="Post Proposal",
     *     operationId="postProposal",
     *     security={ {"sanctum": {} }},
     *      @OA\RequestBody(
     *         description="Post Proposal",
     *         required=true,
     *      @OA\JsonContent(
     *               required={"post_id", "price", "description"},
     *               @OA\Property(property="post_id", type="integer", format="integer", example="1"),
     *               @OA\Property(property="price", type="string", format="string", example="20.22"),
     *               @OA\Property(property="description", type="string", format="string", example="description")
     *           ),
     *      ),
     *     @OA\Response(
     *         response="default",
     *         description="Success"
     *     ),
     * )
     */
    public function proposal(PostProposalRequest $request)
    {
        try {
            $proposal_data = $request->prepareRequest();
            $proposal_data['professional_id'] = $this->_user->id;
            DB::beginTransaction();
            $proposal = $this->post->proposal($proposal_data);
            if ($proposal) {
                DB::commit();
                return $this->sendJson(true, "Proposal send successfully!");
            }
            return $this->sendJson(false, ResponseMessages::MESSAGE_500);
        } catch (\Throwable $th) {
            DB::rollBack();
            logMessage("professional/post/proposal", $request->input(), $th->getMessage());
            return $this->sendJson(false, ResponseMessages::MESSAGE_500);
        }
    }
}
