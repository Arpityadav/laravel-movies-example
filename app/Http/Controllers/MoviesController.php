<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MoviesController extends Controller
{
    public function index()
    {
        $popularMovies = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/movie/popular')
            ->json()['results'];

        $nowPlaying = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/movie/now_playing')
            ->json()['results'];


        $genres = collect(Http::withToken(config('services.tmdb.token'))
                    ->get('https://api.themoviedb.org/3/genre/movie/list')
                    ->json()['genres']
                )->mapWithKeys(function ($genre) {
                    return [$genre['id'] => $genre['name']];
                });

        return view('index', compact('popularMovies', 'genres', 'nowPlaying'));
    }

    public function show($movie)
    {
        $movie = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/movie/'.$movie.'?append_to_response=credits,videos,images')
            ->json();


        return view('show', compact('movie'));
    }
}
