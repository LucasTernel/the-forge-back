<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

class FollowController extends Controller
{
    public function getFollowers($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Utilisateur introuvable.'], 404, ['Content-Type' => 'application/json; charset=UTF-8']);
        }

        $followers = $user->followers()->get();

        return response()->json($followers, 200, ['Content-Type' => 'application/json; charset=UTF-8']);
    }

    public function getFollowing($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Utilisateur introuvable.'], 404, ['Content-Type' => 'application/json; charset=UTF-8']);
        }

        $following = $user->following()->get();

        return response()->json($following, 200, ['Content-Type' => 'application/json; charset=UTF-8']);
    }

    public function toggleFollow(Request $request, $id)
    {
        $userToFollow = User::find($id);

        if (!$userToFollow) {
            return response()->json(['message' => 'Cette épéiste est introuvable.'], 404, ['Content-Type' => 'application/json; charset=UTF-8']);
        }

        $currentUser = $request->user();

        if ($currentUser->id == $id) {
            return response()->json(['message' => 'Vous ne pouvez pas vous suivre vous-même.'], 400, ['Content-Type' => 'application/json; charset=UTF-8']);
        }

        $toggle = $currentUser->following()->toggle($id);

        if (count($toggle['attached']) > 0) {
            return response()->json(['message' => 'Vous suivez maintenant cet épéiste.'], 200, ['Content-Type' => 'application/json; charset=UTF-8']);
        } else {
            return response()->json(['message' => 'Vous ne suivez plus cet épéiste.'], 200, ['Content-Type' => 'application/json; charset=UTF-8']);
        }
    }
}