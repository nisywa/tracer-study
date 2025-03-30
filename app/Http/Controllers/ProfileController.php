<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Alumni;
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
        $survey=Survey::whereHas('surveyUsers',function($query){
            $query->where('user_id',Auth::id());
        })->get();

        foreach($survey as $item) {
            $item->tanggal_mulai = Carbon::parse($item->tanggal_mulai)->format("d-m-y");
            $item->tanggal_selesai = Carbon::parse($item->tanggal_selesai)->format("d-m-y");
            $item->status_aktif = $item->tanggal_selesai >= now() ? "Aktif" : "Selesai";
        }

        $alumni=Alumni::where('user_id',Auth::id())->first();
        return view('user.views.index',[
            'alumni'=>$alumni,
            'survey'=>$survey,
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
}

