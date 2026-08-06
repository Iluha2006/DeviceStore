<?php
namespace App\Http\Controllers;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{

    public function show($id)
{

    $profile = Profile::where('user_id', $id)->first();
    if (!$profile) {
        return response()->json(['message' => 'Profile not found'], 404);
    }

    return response()->json(
    [
        'id' => $profile->id,
        'user_id' => $profile->user_id,
        'name' => $profile->name,
        'email' => $profile->email,
        'created_at' => $profile->created_at


    ] ,
   );
}
public function update($id)
{
    $profile = Profile::find($id);
    if (!$profile) {
        return response()->json(['message' => 'Profile not found'], 404);
    }

    $validated = request()->validate(
[
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:profiles,email,'.$profile->id,

    ],
    [
        'name.required' => 'Поле имени обязательно для заполнения',
        'email.required' => 'Поле email обязательно для заполнения',
        'email.email' => 'Введите корректный email адрес',
        'email.unique' => 'Этот email уже используется'
    ]);
    $profile->update($validated);
    return response()->json($profile);
}
public function destroy($id)
{

    $profile = Profile::find($id);
    if (!$profile) {
        return response()->json(['message' => 'Профиль не найден'], 404);
    }
    $profile->delete();
    return response()->json([
        'message' => 'Профиль успешно удален'
    ], 200);
}

}