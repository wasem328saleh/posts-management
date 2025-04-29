<?php

namespace App\Repositories\Admin;

use App\Http\Resources\AddUserResource;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use App\Repositories\Admin\AdminRepositoryInterface;
use App\Traits\ApiResponderTrait;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class AdminRepository implements AdminRepositoryInterface
{
    use ApiResponderTrait,GeneralTrait;
    public function get_all_users()
    {
        // TODO: Implement get_all_users() method.

        abort_if(Gate::denies('get_all_users'), Response::HTTP_FORBIDDEN, 'Forbidden');
        try {
            $all_users=User::all();
            if ($all_users->isEmpty()){
                return $this->returnData(UserResource::collection($all_users),'No users available at the moment',204);
            }
            return $this->returnData(UserResource::collection($all_users),'This All Users',Response::HTTP_OK);
        }catch (\Exception $exception){
            return $this->returnError($exception->getCode(), $exception->getMessage());
        }
    }

    public function get_all_admins()
    {
        // TODO: Implement get_all_admins() method.

        abort_if(Gate::denies('get_all_admins'), Response::HTTP_FORBIDDEN, 'Forbidden');
        try {

            $admins=User::whereHas('roles', function($q){
                $q->where('title','admin');
            })
                ->where('id','!=',Auth::id())
                ->get();
            if ($admins->isEmpty()){
                return $this->returnData(UserResource::collection($admins),'No admins available at the moment',204);
            }
            return $this->returnData(UserResource::collection($admins),'This All Admins',Response::HTTP_OK);
        }catch (\Exception $exception){
            return $this->returnError($exception->getCode(), $exception->getMessage());
        }
    }

    public function get_all_regular_users()
    {
        // TODO: Implement get_all_regular_users() method.

        abort_if(Gate::denies('get_all_regular_users'), Response::HTTP_FORBIDDEN, 'Forbidden');
        try {

            $regular_users=User::whereHas('roles', function($q){
                $q->where('title','user');
            })
                ->whereDoesntHave('roles', function($q) {
                    $q->where('title', 'admin');
                })
                ->where('id','!=',Auth::id())
                ->get();

            if ($regular_users->isEmpty()){

                return $this->returnData(UserResource::collection($regular_users),'No regular users available at the moment',204);
            }
            return $this->returnData(UserResource::collection($regular_users),'This All Regular Users',Response::HTTP_OK);
        }catch (\Exception $exception){
            return $this->returnError($exception->getCode(), $exception->getMessage());
        }
    }

    public function add_user($request)
    {
        // TODO: Implement add_user() method.

        abort_if(Gate::denies('add_user'), Response::HTTP_FORBIDDEN, 'Forbidden');
        try {
            DB::beginTransaction();
            $name=$request->input('name');
            $email=$request->input('email');
            $password=rand(10000000,99999999);
            $user=User::create([
                'name'=>$name,
                'email'=>$email,
                'password'=>Hash::make($password),
                'is_active'=>true
            ]);
            $image_url='user_profile_default.png';
            if ($request->hasFile('image')) {
                $image=$request->file('image');
                $image_url=$this->UploadeImage('user_profile_images',$image);
            }
            $user->image_profile()->create([
                'url'=>$image_url
            ]);
            if ($request->input('role_title')){
                $role_id=Role::where('title',$request->input('role_title'))->first()->id;
                if ($role_id ==1){
                    $user->roles()->sync([1,2]);
                }elseif ($role_id ==2){
                    $user->roles()->sync(2);
                }
            }
            DB::commit();
            $data=[
                'name'=>$name,
                'email'=>$email,
                'password'=>$password,
                'image_profile'=>url($image_url)
            ];
            return $this->returnData($data,"Add User Successfully",200);
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->returnError($exception->getCode(), $exception->getMessage());
        }
    }

    public function delete_user($id)
    {
        // TODO: Implement delete_user() method.

        abort_if(Gate::denies('delete_user'), Response::HTTP_FORBIDDEN, 'Forbidden');
        try {
            DB::beginTransaction();
            if ($id==Auth::id()){
                return $this->returnError(Response::HTTP_FORBIDDEN,'You can not delete your own account');
            }
            $user=User::where('id',$id)->first();
            if ($user){
                $url=$user->image_profile->url;
                if (!$url!="user_profile_default.png"){
                    if (Str::startsWith($url,'/'))
                    {
                        File::delete(public_path($this->after('/',$url)));
                    }else
                    {
                        File::delete(public_path($url));
                    }
                }

                $user->delete();
                DB::commit();
                return $this->returnSuccessMessage('User Deleted Successfully',204);
            }
            return $this->returnError(Response::HTTP_NOT_FOUND,'User Not Found');
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->returnError($exception->getCode(), $exception->getMessage());
        }
    }

    public function update_activation_user($id)
    {
        // TODO: Implement update_activation_user() method.

        abort_if(Gate::denies('update_activation_user'), Response::HTTP_FORBIDDEN, 'Forbidden');
        try {
            DB::beginTransaction();
            if ($id==Auth::id()){
                return $this->returnError(Response::HTTP_FORBIDDEN,'You can not delete your own account');
            }
            $user=User::where('id',$id)->first();
            if ($user){
                $user->is_active=!$user->is_active;
                $user->save();
                DB::commit();
                return $this->returnSuccessMessage('User Updated Activation Successfully',204);
            }
            return $this->returnError(Response::HTTP_NOT_FOUND,'User Not Found');
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->returnError($exception->getCode(), $exception->getMessage());
        }
    }
}
