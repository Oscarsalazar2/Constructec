<?php

namespace App\Http\Controllers;

use App\Models\BillingDetail;
use App\Models\Discount;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductWholesalePrice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        $this->ensureAdmin();

        $totalProducts = Product::count('*');
        $totalOrders = Order::count('*');
        $totalRevenue = Order::sum('total') ?? 0;
        $totalUsers = User::count('*');
        $totalDiscounts = Discount::count('*');
        $recentProducts = Product::with('wholesalePrices')->orderBy('id', 'desc')->take(5)->get();
        $recentOrders = Order::with(['user', 'discount', 'billingDetail'])->orderBy('id', 'desc')->take(8)->get();
        $users = User::orderBy('id', 'desc')->get();
        $discounts = Discount::orderBy('id', 'desc')->get();
        $products = Product::with('wholesalePrices')->orderBy('id', 'desc')->get();
        $billingDetails = BillingDetail::with('order')->latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalOrders',
            'totalRevenue',
            'totalUsers',
            'totalDiscounts',
            'recentProducts',
            'recentOrders',
            'users',
            'discounts',
            'products',
            'billingDetails'
        ));
    }

    public function storeProduct(Request $request)
    {
        $this->ensureAdmin();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'image_url' => ['nullable', 'string', 'max:255'],
            'stock' => ['required', 'integer', 'min:0'],
        ]);

        Product::create($data);

        return redirect()->route('admin.dashboard')->with('status', 'Producto creado.');
    }

    public function updateProduct(Request $request, Product $product)
    {
        $this->ensureAdmin();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'image_url' => ['nullable', 'string', 'max:255'],
            'stock' => ['required', 'integer', 'min:0'],
        ]);

        $product->update($data);

        return redirect()->route('admin.dashboard')->with('status', 'Producto actualizado.');
    }

    public function destroyProduct(Product $product)
    {
        $this->ensureAdmin();

        Product::destroy($product->id);

        return redirect()->route('admin.dashboard')->with('status', 'Producto eliminado.');
    }

    public function storeDiscount(Request $request)
    {
        $this->ensureAdmin();

        $data = $request->validate([
            'code' => ['required', 'string', 'max:255', 'unique:discounts,code'],
            'type' => ['required', 'in:percentage,fixed'],
            'value' => ['required', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        Discount::create($data);

        return redirect()->route('admin.dashboard')->with('status', 'Descuento creado.');
    }

    public function updateDiscount(Request $request, Discount $discount)
    {
        $this->ensureAdmin();

        $data = $request->validate([
            'code' => ['required', 'string', 'max:255', 'unique:discounts,code,' . $discount->id],
            'type' => ['required', 'in:percentage,fixed'],
            'value' => ['required', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $discount->update($data);

        return redirect()->route('admin.dashboard')->with('status', 'Descuento actualizado.');
    }

    public function destroyDiscount(Discount $discount)
    {
        $this->ensureAdmin();

        Discount::destroy($discount->id);

        return redirect()->route('admin.dashboard')->with('status', 'Descuento eliminado.');
    }

    public function updateUser(Request $request, User $user)
    {
        $this->ensureAdmin();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:user,admin'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.dashboard')->with('status', 'Usuario actualizado.');
    }

    public function destroyUser(User $user)
    {
        $this->ensureAdmin();

        if (Auth::id() === $user->id) {
            return redirect()->route('admin.dashboard')->with('status', 'No puedes eliminar tu propio usuario.');
        }

        User::destroy($user->id);

        return redirect()->route('admin.dashboard')->with('status', 'Usuario eliminado.');
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $this->ensureAdmin();

        $data = $request->validate([
            'status' => ['required', 'in:pending,processing,ready,completed,cancelled'],
        ]);

        $order->update($data);

        return redirect()->route('admin.dashboard')->with('status', 'Estatus de la orden actualizado.');
    }

    public function storeWholesalePrice(Request $request, Product $product)
    {
        $this->ensureAdmin();

        $data = $request->validate([
            'min_quantity' => ['required', 'integer', 'min:1'],
            'max_quantity' => ['nullable', 'integer', 'min:1', 'gte:min_quantity'],
            'price' => ['required', 'numeric', 'min:0'],
        ]);

        $product->wholesalePrices()->create($data);

        return redirect()->route('admin.dashboard')->with('status', 'Precio de mayoreo agregado.');
    }

    public function destroyWholesalePrice(ProductWholesalePrice $wholesalePrice)
    {
        $this->ensureAdmin();

        ProductWholesalePrice::destroy($wholesalePrice->id);

        return redirect()->route('admin.dashboard')->with('status', 'Precio de mayoreo eliminado.');
    }

    private function ensureAdmin(): void
    {
        if (! Auth::check() || Auth::user()->role !== 'admin') {
            abort(403);
        }
    }
}
