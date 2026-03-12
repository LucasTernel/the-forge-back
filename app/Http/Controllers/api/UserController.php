<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * GET /api/users
     * Retourne la liste de tous les utilisateurs.
     * Filtrage optionnel par nom via le query param ?search=
     *
     * Exemples :
     *   GET /api/users              → tous les utilisateurs
     *   GET /api/users?search=john  → utilisateurs dont le nom contient "john"
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filtre par nom (insensible à la casse)
        if ($request->has('search') && $request->search !== '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $users = $query
            ->select('id', 'name', 'email', 'role', 'created_at')
            ->orderBy('name')
            ->get();

        return response()->json($users);
    }
}
