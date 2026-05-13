<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BillingDetail;
use App\Models\Discount;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        // Verificar que el usuario está autenticado
        if (! Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'Debes estar autenticado para hacer una compra.'], 401);
        }

        $user = Auth::user();
        $cart = $request->input('cart', []);

        if (empty($cart)) {
            return response()->json(['status' => 'error', 'message' => 'El carrito está vacío.']);
        }

        $validated = $request->validate([
            'billing_enabled' => ['nullable', 'boolean'],
            'rfc' => ['nullable', 'string', 'max:20'],
            'business_name' => ['nullable', 'string', 'max:255'],
            'tax_regime' => ['nullable', 'string', 'max:120'],
            'billing_postal_code' => ['nullable', 'string', 'max:20'],
            'fiscal_address' => ['nullable', 'string', 'max:255'],
            'cfdi_usage' => ['nullable', 'string', 'max:20'],
            'discount_code' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $needsBilling = $request->boolean('billing_enabled');

        if ($needsBilling) {
            $request->validate([
                'rfc' => ['required', 'string', 'max:20'],
                'business_name' => ['required', 'string', 'max:255'],
                'tax_regime' => ['required', 'string', 'max:120'],
                'billing_postal_code' => ['required', 'string', 'max:20'],
                'fiscal_address' => ['required', 'string', 'max:255'],
                'cfdi_usage' => ['required', 'string', 'max:20'],
            ]);
        }

        try {
            DB::beginTransaction();

            $subtotal = 0;
            $orderItems = [];
            foreach ($cart as $item) {
                $product = Product::with('wholesalePrices')->findOrFail($item['id']);
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stock insuficiente para: " . $product->name);
                }

                $linePrice = $this->resolveLinePrice($product, (int) $item['quantity']);
                $lineTotal = $linePrice * $item['quantity'];
                $subtotal += $lineTotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $linePrice,
                ];

                $product->stock -= $item['quantity'];
                $product->save();
            }

            $discount = null;
            $discountAmount = 0;
            if (! empty($validated['discount_code'])) {
                $discount = Discount::where('code', '=', $validated['discount_code'])
                    ->where('is_active', true)
                    ->first();

                if ($discount) {
                    if ($discount->type === 'percentage') {
                        $discountAmount = ($subtotal * $discount->value) / 100;
                    } else {
                        $discountAmount = $discount->value;
                    }
                    $discountAmount = min($discountAmount, $subtotal);
                }
            }

            $total = max($subtotal - $discountAmount, 0);

            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'user_id' => $user->id,
                'customer_name' => $user->name,
                'customer_phone' => $user->phone,
                'customer_email' => $user->email,
                'customer_address' => $user->address,
                'customer_city' => $user->city,
                'customer_state' => $user->state,
                'customer_postal_code' => $user->postal_code,
                'subtotal' => $subtotal,
                'discount_id' => $discount?->id,
                'discount_amount' => $discountAmount,
                'total' => $total,
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($orderItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }

            if ($needsBilling) {
                BillingDetail::create([
                    'order_id' => $order->id,
                    'rfc' => $validated['rfc'],
                    'business_name' => $validated['business_name'],
                    'tax_regime' => $validated['tax_regime'],
                    'postal_code' => $validated['billing_postal_code'],
                    'fiscal_address' => $validated['fiscal_address'],
                    'cfdi_usage' => $validated['cfdi_usage'],
                    'invoice_status' => 'pending',
                ]);
            }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Pedido generado', 'order_number' => $order->order_number, 'order_id' => $order->id, 'total' => number_format($order->total, 2, '.', '')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    private function resolveLinePrice(Product $product, int $quantity): float
    {
        $wholesalePrice = $product->wholesalePrices
            ->filter(function ($price) use ($quantity) {
                $maxQuantity = $price->max_quantity;

                return $price->min_quantity <= $quantity && ($maxQuantity === null || $quantity <= $maxQuantity);
            })
            ->sortByDesc('min_quantity')
            ->first();

        return $wholesalePrice ? (float) $wholesalePrice->price : (float) $product->price;
    }

    private function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'ORD-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
        } while (Order::query()->where('order_number', '=', $orderNumber)->exists());

        return $orderNumber;
    }
}
