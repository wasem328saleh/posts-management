<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Traits\ApiResponderTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests,ApiResponderTrait;

    public function test()
    {

//        $user=User::where('id',2)->first();
//
//        return PostResource::collection($user->posts()->with(['comments','images'])->get());

        try {
//            $post=Post::with(['comments','images'])->get();
            $post=Comment::all();
            if ($post->isEmpty()){
                return $this->returnData($post,'No posts available at the moment',204);
            }
            return $this->returnData($post,'Posts Retrieved Successfully',200);
        }catch (\Exception $exception){
            return $this->returnError($exception->getCode(),$exception->getMessage());
        }
    }
}
