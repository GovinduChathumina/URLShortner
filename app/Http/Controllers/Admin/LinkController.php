<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShortUrl;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $links = ShortUrl::when($search, function ($query) use ($search) {
            $query->where('original_url', 'like', "%$search%")
                  ->orWhere('short_code', 'like', "%$search%");
        })->latest()->paginate(10);

        return view('admin.links.index', compact('links', 'search'));
    }

    public function destroy($id)
    {
        $link = ShortUrl::findOrFail($id);
        $link->delete();

        return redirect()->route('admin.links.index')->with('success', 'Link deleted.');
    }
}
