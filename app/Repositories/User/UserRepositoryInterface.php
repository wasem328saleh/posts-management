<?php

namespace App\Repositories\User;

interface UserRepositoryInterface
{
    public function get_all_posts_with_their_comments();
    public function add_comment_on_post($request);
    public function get_all_my_posts_with_their_comments();
    public function add_post($request);
    public function update_my_post($id,$request);
    public function delete_my_post($id);
    public function get_user_profile($id);

    public function add_post_images($request);
    public function delete_post_image($id,$request);
}
