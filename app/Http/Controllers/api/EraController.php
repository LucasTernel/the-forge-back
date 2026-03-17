<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Era;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EraController extends Controller
{
    public function index()
    {
        $eras = Era::all();
        return response()->json($eras, 200, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
    }

    public function show($id)
    {
        $era = Era::find($id);

        if (!$era) {
            return response()->json(['message' => 'era not found'], 404, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
        }

        return response()->json($era, 200, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'image_cover' => 'nullable',
            'overlay'     => 'nullable|string',
            'color'       => 'nullable|string',
        ]);

        $data = $request->only(['name', 'overlay', 'color']);

        $data['image_cover'] = $this->handleImageCover($request, Str::slug($request->input('name')));

        $era = Era::create($data);

        return response()->json($era, 201, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
    }

    public function update(Request $request, $id)
    {
        $era = Era::find($id);

        if (!$era) {
            return response()->json(['message' => 'era not found'], 404, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
        }

        $data = $request->only(['name', 'overlay', 'color']);

        // Recalculate slug (use updated name if provided, otherwise existing name)
        $slug = Str::slug($request->input('name', $era->name));

        $imageCover = $this->handleImageCover($request, $slug);
        if ($imageCover !== null) {
            $data['image_cover'] = $imageCover;
        }

        $era->update($data);

        return response()->json($era, 200, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Handles image_cover: file upload → /storage/eras/image_cover/{era_slug}/
     *                       URL string  → stored as-is
     *                       not provided → returns null
     */
    private function handleImageCover(Request $request, string $slug): ?string
    {
        if ($request->hasFile('image_cover')) {
            $file     = $request->file('image_cover');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9.\-_]/', '', $file->getClientOriginalName());
            $path     = $file->storeAs("eras/image_cover/{$slug}", $filename, 'public');
            return '/storage/' . $path;
        }

        if ($request->filled('image_cover')) {
            return $request->input('image_cover');
        }

        return null;
    }

    public function destroy($id)
    {
        $era = Era::find($id);

        if (!$era) {
            return response()->json(['message' => 'Era not found'], 404);
        }

        // Supprime l'image_cover physique si c'est un fichier local
        if ($era->getRawOriginal('image_cover') && str_starts_with($era->getRawOriginal('image_cover'), '/storage/')) {
            $relativePath = str_replace('/storage/', '', $era->getRawOriginal('image_cover'));
            Storage::disk('public')->delete($relativePath);
        }

        $era->delete();

        return response()->json(['message' => 'Era supprimée avec succès.'], 200);
    }
}
