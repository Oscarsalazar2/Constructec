<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function checkout(Request $request) {
        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'Debes iniciar sesión para comprar.']);
        }

        $cart = $request->input('cart', []);
        if (empty($cart)) {
            return response()->json(['status' => 'error', 'message' => 'El carrito está vacío.']);
        }

        try {
            DB::beginTransaction();
            $total = 0;
            foreach ($cart as $item) {
                $total += $item['price'] * $item['quantity'];
                
                $product = Product::findOrFail($item['id']);
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stock insuficiente para: " . $product->name);
                }
                $product->stock -= $item['quantity'];
                $product->save();
            }

            $order = Order::create([
                'user_id' => Auth::id(),
                'total' => $total,
                'status' => 'pending'
            ]);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Compra realizada', 'order_id' => $order->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
