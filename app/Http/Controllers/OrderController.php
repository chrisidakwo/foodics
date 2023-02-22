<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderProductRequest;
use App\Http\Services\OrderService;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    /**
     * @param OrderService $orderService
     */
    public function __construct(private OrderService $orderService)
    {
    }

    /**
     * @param OrderProductRequest $request
     *
     * @return JsonResponse
     */
    public function order(OrderProductRequest $request): JsonResponse
    {
        $data = $request->validated();

        $order = $this->orderService->order($data['products']);

        return response()->json($order->toArray());
    }
}
