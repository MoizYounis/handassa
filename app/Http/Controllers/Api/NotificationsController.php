<?php

namespace App\Http\Controllers\Api;

use App\Contracts\NotificationContract;
use App\Enum\ResponseMessages;
use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResourceCollection;
use Illuminate\Support\Facades\DB;

class NotificationsController extends Controller
{
    protected $notification;
    protected $_user;
    public function __construct(NotificationContract $notification)
    {
        $this->middleware(function ($request, $next) {
            if (auth()->check()) {
                $this->_user = auth()->user();
            }
            return $next($request);
        });
        $this->notification = $notification;
    }

    /**
     * @OA\GET(
     *     path="/api/notifications",
     *     tags={"Notifications"},
     *     summary="All Notifications",
     *     operationId="allNotifications",
     *     security={ {"sanctum": {} }},
     *     @OA\Response(
     *         response="default",
     *         description="Success"
     *     ),
     * )
     */
    public function index()
    {
        try {
            $notifications = $this->notification->index($this->_user->id);
            $notifications = new NotificationResourceCollection($notifications);
            return $this->sendJson(true, "Success", $notifications);
        } catch (\Throwable $th) {
            logMessage("api/notifications", "", $th->getMessage());
            return $this->sendJson(false, ResponseMessages::MESSAGE_500);
        }
    }

    /**
     * @OA\GET(
     *     path="/api/notification/{id}/delete",
     *     tags={"Notifications"},
     *     summary="Delete Notification",
     *     operationId="deleteNotification",
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

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $notification = $this->notification->delete($id);
            if ($notification) {
                DB::commit();
                return $this->sendJson(true, "Notification Deleted Successfully!");
            }
            return $this->sendJson(false, ResponseMessages::MESSAGE_500);
        } catch (\Throwable $th) {
            DB::rollBack();
            logMessage("api/notification/{$id}/delete", $id, $th->getMessage());
            return $this->sendJson(false, ResponseMessages::MESSAGE_500);
        }
    }

    /**
     * @OA\GET(
     *     path="/api/professional/image/{id}/delete",
     *     tags={"Profile"},
     *     summary="Delete Project Image",
     *     operationId="deleteProjectImage",
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

    public function deleteImage($id)
    {
        try {
            DB::beginTransaction();
            $image = $this->notification->deleteImage($id);
            if ($image) {
                DB::commit();
                return $this->sendJson(true, "Image Deleted Successfully!");
            }
            return $this->sendJson(false, ResponseMessages::MESSAGE_500);
        } catch (\Throwable $th) {
            DB::rollBack();
            logMessage("api/professional/image/{$id}/delete", $id, $th->getMessage());
            return $this->sendJson(false, ResponseMessages::MESSAGE_500);
        }
    }
}
