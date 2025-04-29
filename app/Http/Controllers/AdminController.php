<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\AddUserRequest;
use App\Http\Requests\Admin\DeleteUserRequest;
use App\Http\Requests\Admin\UpdateActivationRequest;
use App\Repositories\Admin\AdminRepositoryInterface;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected AdminRepositoryInterface $admin;

    /**
     * @param AdminRepositoryInterface $admin
     */
    public function __construct(AdminRepositoryInterface $admin){
        $this->admin = $admin;
    }

    public function get_all_users()
    {
        return $this->admin->get_all_users();
    }
    public function get_all_admins()
    {
        return $this->admin->get_all_admins();
    }
    public function get_all_regular_users()
    {
        return $this->admin->get_all_regular_users();
    }
    public function add_user(AddUserRequest $request)
    {
        return $this->admin->add_user($request);
    }
    public function delete_user($id)
    {
        return $this->admin->delete_user($id);
    }
    public function update_activation_user($id)
    {
        return $this->admin->update_activation_user($id);
    }
}
