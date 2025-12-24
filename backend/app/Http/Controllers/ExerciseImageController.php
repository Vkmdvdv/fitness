<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class ExerciseImageController extends Controller
{
    /**
     * Обновить изображение упражнения
     */
    public function update(Request $request, Exercise $exercise): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($exercise->image_url) {
            Storage::disk('public')->delete($exercise->image_url);
        }

        $path = $request->file('image')->store('exercises', 'public');

        $exercise->update([
            'image_url' => Config::get('app.url') . Storage::url($path)
        ]);

        return response()->json([
            'message' => 'Image updated successfully',
            'data' => [
                'image_url' => Config::get('app.url') . Storage::url($path)
            ]
        ]);
    }

    /**
     * Удалить изображение упражнения
     */
    public function destroy(Exercise $exercise): JsonResponse
    {
        if ($exercise->image_url) {
            Storage::disk('public')->delete($exercise->image_url);
            $exercise->update(['image_url' => null]);
        }

        return response()->json([
            'message' => 'Image deleted successfully'
        ]);
    }
}