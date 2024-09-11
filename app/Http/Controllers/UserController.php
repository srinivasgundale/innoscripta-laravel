<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserPreferencesService;

class UserController extends Controller
{
    protected $preferencesService;

    public function __construct(UserPreferencesService $preferencesService)
    {
        $this->preferencesService = $preferencesService;
    }

    public function updatePreferences(Request $request)
    {
        $user = auth()->user();
        $this->preferencesService->updateUserPreferences($user, $request->input('preferences'));

        return response()->json(['message' => 'Preferences updated successfully.']);
    }
}
