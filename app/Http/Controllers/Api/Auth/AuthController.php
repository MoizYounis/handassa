<?php

namespace App\Http\Controllers\Api\Auth;

use App\Contracts\AuthContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $auth;
    public function __construct(AuthContract $auth)
    {
        $this->auth = $auth;
    }
    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Auth"},
     *     summary="Create Account",
     *     operationId="register",
     *      @OA\RequestBody(
     *         description="Register",
     *         required=true,
     *         @OA\JsonContent(
     *               required={"first_name", "occupation_id", "last_name", "email", "phone_number", "password","password_confirmation", "state", "city", "street", "latitude", "longitude"},
     *               @OA\Property(property="first_name", type="string", format="string", example="John"),
     *               @OA\Property(property="last_name", type="string", format="string", example="Doe"),
     *               @OA\Property(property="occupation_id", type="integer", format="integer", example="1"),
     *               @OA\Property(property="email", type="string", format="email", example="user@mail.com"),
     *               @OA\Property(property="phone_number", type="string", format="phone_number", example="+16472944676"),
     *               @OA\Property(property="password", type="string", format="password", example=""),
     *               @OA\Property(property="password_confirmation", type="string", format="string", example=""),
     *               @OA\Property(property="state", type="string", format="string", example=""),
     *               @OA\Property(property="city", type="string", format="string", example=""),
     *               @OA\Property(property="street", type="string", format="string", example=""),
     *               @OA\Property(property="zipcode", type="string", format="string", example=""),
     *               @OA\Property(property="latitude", type="string", format="string", example=""),
     *               @OA\Property(property="longitude", type="string", format="string", example=""),
     *           ),
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Success"
     *     ),
     * )
     */
    public function register(RegisterRequest $request)
    {
        $account = $this->auth->register($request->prepareRequest());
        return $this->sendJson(true, "Success", $account);
    }
}
