<?php

namespace App\Http\Controllers;
use App\Post;
use Illuminate\Http\Request;


class PostController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index ()
    {
        $post = Post::all();
        return response()->json([
            'success' => true,
            'message' =>'List Semua Post',
            'data'    => $post,
        ], 200);
    }

    public function store (Request $request)
    {
        $dataValidation = $this->validate($request,[
            'title' => 'required',
            'content'=>'required',
        ]);


            $post = Post::create([
                'title'     => $request->title,
                'content'   => $request->content,
            ]);

            if ($post) {
                return response()->json([
                    'success' => true,
                    'message' => 'Post Berhasil Disimpan!',
                    'data' => $post
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Post Gagal Disimpan!',
                ], 400);
            }

    }

    public function show ($id)
    {
        $post = Post::find($id);

        if ($post){
            return response()->json([
                'success' =>true,
                'message' =>'Detail Post',
                'data' =>$post
            ], 200);
        }
        return response()->json([
            'success' =>false,
            'message' =>'Post tidak ditemukan',

        ],404);

    }

    public function update(Request $request, $id)
    {
        $dataValidation = $this->validate($request,[
            'title' => 'required',
            'content'=>'required',
        ]);

        $post = Post::whereId($id)->update([
            'title'     => $request->title,
            'content'   => $request->content,
        ]);

        if ($post) {
            return response()->json([
                'success' => true,
                'message' => 'Post Berhasil Diupdate!',
                'data' => $post
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Post Gagal Diupdate!',
            ], 400);
        }

    }

    public function destroy ($id)
    {
        $post = Post::where('id',$id)->delete();

        if($post){
            return response()->json([
                'success' => true,
                'message' => ' Data berhasil dihapus!',
            ],200);
        }

        return response()->json([
            'success' => false,
            'message' => ' Data gagal dihapus!',
        ],400);

    }


}



