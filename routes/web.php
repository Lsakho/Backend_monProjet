<?php

use Illuminate\Support\Facades\Route;
use App\Models\ScrapedData;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/scrape', function (Request $request) {
    $url = $request->query('url');

    try {
        $response = Http::get($url);
        $html = $response->body();

        $crawler = new Crawler($html);
        $titles = $crawler->filter('h1, h2, h3')->each(function ($node) {
            return $node->text();
        });

        $paragraphs = $crawler->filter('p')->each(function ($node) {
            return $node->text();
        });

        // Enregistrement des données dans la base de données
        foreach ($titles as $index => $title) {
            $scrapedData = new ScrapedData();
            $scrapedData->title = $title;
            $scrapedData->paragraph = $paragraphs[$index];
            $scrapedData->save();
        }

        return response()->json(['message' => 'Les données ont été enregistrées avec succès.']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Une erreur est survenue lors de la requête vers le site distant.'], 500);
    }
});

Route::get('/scraped-data', function () {
    $data = ScrapedData::all();
    return response()->json($data);
});

