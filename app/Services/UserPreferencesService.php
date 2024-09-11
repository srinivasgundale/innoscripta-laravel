<?php
namespace App\Services;

use App\Models\User;
use App\Models\UserPreference;

class UserPreferencesService
{
    public function getUserPreferences(User $user)
    {
        return $user->preferences;
    }

    public function updateUserPreferences(User $user, array $preferences)
    {
        // Clear existing preferences
        $user->preferences()->delete();

        // Save new preferences
        foreach ($preferences as $preference) {
            $user->preferences()->create($preference);
        }
    }
}
