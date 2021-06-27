<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index()
    {
        if (request()->slider_images) return Document::select('id', 'path', 'name')->where('type', 'slider_image')->get();
    }

    public function store()
    {
        $path = request()->image->store('slider_images', ['disk' => 'public']);
        return Document::create(request()->only('name', 'path', 'type') + ['path' => $path, 'type' => 'slider_image']);
    }

    public function destroy(Document $document)
    {
        $document->delete();
    }
}
