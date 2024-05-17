<?php

namespace App\Http\Controllers;
use App\Models\Comment;
use App\Models\Document;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'commentable_id' => 'required|integer',
            'commentable_type' => 'required|string',
        ]);

        $comment = Comment::create([
            'content' => $request->content,
            'commentable_id' => $request->commentable_id,
            'commentable_type' => $request->commentable_type,
            'user_id' => auth()->id(),
        ]);

        return response()->json($comment, 201);
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return response()->json(null, 204);
    }
}

