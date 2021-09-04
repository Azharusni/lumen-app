<?php

namespace App\Http\Controllers;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index ()
    {

        // $user = auth()->user();
        // $post = $user->posts()->paginate(5);
        $post = Auth::user()->posts()->paginate(5);
        $response = [
            'pagination' => [
            'total' => $post->total(),
            'per_page' => $post->perPage(),
            'current_page' => $post->currentPage(),
            'last_page' => $post->lastPage(),
            'from' => $post->firstItem(),
            'to' => $post->lastItem()
            ],
            'data' => $post
          ];
          return response()->json($response);
        // return response()->json([
        //     'success' => true,
        //     'message' =>'List Semua Post',
        //     'data'    => $post,
        // ], 200);
    }

    public function store (Request $request)
    {
        $dataValidation = $this->validate($request,[
            'title' => 'required',
            'content'=>'required',
        ]);


            $post = Auth::user()->posts()->create([
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
                    'message' => $dataValidation()->error(),
                ], 400);
            }

    }

    public function show ($id)
    {
        $post = Post::findOrFail($id);

        if (Auth::user()->id!== $post->user_id){
            return response()->json(['status'=>'error', 'message'=>'Unauthorized'], 401);
        }

        return response()->json(['message'=>'success', 'post'=>$post],200);

        // if ($post){
        //     return response()->json([
        //         'success' =>true,
        //         'message' =>'Detail Post',
        //         'data' =>$post
        //     ], 200);
        // }
        // return response()->json([
        //     'success' =>false,
        //     'message' =>'Post tidak ditemukan',

        // ],404);

    }

    public function update(Request $request, $id)
    {
        $dataValidation = $this->validate($request,[
            'title' => 'required',
            'content'=>'required',
        ]);

        // $post = Post::whereId($id)->update([
        //     'title'     => $request->title,
        //     'content'   => $request->content,
        // ]);

        $post = Post::find($id);
        if (Auth::user()->id!== $post->user_id){
            return response()->json(['status'=>'error', 'message'=>'Unauthorized'], 401);
        }

        $post = $post->update([
            'title'     => $request->title,
            'content'   => $request->content,

        ]);

        // if ($post) {
            return response()->json([
                'success' => true,
                'message' => 'Post Berhasil Diupdate!',
                'data' => $post
            ], 201);
        // } else {
        //     return response()->json([
        //         'success' => false,
        //         'message' => $dataValidation()->error(),
        //     ], 400);
        // }

    }

    public function destroy ($id)
    {
        // $post = Post::where('id',$id)->delete();

        $post = Post::find($id);

        if (Auth::user()->id!== $post->user_id){
            return response()->json(['status'=>'error', 'message'=>'Unauthorized'],401);
        }

        if(Post::destroy($id)){
            return response()->json(['status'=>'success', 'message'=>'Post Deleted Successfully']);
        }

        return response()->json(['status'=>'error', 'message'=>'Something went wrong']);

    }


}



