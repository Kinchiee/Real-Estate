<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;

class AdminController extends Controller
{
    //This is for the Admin Dashboard, the FIRST ROUTER you set on the web route
    public function AdminDashboard()
    {
        return view('admin.index');
    } //End Method

    //This is for the logout method for the Admin role
    public function AdminLogout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin/login');
    } //End Method

    //This is for Admin Login
    public function AdminLogin()
    {
        return view('admin.admin_login');
    } //End Method

    //This is for viewing the Profile of the Admin
    public function AdminProfile()
    {
        //First access the ID that are required on viewing specific profile save it on variable
        $id = Auth::user()->id;
        //Now find the specific ID using Eloquent ORM and find() method on accessing the ID save the result in variable
        $profileData = User::find($id);
        //Then send the profileData to the template that you are accessing with the compact method
        return view('admin.admin_profile_view', compact('profileData'));
    }

    //Posting to database the updated info's about the user (admin)
    public function AdminProfileStore(Request $request)
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        $data->username = $request->username;
        $data->name = $request->name;
        $data->phone = $request->phone;
        $data->email = $request->email;
        $data->address = $request->address;

        if ($request->file('photo')) {
            $file = $request->file('photo');
            //This will replace the old photos that are saved on the database into new uploaded one
            @unlink(public_path('upload/admin_images/' . $data->photo));
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('upload/admin_images'), $filename);
            $data['photo'] = $filename;
        }
        $data->save();
        $notification = array(
            'message' => 'Admin Profile has been updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    //Viewing the form for changing the password
    public function AdminChangePassword()
    {
        $id = Auth::user()->id;
        $profileData = User::find($id);
        return view('admin.admin_change_password', compact('profileData'));
    }

    //Posting the data into the database, and checking for different info's
    public function AdminUpdatePassword(Request $request)
    {
        //Validate the fields
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed'
        ]);
        //Match the Old Password to New Password
        if (!Hash::check($request->old_password, auth::user()->password)) {
            $notification = array(
                'message' => 'The information does not match!',
                'alert-type' => 'error'
            );

            return back()->with($notification);
        }
        //Change the password
        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);
        $notification = array(
            'message' => 'Password change successfully',
            'alert-type' => 'success'
        );
        return back()->with($notification);
    }
}
