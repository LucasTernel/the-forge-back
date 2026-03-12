<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Media;
use App\Models\Sword;
use App\Models\Collection;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function store(Request $request)
    {
        if (!auth('sanctum')->check()) {
            return response()->json(['message' => 'Veuillez vous connecter (go to login)'], 401, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
        }

        $request->validate([
            'sword_id' => 'required|exists:swords,id',
        ]);

        $sword      = Sword::findOrFail($request->sword_id);
        $collection = Collection::find($sword->collection_id);
        $user       = auth('sanctum')->user();

        if ($collection->user_id !== $user->id) {
            return response()->json(['message' => 'Cette collection ne vous appartient pas'], 403, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
        }

        $created = [];

        // --- Bulk upload : plusieurs fichiers dans images[] ---
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $filename = time() . '_' . uniqid() . '_' . preg_replace('/[^a-zA-Z0-9.\-_]/', '', $file->getClientOriginalName());
                $path     = $file->storeAs("{$collection->id}/{$sword->id}", $filename, 'public');
                $created[] = Media::create([
                    'sword_id' => $sword->id,
                    'url'      => '/storage/' . $path,
                    'type'     => $request->input('type', 'image'),
                ]);
            }
        }

        // --- Upload unique ou URL string via champ "url" ---
        if ($request->hasFile('url')) {
            $file     = $request->file('url');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9.\-_]/', '', $file->getClientOriginalName());
            $path     = $file->storeAs("{$collection->id}/{$sword->id}", $filename, 'public');
            $data     = $request->except('url');
            $data['url'] = '/storage/' . $path;
            $created[] = Media::create($data);
        } elseif ($request->filled('url')) {
            $data        = $request->except('url');
            $data['url'] = $request->input('url');
            $created[]   = Media::create($data);
        }

        if (empty($created)) {
            return response()->json(['message' => 'Aucun média fourni. Envoyez "url" (fichier ou URL) ou un tableau "images[]".'], 422, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
        }

        $result = count($created) === 1 ? $created[0] : $created;
        return response()->json($result, 201);
    }

    public function destroy($id)
    {
        $media = Media::find($id);

        if (!$media) {
            return response()->json(['message' => 'Média introuvable.'], 404);
        }

        $sword      = Sword::find($media->sword_id);
        $collection = $sword ? Collection::find($sword->collection_id) : null;
        $user       = auth('sanctum')->user();

        if (!$collection || $collection->user_id !== $user->id) {
            return response()->json(['message' => 'Action non autorisée.'], 403);
        }

        // Supprimer le fichier physique si stocké en local
        if ($media->getRawOriginal('url') && str_starts_with($media->getRawOriginal('url'), '/storage/')) {
            $relativePath = str_replace('/storage/', '', $media->getRawOriginal('url'));
            Storage::disk('public')->delete($relativePath);
        }

        $media->delete();
        return response()->json(['message' => 'Média supprimé.'], 200);
    }
}