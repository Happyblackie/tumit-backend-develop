<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;

class LocalizationController extends Controller
{
    /**
     * Get the translation based on language.
     *
     * @param  string  $lang
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTranslations(Request $request, $lang)
    {
        // Check if the requested language is supported
        if (!in_array($lang, ['en', 'fr', 'sw'])) {
            $data = [
                "status" => 400,
                "message" => 'Language not supported'
            ];
            return response()->json($data, 400);

            // return response()->json(['message' => 'Language not supported'], 400);
        }

        // Set the locale for translation
        App::setLocale($lang);

        // Define the path to the language files (JSON format)
        $langFilePath = resource_path("lang/{$lang}.json");

        // Check if the language file exists
        if (!File::exists($langFilePath)) {
            $data = [
                "status" => 404,
                "message" => 'Language file not found'
            ];
            return response()->json($data, 404);

            // return response()->json(['message' => 'Language file not found'], 404);
        }

        // Read the content of the JSON file
        $translations = json_decode(File::get($langFilePath), true);

        return response()->json($translations);
    }
}
