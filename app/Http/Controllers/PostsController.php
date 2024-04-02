<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Requests\PostsRequest;
use App\Http\Requests\UpdateRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $posts=Post :: all();
        return response()->json([
            'status'=>'success',
            'posts'=>$posts
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostsRequest $request)
    {
     

        try{
            DB::beginTransaction();

            $posts= Post::create([
                'title'=>$request->title,
                'content'=>$request->content,
            ]);


            DB::commit();

           return response()->json(
            ['status'=>'post created successfuly',
            'posts'=>$posts]
            );



        }
        catch(\Throwable  $th){

                DB::rollback();
                Log::error($th);
                return response()->json([
                    'status'=>'post not created',
                    'error'=>$th->getMessage(),
                    
                ], 500);

        }
      

        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        
        return response()->json([
          'status'=>'success',
          'posts'=>$post,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Post $post)
    {


        try{
            DB::beginTransaction();

            if($request->has('title')){
                $post->update([
                    'title'=>$request->title]);
            }

            elseif ($request->has('content')) {
                $post->update([
                    'content'=>$request->content
                ]);
        
            }


            else{

                $post->update([
                    'title'=>$request->title,
                    'content'=>$request->content
                ]);
            }

           
    
            DB::commit();

            return response()->json([
                'status'=>'post updated succesfully',
                'posts'=>$post
            ]);
           

        }catch(\Throwable $th){
                DB::rollback();
                Log::error($th);
                return response()->json([
                    'status'=>'post not updated',
                    'error'=>$th->getMessage(),
                    
                ], 500);


        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)

    {
       $post->delete();
       return response()->json([
        'status'=>'post deleted succesfully',
        'posts'=>$post
       ]);
    }
}
