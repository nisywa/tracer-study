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
        
        // Get surveys with user's status from survey_user table
        $survey = Survey::select('survey.*', 'survey_user.status', 'survey_user.tanggal_mengisi')
            ->join('survey_user', 'survey.id', '=', 'survey_user.survey_id')
            ->where('survey_user.user_id', Auth::id())
            ->get()
            ->map(function ($survey) {
                // Store original dates for comparison
                $startDate = Carbon::parse($survey->tanggal_mulai);
                $endDate = Carbon::parse($survey->tanggal_selesai);
                $now = now();
                
                // Format dates for display
                $survey->tanggal_mulai = $startDate->format("d-m-Y");
                $survey->tanggal_selesai = $endDate->format("d-m-Y");
                
                // Determine if survey is still active
                if ($now >= $startDate && $now <= $endDate) {
                    $survey->status_aktif = "Aktif";
                } else {
                    $survey->status_aktif = "Selesai";
                }
                
                return $survey;
            });

        foreach ($survey as $item) {
            $item->status_aktif = Carbon::parse($item->tanggal_selesai) >= now() ? "Aktif" : "Selesai";
            $item->tanggal_mulai = Carbon::parse($item->tanggal_mulai)->format("d-m-Y");
            $item->tanggal_selesai = Carbon::parse($item->tanggal_selesai)->format("d-m-Y");
        }
        
        $user = Auth::user();
        
        // Load relasi berdasarkan role user
        if ($user->hasRole('alumni')) {
            $user->load('alumni');
        } elseif ($user->hasRole('atasan')) {
            $user->load('atasan');
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
