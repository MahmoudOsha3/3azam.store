<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use App\Traits\ManageApiTrait;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ManageApiTrait  ;

    public function view()
    {
        $this->authorize('usersView' , Admin::class ) ;
        return view('pages.dashboard.users.index') ;
    }

    public function index(Request $request)
    {
        $this->authorize('usersView' , Admin::class ) ;
        $users = User::with('orders')->filter($request)->latest()->paginate(15);
        return $this->successApi($users , 'Users fetched successfully ');
    }

    public function show(User $user)
    {
        return $this->successApi($user->load('orders') , 'User fetched successfully ');
    }

    public function destroy(User $user)
    {
        $this->authorize('userDelete' , Admin::class) ;
        if($user->orders()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن حذف المستخدم لأنه مرتبط بأوردرات'
            ], 400);
        }
        $user->delete() ;
        return $this->successApi(null , 'Users deleted successfully');
    }

}
