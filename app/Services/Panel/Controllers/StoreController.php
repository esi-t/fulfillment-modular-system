<?php

namespace App\Services\Panel\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Contractors\Base\Models\CashAddress;
use App\Services\Order\Models\Order;
use App\Services\Order\Resources\Store\OrderHistoryCollection;
use App\Services\Order\Resources\Store\OrderResource;
use App\Services\Order\Resources\Store\OrdersCollection;
use App\Services\Panel\Controllers\Concerns\HasBeforeFinalizing;
use App\Services\Panel\Repositories\StoreServiceInterface;
use App\Services\Panel\Requests\ProductActivation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use PDF;

class StoreController extends Controller
{
    use HasBeforeFinalizing;

    public function __construct(private StoreServiceInterface $storeService)
    {
    }

    public function getOrders(int $storeId, Request $request)
    {
        $orders = $this->storeService->getOrders(auth()->user()->store_id, $request->all()); // TODO : Temp fix this

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

    public function makeOrderOpened(Order $order)
    {
        $this->storeService->openTheOrder($order);

        return response()->json([
            'success' => true,
            'message' => 'Order opened',
            'data' => [],
            'meta' => []
        ]);
    }

    public function makeOrderNfc(Order $order, Request $request)
    {
        $validatedData = $request->validate([
            'description' => ['required', 'string'],
        ]);

        try {
            $this->storeService->makeOrderNfc($order, $validatedData);
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
            'message' => 'Order got nfc status',
            'data' => [],
            'meta' => []
        ]);
    }

    public function makeOrderCollected(Order $order)
    {
        try {
            $this->beforeFinalizingOrder($order);

            $invoice_number = $order->collected();
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
            'message' => 'Order collected successfully',
            'data' => [
                'invoice_number' => $invoice_number
            ],
            'meta' => []
        ]);
    }

    public function getOrdersHistory(Request $request)
    {
        $validatedData = $request->validate([
            'store_id' => ['required', 'integer']
        ]);

        $ordersHistory = $this->storeService->getOrdersHistory(
            array_merge($validatedData, $request->all())
        );

        return response()->json([
            'success' => true,
            'message' => '',
            'data' => new OrderHistoryCollection($ordersHistory),
            'meta' => [
                'total' => $ordersHistory->total(),
                'per_page' => $ordersHistory->perPage(),
                'current_page' => $ordersHistory->currentPage(),
                'last_page' => $ordersHistory->lastPage(),
                'from' => $ordersHistory->firstItem(),
                'to' => $ordersHistory->lastItem(),
                'prev_page_url' => $ordersHistory->previousPageUrl(),
                'next_page_url' => $ordersHistory->nextPageUrl(),
                'path' => $ordersHistory->path(),
            ]
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
            'message' => 'Order invoiced successfully',
            'data' => [
                'invoice_number' => $invoice_number
            ],
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

    public function downloadInvoice(Order $order)
    {
        $data = $this->storeService->getDataForPrint($order);

        $loadPdf = PDF::chunkLoadView('<html-separator/>', 'invoice', $data);

        return $loadPdf->stream();
    }

    public function updateProductActivation(ProductActivation $request)
    {
        $validatedData = $request->validated();

        $result = $this->storeService->updateProductsActivation($validatedData['products']);

        return response()->json([
            'success' => true,
            'message' => '',
            'data' => $result,
            'meta' => []
        ]);
    }

    public function deactivateNotification()
    {
        $storeId = Auth::user()->store_id;

        try {
            Http::get(CashAddress::stopNotifying($storeId));
        } catch (\Exception $exception) {

        }

        return response()->json([
            'success' => true,
            'message' => '',
            'data' => [],
            'meta' => []
        ]);
    }
}
