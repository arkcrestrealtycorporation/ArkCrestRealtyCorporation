<?php

namespace App\Http\Controllers;

use App\Models\NewsPost;
use Illuminate\View\View;

class NewsUpdateController extends Controller
{
    public function index(): View
    {
        $posts = NewsPost::query()
            ->publiclyVisible()
            ->with('media')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(9);

        return view('news-updates', [
            'posts' => $posts,
        ]);
    }
}
