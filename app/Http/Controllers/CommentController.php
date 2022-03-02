<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Events\CommentEvent;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Writer\Ods\Content;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        //obtener los datos del comentario
        request()->validate([
            'content'=>['required', 'string']
        ]);

        //Crear comentario
        $comment = Comment::create([
            'user_id'=>auth()->user()->id,
            'publication_id'=>$request->id_publication,
            'content'=> $request->content
        ]);

        //event(new CommentEvent($comment));
        return redirect()->action([PublicationsController::class, 'index']);
    }
}
