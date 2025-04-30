<?php

namespace App\Repositories\User;

use App\Http\Resources\PostResource;
use App\Http\Resources\UserInfoResource;
use App\Jobs\DeleteImagesPost;
use App\Models\Image;
use App\Models\Post;
use App\Models\User;
use App\Traits\ApiResponderTrait;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class UserRepository implements UserRepositoryInterface
{
    use ApiResponderTrait,GeneralTrait;
    public function get_all_posts_with_their_comments()
    {
        // TODO: Implement get_all_posts_with_their_comments() method.

        abort_if(Gate::denies('user_get_all_posts_with_their_comments'), Response::HTTP_FORBIDDEN, 'Forbidden');

        try {
            $cacheKey='all_posts_with_their_comments';
            $posts=Cache::remember($cacheKey,now()->addMinutes(10), function () {
                return Post::with(['comments','images'])->get();
            });
            if ($posts->isEmpty()){
                return $this->returnData(PostResource::collection($posts),'No posts available at the moment',204);
            }
            return $this->returnData(PostResource::collection($posts),'Posts Retrieved Successfully',200);
        }catch (\Exception $exception){
            return $this->returnError($exception->getCode(),$exception->getMessage());
        }
    }

    public function add_comment_on_post($request)
    {
        // TODO: Implement add_comment_on_post() method.

        abort_if(Gate::denies('user_add_comment_on_post'), Response::HTTP_FORBIDDEN, 'Forbidden');

        try {
            DB::beginTransaction();
            $user=Auth::user();
            $comment=$request->input('comment');
            $post_id=$request->input('post_id');
            $user->comments()->create([
                'comment'=>$comment,
                'post_id'=>$post_id,
            ]);
            DB::commit();
            return $this->returnSuccessMessage('Comment Added Successfully',200);
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->returnError($exception->getCode(),$exception->getMessage());
        }
    }

    public function get_all_my_posts_with_their_comments()
    {
        // TODO: Implement get_all_my_posts_with_their_comments() method.

        abort_if(Gate::denies('user_get_all_my_posts_with_their_comments'), Response::HTTP_FORBIDDEN, 'Forbidden');
        try {
            $user=Auth::user();
            $my_posts=$user->posts()->with(['comments','images'])->get();
            if ($my_posts->isEmpty()){
                return $this->returnData(PostResource::collection($my_posts),'No your posts available at the moment',204);
            }
            return $this->returnData(PostResource::collection($my_posts),'Your Posts Retrieved Successfully',200);
        }catch (\Exception $exception){
            return $this->returnError($exception->getCode(),$exception->getMessage());
        }
    }

    public function add_post($request)
    {
        // TODO: Implement add_post() method.

        abort_if(Gate::denies('user_add_post'), Response::HTTP_FORBIDDEN, 'Forbidden');
        try {
            DB::beginTransaction();
            $user=Auth::user();
            $title=$request->input('title');
            $content=$request->input('content');

            $post=Post::create([
                'title'=>$title,
                'content'=>$content,
                'user_id'=>$user->id
            ]);
//            $post=$user->posts()->create([
//                'title'=>$title,
//                'content'=>$content
//            ]);

            if ($request->hasFile('images')) {
                $images=$request->file('images');
                foreach ($images as $image) {
                    $post->images()->create([
                        'url'=>$this->UploadeImage('post_images', $image)
                    ]);
                }
            }

            DB::commit();
            return $this->returnSuccessMessage('Add Post Successfully',200);
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->returnError($exception->getCode(),$exception->getMessage());
        }
    }

    public function update_my_post($id,$request)
    {
        // TODO: Implement update_my_post() method.

        abort_if(Gate::denies('user_update_my_post'), Response::HTTP_FORBIDDEN, 'Forbidden');
        try {
            DB::beginTransaction();
            $post_id=$id;
            $user=Auth::user();
            $post=Post::where('id',$post_id)->first();
            if ($post){
            $existing=$this->relationship_exists($user->id,$post_id,'posts',User::class);
            if ($existing){


                    if ($request->input('title')) {
                        $post->title=$request->input('title');
                    }
                    if ($request->input('content')) {
                        $post->content=$request->input('content');
                    }
                    $post->save();
                    DB::commit();
                    return $this->returnSuccessMessage('Your Post Updated Successfully',204);
            }
            return $this->returnError(403,'Forbidden Update This Post');
            }
            return $this->returnError(404,'Post Not Found');
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->returnError($exception->getCode(),$exception->getMessage());
        }
    }

    public function delete_my_post($id)
    {
        // TODO: Implement delete_my_post() method.

        abort_if(Gate::denies('user_delete_my_post'), Response::HTTP_FORBIDDEN, 'Forbidden');
        try {
            DB::beginTransaction();
            $post_id=$id;
            $user=Auth::user();
            $post=Post::where('id',$post_id)->first();
            if ($post){
            $existing=$this->relationship_exists($user->id,$post_id,'posts',User::class);
            if ($existing){
                    if ($post->hasImages()){
                        dispatch(new DeleteImagesPost($post));
                    }
                    $post->delete();
                    DB::commit();
                    return $this->returnSuccessMessage('Your Post Deleted Successfully',204);

            }
            return $this->returnError(403,'Forbidden Delete This Post');
            }
            return $this->returnError(404,'Post Not Found');
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->returnError($exception->getCode(),$exception->getMessage());
        }
    }

    public function get_user_profile($id)
    {
        // TODO: Implement get_user_profile() method.

        abort_if(Gate::denies('user_get_user_profile'), Response::HTTP_FORBIDDEN, 'Forbidden');

        try {
            $user=User::findOrFail($id);
            return $this->returnData(new UserInfoResource($user->load(['posts','post_images'])),'this is user info profile',200);
        }catch (\Exception $exception){
            return $this->returnError($exception->getCode(),$exception->getMessage());
        }
    }

    public function add_post_images($request)
    {
        // TODO: Implement add_post_images() method.
        abort_if(Gate::denies('user_add_post_images'), Response::HTTP_FORBIDDEN, 'Forbidden');

        try {
            DB::beginTransaction();
            $post_id=$request->input('post_id');
            $user=Auth::user();
            $post=Post::where('id',$post_id)->first();
            if ($post){
                $existing=$this->relationship_exists($user->id,$post_id,'posts',User::class);
                if ($existing){
                    if ($request->hasFile('images')) {
                        $images=$request->file('images');
                        foreach ($images as $image) {
                            $post->images()->create([
                                'url'=>$this->UploadeImage('post_images', $image)
                            ]);
                        }
                        DB::commit();
                        return $this->returnSuccessMessage('Add Images for Your Post Successfully',204);
                    }
                }
                return $this->returnError(403,'Forbidden Add Images This Post');
            }
            return $this->returnError(404,'Post Not Found');
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->returnError($exception->getCode(),$exception->getMessage());
        }
    }

    public function delete_post_image($id,$request)
    {
        // TODO: Implement delete_post_image() method.
        abort_if(Gate::denies('user_delete_post_image'), Response::HTTP_FORBIDDEN, 'Forbidden');

        try {
            DB::beginTransaction();
            $post_id=$request->input('post_id');
            $user=Auth::user();
            $post=Post::where('id',$post_id)->first();
            if ($post){
                $existing=$this->relationship_exists($user->id,$post_id,'posts',User::class);
                if ($existing){
                    $image_id=$id;
                    $existing_image=$this->relationship_exists($post_id,$image_id,'images',Post::class);
                    if ($existing_image){
                        $image=Image::findOrFail($image_id);
                        if ($image){
                            $url=$image->url;
                            if (Str::startsWith($url,'/'))
                            {
                                File::delete(public_path($this->after('/',$url)));
                            }else
                            {
                                File::delete(public_path($url));
                            }
                            $image->delete();
                            DB::commit();
                            return $this->returnSuccessMessage('Image Post Deleted Successfully',204);
                        }
                        return $this->returnError(404,'Image Not Found');

                    }
                    return $this->returnError(403,'Forbidden Add Images This Post');


                }
                return $this->returnError(403,'Forbidden Add Images This Post');
            }
            return $this->returnError(404,'Post Not Found');
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->returnError($exception->getCode(),$exception->getMessage());
        }
    }
}
