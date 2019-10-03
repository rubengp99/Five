<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
class ProfileController extends Controller
{

    private $chatkit;
    private $roomId;

    public function __construct()
    {
        $this->middleware('auth');
        $this->chatkit = app('ChatKit');
        $this->roomId = 'e01b3fa9-3295-4178-9521-dbd5d5757cbd';
    }

    public function index()
    {
        return view('auth.profile');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('profile',compact('user',$user));
    }

    public function update(Request $request){
        
        $chatkit = app('ChatKit');

        $user = Auth::user();

        $request->validate([
            'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'string|max:255',
            'email' => 'string|email|max:255|unique:users',
        ]);

        if(!strpos($user->profile_image,'default') && !strpos($user->profile_image,'http') && !strpos($user->profile_image,'https')){
           \Storage::delete($user->profile_image);  
        }


        //the following IF statements verify if the inputs are filled to proceed to update the user
        if($_FILES['avatar']['size']!='0'){          
            $avatarName = $user->id.'_avatar'.time().'.'.request()->avatar->getClientOriginalExtension();
            \Storage::disk('public')->put($avatarName,  \File::get(request()->avatar));
            $user->profile_image = $avatarName;  
            $user->save();      
        }
      
        if($request->input('new_name')){
            $user->name = $request->input('new_name');
            $user->save();         
        }

        if($request->input('new_email')){
            $user->email = $request->input('new_email');
            $user->save();
        }

        if($request->input('new_password')){
            $user->password = bcrypt($request->input('new_password'));
            $user->save();
        }

         $chatkit->updateUser([
            'id' => 'rdgp99@gmail.com',
            'name' => $user->name,
            'custom_data' => [
                'avatar' => $user->profile_image,
              ] 
        ]);

        return back()
            ->with('success','Your profile is updated now!');
    }
}
