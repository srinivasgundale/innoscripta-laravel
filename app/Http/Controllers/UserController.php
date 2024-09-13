<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;


class UserController extends Controller
{


    public function __construct()
    {

    }

    public function update(Request $request)
    {
        $user = auth()->user();

        // Use mass assignment to update user data
        $user->update([
            'name' => $request->name,
            'main_source' => $request->mainSource ?? $user->main_source,
            'sub_source' => $request->source ?? $user->sub_source,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully.',
            'user' => $user,
        ]);
    }

}
