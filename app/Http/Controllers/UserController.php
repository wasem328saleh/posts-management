<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\AddCommentRequest;
use App\Http\Requests\User\AddImagesRequest;
use App\Http\Requests\User\AddPostRequest;
use App\Http\Requests\User\DeleteImageRequest;
use App\Http\Requests\User\UpdateMyPostRequest;
use App\Jobs\DeleteImagesPost;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected UserRepositoryInterface $user;

    /**
     * @param UserRepositoryInterface $user
     */
    public function __construct(UserRepositoryInterface $user){
        $this->user = $user;
    }

    public function get_all_posts_with_their_comments()
    {
        return $this->user->get_all_posts_with_their_comments();
    }
    public function add_comment_on_post(AddCommentRequest $request)
    {
        return $this->user->add_comment_on_post($request);
    }
    public function get_all_my_posts_with_their_comments()
    {
        return $this->user->get_all_my_posts_with_their_comments();
    }
    public function add_post(AddPostRequest $request)
    {
        return $this->user->add_post($request);
    }
    public function update_my_post($id,UpdateMyPostRequest $request)
    {
        return $this->user->update_my_post($id,$request);
    }
    public function delete_my_post($id)
    {
        return $this->user->delete_my_post($id);
    }
    public function get_user_profile($id)
    {
        return $this->user->get_user_profile($id);
    }

    public function add_post_images(AddImagesRequest $request)
    {
        return $this->user->add_post_images($request);
    }
    public function delete_post_image($id,DeleteImageRequest $request)
    {
        return $this->user->delete_post_image($id,$request);
    }
}
