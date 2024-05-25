<?php

namespace App\Services\Panel\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Order\Enums\OrderInternalStatusEnum;
use App\Services\Order\Models\Order;
use App\Services\Order\Resources\Admin\OrderResource;
use App\Services\Order\Resources\Admin\OrdersCollection;
use App\Services\Panel\Controllers\Concerns\HasBeforeFinalizing;
use App\Services\Panel\Models\User;
use App\Services\Panel\Repositories\AdminServiceInterface;
use App\Services\Panel\Requests\UserCreationRequest;
use App\Services\Panel\Requests\UserUpdateRequest;
use App\Services\Panel\Resources\UserCollection;
use App\Services\Panel\Resources\UserResource;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    use HasBeforeFinalizing;

    public function __construct(private AdminServiceInterface $adminService)
    {
    }

    public function getOrders(Request $request)
    {
        $orders = $this->adminService->getOrders($request->all());

        return response()->json([
            'success' => true,
            'message' => '',
            'data' => new OrdersCollection($orders),
            'meta' => [
                'total' => $orders->total(),
                'per_page' => $orders->perPage(),
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'from' => $orders->firstItem(),
                'to' => $orders->lastItem(),
                'prev_page_url' => $orders->previousPageUrl(),
                'next_page_url' => $orders->nextPageUrl(),
                'path' => $orders->path(),
            ]
        ]);
    }

    public function makeOrderNfc(Order $order)
    {
        try {
            $result = $this->adminService->makeOrderNfc($order);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
                'data' => [],
                'meta' => []
            ], 422);
        }

        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'Order got nfc status',
                'data' => [],
                'meta' => []
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Making order as NFC failed',
            'data' => [],
            'meta' => []
        ], 422);
    }

    public function revokeStoreNfc(Order $order)
    {
        // TODO : We need state pattern for order statuses
//        $order->update([
//            'internal_status' => OrderInternalStatusEnum::None->value
//        ]);

        return response()->json([
            'success' => true,
            'message' => 'Nfc revoked',
            'data' => [],
            'meta' => []
        ]);
    }

    public function getOrder(Order $order)
    {
        return response()->json([
            'success' => true,
            'message' => '',
            'data' => new OrderResource($order),
            'meta' => []
        ]);
    }

    // TODO : Make response for users
    public function index(Request $request)
    {
        $users = $this->adminService->getUsers($request->all());

        return response()->json([
            'success' => true,
            'message' => '',
            'data' => new UserCollection($users),
            'meta' => [
                'total' => $users->total(),
                'per_page' => $users->perPage(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'from' => $users->firstItem(),
                'to' => $users->lastItem(),
                'prev_page_url' => $users->previousPageUrl(),
                'next_page_url' => $users->nextPageUrl(),
                'path' => $users->path(),
            ]
        ]);
    }

    public function createUser(UserCreationRequest $request)
    {
        $user = $this->adminService->createUser($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => new UserResource($user),
            'meta' => []
        ]);
    }

    public function updateUser(UserUpdateRequest $request)
    {
        $user = $this->adminService->updateUser($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => new UserResource($user->refresh()),
            'meta' => []
        ]);
    }

    public function deleteUser(User $user)
    {
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully',
            'data' => [],
            'meta' => []
        ]);
    }

    public function createInvoice(Order $order)
    {
        try {
            $this->beforeFinalizingOrder($order);

            $invoice_number = $order->createInvoice();
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
                'data' => [],
                'meta' => []
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Order has invoiced successfully',
            'data' => [
                'invoice_number' => $invoice_number
            ],
            'meta' => []
        ]);
    }

    public function getUser(User $user)
    {
        return response()->json([
            'success' => true,
            'message' => '',
            'data' => new UserResource($user),
            'meta' => []
        ]);
    }
}
