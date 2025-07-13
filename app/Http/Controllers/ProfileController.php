<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Validation\Rule;


use App\Models\Order;
use App\Models\Product;
use App\Models\Favorite;
use App\Models\User;



class ProfileController extends Controller
{
    /**
     * Display the public profile page.
     */
    public function show()
{
    /** @var \App\Models\User $user */
    $user = Auth::user();

    if ($user->role === 'customer') {
        // FIX: tambahin relasi product biar bisa diakses di blade
        $orders = $user->orders()->with('product')->latest()->paginate(5);
        $favorites = $user->favorites()->with('product')->get();

        return view('profile.index', compact('user', 'orders', 'favorites'));
    }

    if ($user->role === 'seller') {
    $products = $user->products()->latest()->paginate(9);

    // Hitung total estimasi transaksi dari semua pembelian produk dia
    $salesCount = \App\Models\Order::whereIn('product_id', $products->pluck('id'))->count();

    $salesTotal = \App\Models\Order::whereIn('product_id', $products->pluck('id'))
        ->with('product')
        ->get()
        ->sum(function ($order) {
            return $order->quantity * $order->product->price;
        });

    return view('profile.index', compact('user', 'products', 'salesCount', 'salesTotal'));
}

    return redirect()->route('dashboard')->with('error', 'Role tidak dikenali');
}

    /**
     * Display the user's profile edit form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
{
    $user = $request->user();

    // Kalau hanya ada role, berarti switch toggle
    if ($request->has('role') && !$request->has('email')) {
        $request->validate([
            'role' => ['required', 'in:customer,seller'],
        ]);

        $user->role = $request->input('role');
        $user->save();

        return Redirect::route('profile')->with('status', 'role-updated');
    }

    // Kalau full update dari form edit profile
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => [
            'required',
            'string',
            'lowercase',
            'email',
            'max:255',
            Rule::unique(User::class)->ignore($user->id),
        ],
        'photo' => ['nullable', 'image', 'max:10240'],
    ]);

    if ($request->input('email') !== $user->email) {
        $user->email_verified_at = null;
    }

    $user->fill($request->only('name', 'email'));

    // 🖼️ Proses upload foto
    if ($request->hasFile('photo')) {
        $path = $request->file('photo')->store('profile-photos', 'public');
        $user->profile_photo_path = $path;
    }

    $user->save();

    return Redirect::route('profile.edit')->with('status', 'profile-updated');
}



    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
