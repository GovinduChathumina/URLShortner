<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShortUrl;
use Illuminate\Support\Str;


class UrlShortenerController extends Controller
{
    public function encode(Request $request){
        $request->validate([
            'url' => 'required|url'
        ]);

        //Check if the url already exists
        $existing = ShortUrl::where('original_url', $request->url)->first();
        if ($existing) {
            return response()->json([
                'short_url' => url($existing->short_code)
            ]);
        }

        //Genarate unique short code
        do {
            $code = Str::random(6);
        } while (ShortUrl::where('short_code', $code)->exists());

        $shortUrl = ShortUrl::create([
            'original_url' => $request->url,
            'short_code' => $code
        ]);

        return response()->json([
            'short_url' => url($shortUrl->short_code)
        ]);
    }

    public function decode(Request $request){
        $request->validate([
            'short_url' => 'required|url'
        ]);

        $code = basename($request->short_url);

        $shortUrl = ShortUrl::where('short_code', $code)->first();

        if (!$shortUrl) {
            return response()->json([
                'error' => 'URL not found'
            ], 404);
        }

        return response()->json([
            'original_url' => $shortUrl->original_url
        ]);
    }

    public function showForm()
    {
        return view('shorten.form');
    }

    public function handleForm(Request $request)
    {
        $request->validate([
            'url' => 'required|url'
        ]);

        $existing = ShortUrl::where('original_url', $request->url)->first();

        if ($existing) {
            $shortUrl = url($existing->short_code);
        } else {
            do {
                $code = Str::random(6);
            } while (ShortUrl::where('short_code', $code)->exists());

            $shortUrl = url(ShortUrl::create([
                'original_url' => $request->url,
                'short_code' => $code
            ])->short_code);
        }

        return view('shorten.form', [
            'shortUrl' => $shortUrl,
            'originalUrl' => $request->url
        ]);
    }

}
