<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyUser;
use App\Models\SurveyUserJawaban;
use App\Models\TemplatePertanyaan;
use App\Models\User;
use App\Services\SurveyEmailService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;
use PhpParser\Node\Stmt\TryCatch;

class SurveyUserController extends Controller
{
    protected $emailService;

    public function __construct(SurveyEmailService $emailService)
    {
        $this->emailService = $emailService;
    }
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

        $surveyUserPertanyaan = TemplatePertanyaan::with([
                'templateJawaban' => function ($query) {
                    $query->orderBy('urutan', 'asc');
                },
                'block' // Load the block relationship
            ])
            ->where('id_survey', $surveyUser->survey_id)
            ->orderBy('urutan')
            ->get()
            ->groupBy(function ($question) {
                return $question->block ? $question->block->nama : 'Tidak ada blok';
            })
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

            // Automatically send thank you email
            try {
                $survey = Survey::find($id);
                if ($survey) {
                    $this->emailService->sendThankYou(Auth::user(), $survey);
                }
            } catch (\Exception $e) {
                // Log error but don't fail the survey submission
                \Illuminate\Support\Facades\Log::error("Failed to send thank you email: " . $e->getMessage());
            }

            return redirect()->route('user.profile.index')->with('success', 'Jawaban survey berhasil disimpan');
        } catch (\Exception $e) {
            return redirect()->route('user.profile.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function sendEmail($id){
        try {
            $surveyUsers = SurveyUser::with(['user.alumni','user.atasan'])->where('survey_id', $id)->get();
            if($surveyUsers->isEmpty()) {
                return response()->json(['success'=>false, 'message'=>'Tidak ada pengguna yang terdaftar untuk survei ini.'], 400);
            }
            $template_pertanyaan = TemplatePertanyaan::getTemplatePertanyaan($id);
            if($template_pertanyaan->isEmpty()) {
                return response()->json(['success'=>false, 'message'=>'Tidak ada pertanyaan yang tersedia untuk survei ini.'], 400);
            }

            $survey = Survey::find($id);
            $users = $surveyUsers->pluck('user');
            
            // Send bulk invitations using the new service
            $result = $this->emailService->sendBulkInvitationsToCollection($users, $survey);
            
            return response()->json(['success'=>true, 'message'=>'Email berhasil dikirim ke semua!']);
        } catch (\Exception $e) {
            return response()->json(['success'=>false, 'message'=>'Gagal mengirim email: ' . $e->getMessage()], 500);
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
        // Enable detailed logging
        \Log::info('=== ADD USER FUNCTION CALLED ===');
        \Log::info('Request method: ' . $request->method());
        \Log::info('Request URL: ' . $request->url());
        \Log::info('Request all data: ', $request->all());
        \Log::info('Request headers: ', $request->headers->all());
        
        $userId = $request->input('user_id');
        $surveyId = $request->input('survey_id');
        
        // Debug logging
        \Log::info('Add User Debug', [
            'user_id' => $userId,
            'survey_id' => $surveyId,
            'request_data' => $request->all()
        ]);
        
        // Validate input
        if (!$userId || !$surveyId) {
            \Log::error('Missing parameters', [
                'user_id' => $userId,
                'survey_id' => $surveyId
            ]);
            return response()->json([
                'success' => false, 
                'message' => 'Parameter user_id dan survey_id diperlukan'
            ], 400);
        }
        
        try {
            // Check if user exists
            $user = \App\Models\User::find($userId);
            if (!$user) {
                \Log::error('User not found', ['user_id' => $userId]);
                return response()->json(['success' => false, 'message' => 'User not found']);
            }
            
            // Check if survey exists
            $survey = \App\Models\Survey::find($surveyId);
            if (!$survey) {
                \Log::error('Survey not found', ['survey_id' => $surveyId]);
                return response()->json(['success' => false, 'message' => 'Survey not found']);
            }
            
            // Check if already exists
            $existingSurveyUser = SurveyUser::where('user_id', $userId)
                ->where('survey_id', $surveyId)
                ->first();
                
            if ($existingSurveyUser) {
                \Log::info('User already in survey', ['survey_user_id' => $existingSurveyUser->id]);
                return response()->json(['success' => false, 'message' => 'User sudah terdaftar dalam survey ini']);
            }
            
            $surveyUser = SurveyUser::create([
                'user_id' => $userId,
                'survey_id' => $surveyId,
                'status' => 0
            ]);
            
            \Log::info('SurveyUser created successfully', ['survey_user_id' => $surveyUser->id]);
            
            return response()->json([
                'success' => true, 
                'message' => 'User berhasil ditambahkan',
                'survey_user_id' => $surveyUser->id,
                'debug_info' => [
                    'user_name' => $user->name,
                    'survey_name' => $survey->nama,
                    'created_at' => $surveyUser->created_at
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Add user error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
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

    /**
     * Send reminder emails to users who haven't completed the survey
     */
    public function sendReminders($id)
    {
        try {
            $survey = Survey::find($id);
            if (!$survey) {
                return response()->json(['success'=>false, 'message'=>'Survei tidak ditemukan.'], 404);
            }

            // Get users who haven't completed the survey (status = false)
            $incompleteUsers = SurveyUser::with('user')
                ->where('survey_id', $id)
                ->where('status', false)
                ->get()
                ->pluck('user');

            if($incompleteUsers->isEmpty()) {
                return response()->json(['success'=>false, 'message'=>'Semua pengguna sudah mengisi survei.'], 400);
            }

            // Send bulk reminders using the new service
            $result = $this->emailService->sendBulkRemindersToCollection($incompleteUsers, $survey);
            
            return response()->json(['success'=>true, 'message'=>'Email reminder berhasil dikirim!']);
        } catch (\Exception $e) {
            return response()->json(['success'=>false, 'message'=>'Gagal mengirim reminder: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Send thank you email after survey completion
     */
    public function sendThankYou($surveyUserId)
    {
        try {
            $surveyUser = SurveyUser::with(['user', 'user.alumni', 'user.atasan'])
                ->find($surveyUserId);
            
            if (!$surveyUser) {
                return response()->json(['success'=>false, 'message'=>'Data survei pengguna tidak ditemukan.'], 404);
            }

            $survey = Survey::find($surveyUser->survey_id);
            if (!$survey) {
                return response()->json(['success'=>false, 'message'=>'Survei tidak ditemukan.'], 404);
            }

            // Send thank you email
            $result = $this->emailService->sendThankYou($surveyUser->user, $survey);
            
            if ($result) {
                return response()->json(['success'=>true, 'message'=>'Email terima kasih berhasil dikirim!']);
            } else {
                return response()->json(['success'=>false, 'message'=>'Gagal mengirim email terima kasih.'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['success'=>false, 'message'=>'Gagal mengirim email terima kasih: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Send bulk thank you emails to all users who have completed the survey
     */
    public function sendBulkThankYou($surveyId)
    {
        try {
            $survey = Survey::find($surveyId);
            if (!$survey) {
                return response()->json(['success'=>false, 'message'=>'Survei tidak ditemukan.'], 404);
            }

            // Get users who have completed the survey (status = true)
            $completedUsers = SurveyUser::with('user')
                ->where('survey_id', $surveyId)
                ->where('status', true)
                ->get()
                ->pluck('user');

            if($completedUsers->isEmpty()) {
                return response()->json(['success'=>false, 'message'=>'Tidak ada pengguna yang telah menyelesaikan survei.'], 400);
            }

            // Send bulk thank you emails using the service
            $result = $this->emailService->sendBulkThankYouToCollection($completedUsers, $survey);
            
            return response()->json([
                'success'=>true, 
                'message'=>"Email terima kasih berhasil dikirim ke {$result['success']} pengguna!"
            ]);
        } catch (\Exception $e) {
            return response()->json(['success'=>false, 'message'=>'Gagal mengirim email terima kasih: ' . $e->getMessage()], 500);
        }
    }

    public function add_alumni_by_graduation_year(Request $request)
    {
        // Enable detailed logging
        \Log::info('=== ADD ALUMNI BY GRADUATION YEAR FUNCTION CALLED ===');
        \Log::info('Request method: ' . $request->method());
        \Log::info('Request URL: ' . $request->url());
        \Log::info('Request all data: ', $request->all());
        
        $graduationYear = $request->input('tahun_lulus');
        $surveyId = $request->input('survey_id');
        
        // Debug logging
        \Log::info('Add Alumni by Graduation Year Debug', [
            'tahun_lulus' => $graduationYear,
            'survey_id' => $surveyId,
            'request_data' => $request->all()
        ]);
        
        try {
            // Check if survey exists
            $survey = \App\Models\Survey::find($surveyId);
            if (!$survey) {
                \Log::error('Survey not found', ['survey_id' => $surveyId]);
                return response()->json([
                    'success' => false, 
                    'message' => 'Survey tidak ditemukan'
                ]);
            }
            
            // Get all alumni with the specified graduation year
            $alumni = \App\Models\Alumni::where('tahun_lulus', $graduationYear)
                ->whereHas('user') // Make sure they have associated user accounts
                ->get();
                
            \Log::info('Alumni found', ['count' => $alumni->count()]);
            
            if ($alumni->isEmpty()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Tidak ada alumni yang lulus pada tahun ' . $graduationYear
                ]);
            }
            
            $addedCount = 0;
            $skippedCount = 0;
            
            foreach ($alumni as $alumnus) {
                // Check if user is already in this survey
                $existingSurveyUser = SurveyUser::where('user_id', $alumnus->user_id)
                    ->where('survey_id', $surveyId)
                    ->first();
                
                if (!$existingSurveyUser) {
                    $surveyUser = SurveyUser::create([
                        'user_id' => $alumnus->user_id,
                        'survey_id' => $surveyId,
                        'status' => 0
                    ]);
                    \Log::info('Alumni added to survey', [
                        'user_id' => $alumnus->user_id, 
                        'survey_user_id' => $surveyUser->id
                    ]);
                    $addedCount++;
                } else {
                    \Log::info('Alumni already in survey', [
                        'user_id' => $alumnus->user_id, 
                        'existing_survey_user_id' => $existingSurveyUser->id
                    ]);
                    $skippedCount++;
                }
            }
            
            $message = "Berhasil menambahkan {$addedCount} alumni dari tahun lulus {$graduationYear}";
            if ($skippedCount > 0) {
                $message .= ". {$skippedCount} alumni sudah terdaftar dalam survey ini.";
            }
            
            \Log::info('Bulk add alumni result', [
                'added_count' => $addedCount,
                'skipped_count' => $skippedCount
            ]);
            
            return response()->json([
                'success' => true, 
                'message' => $message,
                'added_count' => $addedCount,
                'skipped_count' => $skippedCount
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Add alumni by graduation year error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false, 
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function get_graduation_years()
    {
        try {
            $years = \App\Models\Alumni::whereNotNull('tahun_lulus')
                ->where('tahun_lulus', '!=', '')
                ->distinct()
                ->orderBy('tahun_lulus', 'desc')
                ->pluck('tahun_lulus')
                ->filter() // Remove any null or empty values
                ->values(); // Reset array keys
            
            return response()->json([
                'success' => true,
                'years' => $years
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
}
