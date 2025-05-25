<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyUser;
use App\Models\SurveyUserJawaban;
use App\Models\TemplatePertanyaan;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;
use PhpParser\Node\Stmt\TryCatch;

class SurveyUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $surveys = Survey::whereHas('surveyUsers', function ($query) {
            $query->where('user_id', Auth::id());
        })
            ->where('tanggal_mulai', '<=', now())
            ->where('tanggal_selesai', '>=', now())
            ->get();

        $alumni = Auth::user();

        return view('user.views.index', [
            'surveys' => $surveys,
            'alumni' => $alumni
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SurveyUser $surveyUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SurveyUser $surveyUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SurveyUser $surveyUser)
    {
        //
    }


    public function survey()
    {
        return view('user.views.survey');
    }

    public function surveyUserPertanyaan($id)
    {
        $surveyUser = SurveyUser::where('user_id', Auth::id())->where('survey_id', $id)->where('status', 0)->first();
        if (!$surveyUser) {
            return redirect()->back()->with('error', 'Survey tidak ditemukan');
        }
        $survey = Survey::where('id', $surveyUser->survey_id)->first();
        $survey->tanggal_mulai = Carbon::parse($survey->tanggal_mulai)->format("d-m-Y");
        $survey->tanggal_selesai = Carbon::parse($survey->tanggal_selesai)->format("d-m-Y");

        $surveyUserPertanyaan = TemplatePertanyaan::with(['template_jawaban' => function ($query) {
            $query->orderBy('urutan', 'asc');
        }])
            ->where('id_survey', $surveyUser->survey_id)
            ->orderBy('urutan')
            ->get()
            ->groupBy('blok')
            ->map(function ($questions) {
                return $questions->values(); // Reset array keys for each group
            });
        // dd($surveyUserPertanyaan);

        return view('user.views.survey', [
            'survey' => $survey,
            'surveyPertanyaan' => $surveyUserPertanyaan
        ]);
    }

    public function saveSurvey(Request $request, $id)
    {
        try {
            // Get current user's survey assignment
            $surveyUser = SurveyUser::where('user_id', Auth::id())->where('survey_id', $id)->first();
            if (!$surveyUser) {
                return redirect()->back()->with('error', 'Survey tidak ditemukan');
            }

            // Process each question response
            foreach ($request->except('_token') as $questionId => $answer) {
                // Check if the answer is a file upload
                if ($request->hasFile($questionId)) {
                    $file = $request->file($questionId);
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('survey_uploads', $fileName, 'public');
                    $answer = $path;
                }
                // Handle checkbox arrays
                elseif (is_array($answer)) {
                    $answer = implode(',', $answer);
                }

                // Create or update response
                SurveyUserJawaban::updateOrCreate(
                    [
                        'survey_user_id' => $surveyUser->id,
                        'template_pertanyaan_id' => $questionId
                    ],
                    [
                        'jawaban' => $answer
                    ]
                );
            }

            // update status survey user
            $surveyUser->status = 1;
            $surveyUser->save();


            return redirect()->back()->with('success', 'Jawaban survey berhasil disimpan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function sendEmail ($id){
        $surveyUsers = SurveyUser::with (['user.alumni','user.atasan'])->where('survey_id', $id)->get();
        if($surveyUsers->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada pengguna yang terdaftar untuk survei ini.');
        }
        $template_pertanyaan = TemplatePertanyaan::getTemplatePertanyaan($id);
        if($template_pertanyaan->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada pertanyaan yang tersedia untuk survei ini.');
        }
        foreach ($surveyUsers as $surveyUser) {
            $nip = $surveyUser->user->alumni->nip ?? $surveyUser->user->atasan->nip;
            $data = [
                'subject' => 'Akun Tracer Study Politeknik Statistika STIS',
                'title' => 'Akun Tracer Study Politeknik Statistika STIS',
                'nama' => $surveyUser->user->name,
                'email' => $surveyUser->user->email,
                'password' => substr($nip, 0, 5),
                'link' => route('user.survey.survey', $id),
            ];

            Mail::to($surveyUser->user->email)->send(new SendEmail($data));
        }


    }

    public function search_user (Request $request){
        $search = $request->input('search');
        $type = $request->input('type', 'alumni');
        $surveyId = $request->input('survey_id');

        $query = User::query();

        if ($type === 'alumni') {
            $query->whereHas('roles', function($q) {
                $q->where('name', 'alumni');
            });
        } else {
            $query->whereHas('roles', function($q) {
                $q->where('name', 'atasan');
            });
        }

        // Exclude users that are already in the survey using leftJoin
        if ($surveyId) {
            $query->leftJoin('survey_user', function($join) use ($surveyId) {
                $join->on('users.id', '=', 'survey_user.user_id')
                     ->where('survey_user.survey_id', '=', $surveyId);
            })
            ->whereNull('survey_user.id'); // Only get users not in survey_user
        }

        $users = $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        })
        ->with(['alumni' => function($q) {
            $q->select('id', 'user_id', 'nama', 'nip');
        }])
        ->select('users.id', 'users.name', 'users.email')
        ->distinct()
        ->limit(10)
        ->get();

        return response()->json($users);
    }

    public function add_user (Request $request){
        $userId = $request->input('user_id');
        $surveyId = $request->input('survey_id');
        try {
            SurveyUser::updateOrCreate(
                [
                    'user_id' => $userId,
                    'survey_id' => $surveyId
                ],
                [
                    'status' => 0
                ]
            );

        return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

    }

    public function destroy($survey_user_id){
        $survey_user = SurveyUser::findOrFail($survey_user_id);
        $survey_id = $survey_user->survey_id;

        // Delete associated answers first
        $survey_user->jawaban()->delete();

        // Delete the survey user record
        $survey_user->delete();

        return redirect()->route('admin.survey.details', ['id' => $survey_id])
            ->with('success', 'User Survei deleted successfully.');
    }
}
