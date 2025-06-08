<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Alumni;
use App\Models\Atasan;
use App\Models\Survey;
use App\Models\SurveyUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Carbon\Carbon;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    // controller buat index user
    public function index()
    {
        $survey = Survey::whereHas('surveyUsers', function ($query) {
            $query->where('user_id', Auth::id());
        })->get();

        foreach ($survey as $item) {
            $item->status_aktif = Carbon::parse($item->tanggal_selesai) >= now() ? "Aktif" : "Selesai";
            $item->tanggal_mulai = Carbon::parse($item->tanggal_mulai)->format("d-m-Y");
            $item->tanggal_selesai = Carbon::parse($item->tanggal_selesai)->format("d-m-Y");
        }
        
        $user=Auth::user();
        if ($user->role=='alumni') {
            $user = Alumni::where('user_id', Auth::id())->first();
        } elseif ($user->role=='atasan') {
            $user = Atasan::where('user_id', Auth::id())->first();
        } else {
            $user = [];
        }
        
        return view('user.views.index', [
            'user' => $user,
            'survey' => $survey,
        ]);
    }
    // buat di indeks user
    // public function profileUser(){
    //     // $alumni=Alumni::findOrFail($id);
    //     $alumni=Alumni::where('user_id',31)->first();


    //     return view('user.views.index', [

    //         'profil'=>$alumni
    //     ]);
    // }

    public function editAdmin(){
        return view('admin.views.profileAdmin.index', ['user' => Auth::user()]);
    }

    public function updateAdmin(Request $request){
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),  
        ]);

        if ($request->filled('new_password')) {
            $request->validate([
                'new_password' => 'required|string|min:8|confirmed',
                'old_password' => 'required|current_password',
            ]);
        }

        $user = Auth::user();
        $user->email = $request->input('email');

        if ($request->filled('new_password')) {
            $user->password = bcrypt($request->input('new_password'));
        }

        $user->save();

        return Redirect::route('admin.profile.edit')->with('status', 'profile-updated');
    }
}
