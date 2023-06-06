<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\ScrapedData; // Importation de la classe ScrapedData

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
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

        $data = [
            'titles' => $titles,
            'paragraphs' => $paragraphs
        ];

        return response()->json($data);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Une erreur est survenue lors de la requête vers le site distant.'], 500);
    }
});

Route::get('/scraped-data', function () {
    $data = ScrapedData::all();
    return response()->json($data);
});
