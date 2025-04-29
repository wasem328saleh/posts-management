<?php

namespace App\Repositories\Admin;

interface AdminRepositoryInterface
{
    public function get_all_users();
    public function get_all_admins();
    public function get_all_regular_users();
    public function add_user($request);
    public function delete_user($id);
    public function update_activation_user($id);


}
