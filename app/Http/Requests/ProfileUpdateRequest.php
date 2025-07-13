<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function update(Request $request): RedirectResponse
{
    $user = $request->user();

    $user->name = $request->input('name', $user->name);
    $user->email = $request->input('email', $user->email);
    $user->role = $request->input('role', $user->role);

    if ($request->input('email') !== $user->email) {
        $user->email_verified_at = null;
    }

    $user->save();

    return Redirect::route('profile.edit')->with('status', 'profile-updated');
}



}
