<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DocumentController extends Controller {

    public function index()
    {
        $documents = Cache::remember('documents', 60, function () {
            return Document::with('comments')->get();
        });
    
        return $documents;
    }
    


    public function store(Request $request) {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,txt',
        ]);

        $path = $request->file('file')->store('documents');

        $document = Document::create([
            'title' => $validated['title'],
            'file_path' => $path,
            'user_id' => auth()->id(),
        ]);

        return response()->json($document, 201);
    }

    public function show($id) {
        $document = Document::with('tags', 'comments')->findOrFail($id);
        return response()->json($document);
    }


    public function update(Request $request, $id) {
        $document = Document::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'file' => 'sometimes|required|file|mimes:pdf,doc,docx,txt',
        ]);

        if ($request->hasFile('file')) {
            Storage::delete($document->file_path);
            $path = $request->file('file')->store('documents');
            $document->file_path = $path;
        }

        $document->title = $validated['title'] ?? $document->title;
        $document->save();

        return response()->json($document);
    }

    public function destroy($id) {
        $document = Document::findOrFail($id);
        Storage::delete($document->file_path);
        $document->delete();

        return response()->json(null, 204);
    }
}
