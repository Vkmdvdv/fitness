<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;

class LocaleController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'current_locale' => App::getLocale(),
            'available_locales' => config('app.available_locales')
        ]);
    }

    public function setLocale(string $locale): JsonResponse
    {
        if (in_array($locale, config('app.available_locales'))) {
            App::setLocale($locale);
            return response()->json([
                'message' => __('messages.success'),
                'locale' => $locale
            ]);
        }

        return response()->json([
            'message' => __('messages.error'),
            'error' => 'Unsupported locale'
        ], 400);
    }
} 