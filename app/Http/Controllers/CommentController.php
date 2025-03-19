<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Item $item)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $comment = new Comment();
        $comment->user_id = Auth::id();
        $comment->item_id = $item->id;
        $comment->body = $request->body;
        $comment->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'comment' => $comment,
                'userName' => $comment->user->name,
                'formattedDate' => $comment->created_at->format('Y/m/d H:i')
            ]);
        }

        return back()->with('success', 'コメントを投稿しました!');
    }

    public function destroy(Request $request, Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true
            ]);
        }

        return back()->with('success', 'コメントを削除しました!');
    }
}
