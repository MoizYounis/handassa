<?php

namespace App\Http\Controllers\Api\Client;

use App\Contracts\PostContract;
use App\Enum\ResponseMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\PostRequest;
use App\Http\Requests\PostStatusRequest;
use App\Http\Requests\Client\ProposalStatusRequest;
use App\Http\Requests\PostRatingRequest;
use App\Http\Requests\Professional\PostProposalRequest;
use App\Http\Resources\PostProposalResourceCollection;
use App\Http\Resources\PostResourceCollection;
use App\Http\Resources\RatingResourceCollection;
use App\Models\Notification;
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
     * @OA\GET(
     *     path="/api/client/post/{id}/proposals",
     *     tags={"Posts"},
     *     summary="Post All Proposals",
     *     operationId="postAllProposals",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="integer",
     *             example="1",
     *         )
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Success"
     *     ),
     * )
     */
    public function proposals($id)
    {
        try {
            $proposals = $this->post->proposals($id);
            $proposals = new PostProposalResourceCollection($proposals);
            return $this->sendJson(true, "Success", $proposals);
        } catch (\Throwable $th) {
            logMessage("client/post/{$id}/proposals", $id, $th->getMessage());
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
                Notification::notification(
                    $this->_user->id,
                    $proposal->post->client_id,
                    $this->_user->name . ' sends you a proposal for the ' . $proposal->post->title,
                    $proposal->post_id
                );
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

    /**
     * @OA\Post(
     *     path="/api/client/proposal/status",
     *     tags={"Posts"},
     *     summary="Proposal Status Processing",
     *     operationId="proposalStatusProcessing",
     *     security={ {"sanctum": {} }},
     *      @OA\RequestBody(
     *         description="Proposal Status Processing",
     *         required=true,
     *      @OA\JsonContent(
     *               required={"id"},
     *               @OA\Property(property="id", type="integer", format="integer", example="1")
     *           ),
     *      ),
     *     @OA\Response(
     *         response="default",
     *         description="Success"
     *     ),
     * )
     */
    public function proposalStatus(ProposalStatusRequest $request)
    {
        try {
            DB::beginTransaction();
            $proposal_status = $this->post->proposalStatus($request->prepareRequest());
            if ($proposal_status) {
                DB::commit();
                return $this->sendJson(true, "Hired Successfully");
            }
            return $this->sendJson(false, ResponseMessages::MESSAGE_500);
        } catch (\Throwable $th) {
            DB::rollBack();
            logMessage("client/proposal/status", $request->input(), $th->getMessage());
            return $this->sendJson(false, ResponseMessages::MESSAGE_500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/post/status",
     *     tags={"Posts"},
     *     summary="Post Status",
     *     operationId="postStatus",
     *     security={ {"sanctum": {} }},
     *      @OA\RequestBody(
     *         description="Post Status",
     *         required=true,
     *      @OA\JsonContent(
     *               required={"id", "status"},
     *               @OA\Property(property="id", type="integer", format="integer", example="1"),
     *               @OA\Property(property="status", type="string", format="string", example="ended or completed")
     *           ),
     *      ),
     *     @OA\Response(
     *         response="default",
     *         description="Success"
     *     ),
     * )
     */
    public function postStatus(PostStatusRequest $request)
    {
        try {
            DB::beginTransaction();
            $post_status = $this->post->postStatus($request->prepareRequest());
            if ($post_status) {
                DB::commit();
                return $this->sendJson(true, "Contract " . ucfirst($request->status) . " Successfully!");
            }
            return $this->sendJson(false, ResponseMessages::MESSAGE_500);
        } catch (\Throwable $th) {
            DB::rollBack();
            logMessage("post/status", $request->input(), $th->getMessage());
            return $this->sendJson(false, ResponseMessages::MESSAGE_500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/post/rating",
     *     tags={"Rating"},
     *     summary="Post Rating",
     *     operationId="postRating",
     *     security={ {"sanctum": {} }},
     *      @OA\RequestBody(
     *         description="Post Rating",
     *         required=true,
     *      @OA\JsonContent(
     *               required={"post_id", "rating", "review"},
     *               @OA\Property(property="post_id", type="integer", format="integer", example="1"),
     *               @OA\Property(property="rating", type="string", format="string", example="3.4"),
     *               @OA\Property(property="review", type="string", format="string", example="Nice to work with him!")
     *           ),
     *      ),
     *     @OA\Response(
     *         response="default",
     *         description="Success"
     *     ),
     * )
     */
    public function rating(PostRatingRequest $request)
    {
        try {
            DB::beginTransaction();
            $rating = $this->post->rating($request->prepareRequest($this->_user->role));
            if ($rating) {
                DB::commit();
                return $this->sendJson(true, "Thanks for rating!");
            }
            return $this->sendJson(false, ResponseMessages::MESSAGE_500);
        } catch (\Throwable $th) {
            DB::rollBack();
            logMessage("post/rating", $request->input(), $th->getMessage());
            return $this->sendJson(false, ResponseMessages::MESSAGE_500);
        }
    }

    /**
     * @OA\GET(
     *     path="/api/client/professional/{id}/rating",
     *     tags={"Rating"},
     *     summary="Professional Rating",
     *     operationId="professionalRating",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="integer",
     *             example="1",
     *         )
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Success"
     *     ),
     * )
     */
    public function professionalRating($id)
    {
        try {
            $rating = $this->post->professionalRating($id);
            $rating = new RatingResourceCollection($rating);
            return $this->sendJson(true, "Success", $rating);
        } catch (\Throwable $th) {
            logMessage("client/professional/{$id}/rating", $id, $th->getMessage());
            return $this->sendJson(false, ResponseMessages::MESSAGE_500);
        }
    }
}
