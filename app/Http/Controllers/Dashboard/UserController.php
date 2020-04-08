<?php

namespace App\Http\Controllers\Dashboard;

use App\User;
use App\Role;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    public function __construct()
    {
        //create read update delete
        $this->middleware(['permission:read_users'])->only('index');
        $this->middleware(['permission:create_users'])->only('create');
        $this->middleware(['permission:update_users'])->only('edit');
        $this->middleware(['permission:delete_users'])->only('destroy');

    }//end of constructor

    public function index(Request $request)
    {
        $users = User::whereRoleIs('user')->where(function ($q) use ($request) {
       // $users = User::where(function ($q) use ($request) {

            return $q->when($request->search, function ($query) use ($request) {

                return $query->where('name', 'like', '%' . $request->search . '%');

            });
                dd($users);
        })->latest()->paginate(25);

        return view('dashboard.users.index', compact('users'));

    }//end of index

    public function create()
    {
                $roles = Role::all();

        return view('dashboard.users.create', compact('roles'));

    }//end of create

    public
    function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'image' => 'image',
            'password' => 'required|confirmed',
        ]);

        $request_data = $request->except(['password', 'password_confirmation', 'permissions', 'image']);
        $request_data['password'] = bcrypt($request->password);

        if ($request->image) {

            Image::make($request->image)
                ->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('uploads/user_images/' . $request->image->hashName()));

            $request_data['image'] = $request->image->hashName();

        }//end of if

        $user = User::create($request_data);
        // $user->attachRole('admin');
        $user->syncRoles($request->roles);

        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.users.index');

    }//end of store

    public
    function edit(User $user)
    {
                        $roles = Role::all();

        return view('dashboard.users.edit', compact('user','roles'));

    }//end of user

    public
    function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => ['required', Rule::unique('users')->ignore($user->id),],
            'image' => 'image',
        ]);

        $request_data = $request->except(['permissions', 'image']);

        if ($request->image) {

            if ($user->image != 'default.png') {

                Storage::disk('public_uploads')->delete('/user_images/' . $user->image);

            }//end of inner if

            Image::make($request->image)
                ->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('uploads/user_images/' . $request->image->hashName()));

            $request_data['image'] = $request->image->hashName();

        }//end of external if

        $user->update($request_data);
        $user->syncRoles($request->roles);

        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.users.index');

    }//end of update

    public
    function destroy(User $user)
    {
        if ($user->image != 'default.png') {

            Storage::disk('public_uploads')->delete('/user_images/' . $user->image);

        }//end of if

        $user->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.users.index');

    }//end of destroy

}//end of controller
