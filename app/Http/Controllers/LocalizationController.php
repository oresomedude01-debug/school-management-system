<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\App;

class LocalizationController extends Controller
{
    /**
     * Get translations for a specific locale
     */
    public function getTranslations($locale)
    {
        // Validate locale
        $availableLocales = ['en', 'ar'];
        if (!in_array($locale, $availableLocales)) {
            return response()->json(['error' => 'Invalid locale'], 400);
        }

        // Get translation file path
        $path = lang_path("{$locale}/app.php");

        if (!File::exists($path)) {
            return response()->json(['error' => 'Translations not found'], 404);
        }

        // Load translations
        $translations = include $path;

        return response()->json($translations);
    }

    /**
     * Set user's locale preference
     */
    public function setLocale(Request $request)
    {
        $locale = $request->input('locale', 'en');

        // Validate locale
        $availableLocales = ['en', 'ar'];
        if (!in_array($locale, $availableLocales)) {
            return response()->json(['error' => 'Invalid locale'], 400);
        }

        // Set application locale
        App::setLocale($locale);

        // Store in session if user is authenticated
        if (auth()->check()) {
            session(['locale' => $locale]);
        }

        return response()->json([
            'success' => true,
            'locale' => $locale,
            'message' => 'Locale updated successfully'
        ]);
    }

    /**
     * Get current locale
     */
    public function getLocale()
    {
        $locale = session('locale', config('app.locale', 'en'));

        return response()->json([
            'locale' => $locale,
            'available_locales' => [
                ['code' => 'en', 'name' => 'English', 'native_name' => 'English'],
                ['code' => 'ar', 'name' => 'Arabic', 'native_name' => 'العربية']
            ]
        ]);
    }
}
