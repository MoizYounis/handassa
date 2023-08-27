<?php

namespace App\Http\Controllers\Api\Auth;

use App\Contracts\AuthContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\CheckMobileNumberRequest;
use App\Http\Requests\CheckUsernameRequest;
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
     * @OA\POST(
     *     path="/api/check_username",
     *     tags={"Auth"},
     *     summary="Check Username",
     *     operationId="checkUsername",
     *      @OA\RequestBody(
     *         description="Check Username",
     *         required=true,
     *         @OA\JsonContent(
     *               required={"username"},
     *               @OA\Property(property="username", type="string", format="string", example="Person1")
     *           ),
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Success"
     *     ),
     * )
     */
    public function checkUsername(CheckUsernameRequest $request)
    {
        return $this->sendJson(true, $request->username);
    }

    /**
     * @OA\POST(
     *     path="/api/check_mobile_number",
     *     tags={"Auth"},
     *     summary="Check Mobile Number",
     *     operationId="checkMobileNumber",
     *      @OA\RequestBody(
     *         description="Check Mobile Number",
     *         required=true,
     *         @OA\JsonContent(
     *               required={"mobile_number"},
     *               @OA\Property(property="mobile_number", type="string", format="string", example="+921234567890")
     *           ),
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Success"
     *     ),
     * )
     */
    public function checkMobileNumber(CheckMobileNumberRequest $request)
    {
        return $this->sendJson(true, $request->mobile_number);
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
     *               required={"role", "type", "username", "name", "mobile_number", "location"},
     *               @OA\Property(property="role", type="string", format="string", example="client"),
     *               @OA\Property(property="type", type="string", format="string", example="person"),
     *               @OA\Property(property="image", type="string", format="string", example="image.jpg"),
     *               @OA\Property(property="username", type="string", format="string", example="person1"),
     *               @OA\Property(property="name", type="string", format="string", example="My Name"),
     *               @OA\Property(property="mobile_number", type="string", format="string", example="+921234567890"),
     *               @OA\Property(property="phone_number", type="string", format="string", example="+921234567890 for freelancer company"),
     *               @OA\Property(property="location", type="string", format="string", example="Al Doha"),
     *               @OA\Property(property="cr_copy", type="string", format="string", example="cr_copy.jpg for company"),
     *               @OA\Property(property="id_copy", type="string", format="string", example="id_copy.jpg for freelancer"),
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
