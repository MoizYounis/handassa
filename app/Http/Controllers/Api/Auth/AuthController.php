<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Enum\ResponseMessages;
use App\Contracts\AuthContract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\CheckUsernameRequest;
use App\Http\Requests\CheckMobileNumberRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ProfileRequest;
use App\Http\Resources\ServiceResourceCollection;
use App\Http\Resources\CategoryResourceCollection;
use App\Http\Resources\LocationResourceCollection;
use App\Http\Resources\LoginResponse;

class AuthController extends Controller
{
    protected $auth;
    protected $user;
    public function __construct(AuthContract $auth)
    {
        $this->auth = $auth;
        $this->middleware(function ($request, $next) {
            if (auth()->check()) {
                $this->user = auth()->user();
            }

            return $next($request);
        });
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
        try {
            return $this->sendJson(true, $request->username);
        } catch (\Throwable $th) {
            logMessage("check_username", $request->input(), $th->getMessage());
            return $this->sendJson(false, ResponseMessages::MESSAGE_500);
        }
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
        try {
            return $this->sendJson(true, $request->mobile_number);
        } catch (\Throwable $th) {
            logMessage("check_mobile_number", $request->input(), $th->getMessage());
            return $this->sendJson(false, ResponseMessages::MESSAGE_500);
        }
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
     *               @OA\Property(property="username", type="string", format="string", example="person1"),
     *               @OA\Property(property="experience", type="integer", format="integer", example="3"),
     *               @OA\Property(property="name", type="string", format="string", example="My Name"),
     *               @OA\Property(property="mobile_number", type="string", format="string", example="+921234567890"),
     *               @OA\Property(property="phone_number", type="string", format="string", example="+921234567890 for professional person and company"),
     *               @OA\Property(property="location", type="string", format="string", example="Al Doha"),
     *               @OA\Property(property="services", type="string", format="string", example="[1,2] for professional"),
     *               @OA\Property(property="categories", type="string", format="string", example="[1,2] for professional"),
     *               @OA\Property(property="image", type="string", format="string", example="image to upload"),
     *               @OA\Property(property="cr_copy", type="string", format="string", example="cr copy to upload for company"),
     *               @OA\Property(property="id_copy", type="string", format="string", example="id copy to upload for professional person")
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
        try {
            DB::beginTransaction();
            $account = $this->auth->register($request->prepareRequest());
            DB::commit();
            return $this->sendJson(true, "Register successfully", [
                'token' => $account->createToken(Str::random(10))->plainTextToken,
                "user" => new LoginResponse($account)
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            logMessage("register", $request->all(), $th->getMessage());
            return $this->sendJson(false, ResponseMessages::MESSAGE_500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Auth"},
     *     summary="Login",
     *     operationId="login",
     *      @OA\RequestBody(
     *         description="Login",
     *         required=true,
     *         @OA\JsonContent(
     *               required={"mobile_number"},
     *               @OA\Property(property="mobile_number", type="string", format="mobile_number", example="+921234567890")
     *           ),
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Success"
     *     ),
     * )
     */

    public function login(LoginRequest $request)
    {
        $user = $this->auth->login($request->mobile_number);
        return $this->sendJson(true, "Login successfully.", [
            'token' => $user->createToken(Str::random(10))->plainTextToken,
            'user' => new LoginResponse($user)
        ]);
    }
    /**
     * @OA\GET(
     *     path="/api/locations",
     *     tags={"Auth"},
     *     summary="Locations",
     *     operationId="Locations",
     *     @OA\Response(
     *         response="default",
     *         description="Success"
     *     ),
     * )
     */
    public function locations()
    {
        try {
            $locations = $this->auth->locations();
            $locations = new LocationResourceCollection($locations);
            return $this->sendJson(true, "Success", $locations);
        } catch (\Throwable $th) {
            logMessage("locations", "", $th->getMessage());
            return $this->sendJson(false, ResponseMessages::MESSAGE_500);
        }
    }

    /**
     * @OA\GET(
     *     path="/api/services",
     *     tags={"Auth"},
     *     summary="Services",
     *     operationId="Services",
     *     @OA\Response(
     *         response="default",
     *         description="Success"
     *     ),
     * )
     */
    public function services()
    {
        try {
            $services = $this->auth->services();
            $services = new ServiceResourceCollection($services);
            return $this->sendJson(true, "Success", $services);
        } catch (\Throwable $th) {
            logMessage("services", "", $th->getMessage());
            return $this->sendJson(false, ResponseMessages::MESSAGE_500);
        }
    }

    /**
     * @OA\GET(
     *     path="/api/categories",
     *     tags={"Auth"},
     *     summary="Categories",
     *     operationId="Categories",
     *     @OA\Response(
     *         response="default",
     *         description="Success"
     *     ),
     * )
     */
    public function categories()
    {
        try {
            $categories = $this->auth->categories();
            $categories = new CategoryResourceCollection($categories);
            return $this->sendJson(true, "Success", $categories);
        } catch (\Throwable $th) {
            logMessage("categories", "", $th->getMessage());
            return $this->sendJson(false, ResponseMessages::MESSAGE_500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/upload_image",
     *     tags={"Upload Image"},
     *     summary="Upload Image",
     *     operationId="uploadImage",
     *      @OA\RequestBody(
     *         description="Upload Image",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     description="image to upload",
     *                     property="image",
     *                     type="file",
     *                     format="file",
     *                 ),
     *             ),
     *         ),
     *      ),
     *     @OA\Response(
     *         response="default",
     *         description="Success"
     *     ),
     * )
     */
    public function uploadImage(Request $request)
    {
        try {
            $image = $request->image;
            DB::beginTransaction();
            $image = $this->auth->uploadImage($image);
            DB::commit();
            return $this->sendJson(true, "Image uploaded successfully.", $image);
        } catch (\Throwable $th) {
            DB::rollBack();
            logMessage("upload_file", $request->all(), $th->getMessage());
            return $this->sendJson(false, ResponseMessages::MESSAGE_500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/update/profile",
     *     tags={"Profile"},
     *     summary="Update Profile",
     *     operationId="updateProfile",
     *     security={ {"sanctum": {} }},
     *      @OA\RequestBody(
     *         description="updateProfile",
     *         required=true,
     *         @OA\JsonContent(
     *               required={"name", "location"},
     *               @OA\Property(property="experience", type="integer", format="integer", example="3"),
     *               @OA\Property(property="total_project", type="integer", format="integer", example="10"),
     *               @OA\Property(property="project_done_by_app", type="integer", format="integer", example="7"),
     *               @OA\Property(property="name", type="string", format="string", example="My Name"),
     *               @OA\Property(property="phone_number", type="string", format="string", example="+921234567890 for professional person and company"),
     *               @OA\Property(property="location", type="string", format="string", example="Al Doha"),
     *               @OA\Property(property="services", type="string", format="string", example="1,2 for professional"),
     *               @OA\Property(property="categories", type="string", format="string", example="1,2 for professional"),
     *               @OA\Property(property="image", type="string", format="string", example="image to upload"),
     *               @OA\Property(property="cr_copy", type="string", format="string", example="cr copy to upload for company"),
     *               @OA\Property(property="id_copy", type="string", format="string", example="id copy to upload for professional person")
     *           ),
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Success"
     *     ),
     * )
     */

    public function updateProfile(ProfileRequest $request)
    {
        try {
            DB::beginTransaction();
            $profile = $this->auth->updateProfile($this->user->id, $request->prepareRequest());
            if ($profile) {
                DB::commit();
                $profile = new LoginResponse($profile);
                return $this->sendJson(true, "Profile Updated Successfully!", $profile);
            }
            return $this->sendJson(false, ResponseMessages::MESSAGE_500);
        } catch (\Throwable $th) {
            DB::rollBack();
            logMessage("update/profile", $request->all(), $th->getMessage());
            return $this->sendJson(false, ResponseMessages::MESSAGE_500);
        }
    }

    /**
     * @OA\GET(
     *     path="/api/delete/account",
     *     tags={"Auth"},
     *     summary="Delete Account",
     *     operationId="deleteAccount",
     *     security={ {"sanctum": {} }},
     *     @OA\Response(
     *         response="default",
     *         description="Success"
     *     ),
     * )
     */

    public function deleteAccount()
    {
        try {
            DB::beginTransaction();
            $user = $this->auth->deleteAccount($this->user->id);
            if ($user) {
                DB::commit();
                return $this->sendJson(true, "Account Deleted Successfully!");
            }
            return $this->sendJson(false, ResponseMessages::MESSAGE_500);
        } catch (\Throwable $th) {
            DB::rollBack();
            logMessage("api/delete/account", "", $th->getMessage());
            return $this->sendJson(false, ResponseMessages::MESSAGE_500);
        }
    }
}
