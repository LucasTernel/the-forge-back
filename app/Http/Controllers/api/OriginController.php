<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Origin;

class OriginController extends Controller
{
    public function index()
    {
        $origins = Origin::with('swords')->get();
        return response()->json($origins, 200, ['Content-Type' => 'application/json; charset=UTF-8']);
    }

    public function show($id)
    {
        $origin = Origin::with('swords')->find($id);

        if (!$origin) {
            return response()->json(['message' => "L'origine de ton épée est introuvable..."], 404, ['Content-Type' => 'application/json; charset=UTF-8']);
        }

        return response()->json($origin, 200, ['Content-Type' => 'application/json; charset=UTF-8']);
    }

    public function update(Request $request, $id)
    {
        $origin = Origin::find($id);

        if (!$origin) {
            return response()->json(['message' => "Origine introuvable."], 404, ['Content-Type' => 'application/json; charset=UTF-8']);
        }

        $data = $request->only(['name']);

        // image_cover : fichier uploadé OU URL string
        if ($request->hasFile('image_cover')) {
            $file     = $request->file('image_cover');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9.\-_]/', '', $file->getClientOriginalName());
            $path     = $file->storeAs("origins/{$id}", $filename, 'public');
            $data['image_cover'] = '/storage/' . $path;
        } elseif ($request->filled('image_cover')) {
            $data['image_cover'] = $request->input('image_cover');
        }

        $origin->update($data);

        return response()->json($origin, 200, ['Content-Type' => 'application/json; charset=UTF-8']);
    }

    public function destroy($id)
    {
        $origin = Origin::find($id);

        if (!$origin) {
            return response()->json(['message' => 'Origine introuvable.'], 404);
        }

        // Supprime l'image_cover physique si c'est un fichier local
        if ($origin->image_cover && str_starts_with($origin->image_cover, '/storage/')) {
            $relativePath = str_replace('/storage/', '', $origin->image_cover);
            Storage::disk('public')->delete($relativePath);
        }

        $origin->delete();

        return response()->json(['message' => 'Origine supprimée avec succès.'], 200);
    }
}
