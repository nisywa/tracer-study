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
use Illuminate\Support\Facades\Log;
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

        $user = Auth::user();

        return view('user.views.index', [
            'survey' => $surveys,
            'user' => $user
        ]);
    }

    /**
     * Update user profile information (alumni or atasan)
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'jabatan' => 'nullable|string|max:255',
            'satuan_kerja' => 'nullable|string|max:255',
            'unit_kerja' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();

        try {
            if ($user->hasRole('alumni') && $user->alumni) {
                $user->alumni->update([
                    'jabatan' => $request->jabatan,
                    'satuan_kerja' => $request->satuan_kerja,
                    'unit_kerja' => $request->unit_kerja,
                    'no_hp' => $request->no_hp,
                ]);
            } elseif ($user->hasRole('atasan') && $user->atasan) {
                $user->atasan->update([
                    'jabatan' => $request->jabatan,
                    'satuan_kerja' => $request->satuan_kerja,
                    'unit_kerja' => $request->unit_kerja,
                    'no_hp' => $request->no_hp,
                ]);
            } else {
                return back()->with('error', 'Profile tidak ditemukan.');
            }

            return back()->with('success', 'Informasi user berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
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

        // Get survey data organized by blocks for the new flow
        $surveyBlocks = \App\Models\SurveyBlock::with([
            'questions' => function ($query) {
                $query->with(['templateJawaban' => function ($q) {
                    $q->select('id', 'id_template_pertanyaan', 'pilihan_jawaban', 'urutan', 'navigation_target')
                      ->orderBy('urutan');
                }])->orderBy('urutan');
            }
        ])
        ->where('survey_id', $surveyUser->survey_id)
        ->orderBy('urutan')
        ->get();

        // If no blocks exist, fall back to the old grouping method for backward compatibility
        if ($surveyBlocks->isEmpty()) {
            $surveyUserPertanyaan = TemplatePertanyaan::with([
                    'templateJawaban' => function ($query) {
                        $query->select('id', 'id_template_pertanyaan', 'pilihan_jawaban', 'urutan', 'navigation_target')
                              ->orderBy('urutan', 'asc');
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
        } else {
            // New block-based structure with full block information
            $surveyUserPertanyaan = $surveyBlocks->mapWithKeys(function ($block) {
                return [$block->nama => $block->questions->map(function ($question) {
                    // Ensure templateJawaban is included with navigation targets
                    $questionArray = $question->toArray();
                    
                    // Log navigation targets for debugging
                    if ($question->tipe === 'radio' && $question->templateJawaban) {
                        Log::info('Radio question navigation data', [
                            'question_id' => $question->id,
                            'question_text' => $question->pertanyaan,
                            'options' => $question->templateJawaban->map(function ($option) {
                                return [
                                    'id' => $option->id,
                                    'text' => $option->pilihan_jawaban,
                                    'navigation_target' => $option->navigation_target
                                ];
                            })->toArray()
                        ]);
                    }
                    
                    return $questionArray;
                })];
            });
            
            // Also pass the full block structure for navigation
            $blockStructure = $surveyBlocks->map(function ($block) {
                return [
                    'id' => $block->id,
                    'nama' => $block->nama,
                    'urutan' => $block->urutan,
                    'deskripsi' => $block->deskripsi,
                    'navigation_type' => $block->navigation_type
                ];
            });
            
            Log::info('Block structure created', [
                'survey_id' => $surveyUser->survey_id,
                'block_count' => $blockStructure->count(),
                'blocks' => $blockStructure->toArray()
            ]);
        }

        // Get existing answers for this user
        $existingAnswers = SurveyUserJawaban::where('survey_user_id', $surveyUser->id)
            ->pluck('jawaban', 'template_pertanyaan_id')
            ->toArray();

        return view('user.views.survey', [
            'survey' => $survey,
            'surveyPertanyaan' => $surveyUserPertanyaan,
            'existingAnswers' => $existingAnswers,
            'surveyBlocks' => $surveyBlocks,
            'blockStructure' => $blockStructure ?? null
        ]);
    }

    public function saveSurvey(Request $request, $id)
    {
        try {
            // Get current user's survey assignment
            $surveyUser = SurveyUser::where('user_id', Auth::id())->where('survey_id', $id)->first();
            if (!$surveyUser) {
                return response()->json(['success' => false, 'message' => 'Survey tidak ditemukan'], 404);
            }

            // Process each question response
            foreach ($request->except('_token') as $questionId => $answer) {
                // Skip non-answer fields
                if (strpos($questionId, 'answer_') !== 0) {
                    continue;
                }
                
                // Extract actual question ID
                $actualQuestionId = str_replace('answer_', '', $questionId);
                
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
                        'template_pertanyaan_id' => $actualQuestionId
                    ],
                    [
                        'jawaban' => $answer
                    ]
                );
            }

            // Update status survey user
            $surveyUser->status = 1;
            $surveyUser->tanggal_mengisi = now();
            $surveyUser->save();

            // Automatically send thank you email
            try {
                $survey = Survey::find($id);
                if ($survey) {
                    $this->emailService->sendThankYou(Auth::user(), $survey);
                }
            } catch (\Exception $e) {
                // Log error but don't fail the survey submission
                Log::error("Failed to send thank you email: " . $e->getMessage());
            }

            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Survey berhasil diselesaikan',
                    'redirect' => route('user.profile.index')
                ]);
            }

            return redirect()->route('user.profile.index')->with('success', 'Jawaban survey berhasil disimpan');
        } catch (\Exception $e) {
            Log::error('Error saving survey: ' . $e->getMessage(), [
                'survey_id' => $id,
                'user_id' => Auth::id(),
                'request_data' => $request->all()
            ]);

            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

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
        Log::info('=== ADD USER FUNCTION CALLED ===');
        Log::info('Request method: ' . $request->method());
        Log::info('Request URL: ' . $request->url());
        Log::info('Request all data: ', $request->all());
        Log::info('Request headers: ', $request->headers->all());
        
        $userId = $request->input('user_id');
        $surveyId = $request->input('survey_id');
        
        // Debug logging
        Log::info('Add User Debug', [
            'user_id' => $userId,
            'survey_id' => $surveyId,
            'request_data' => $request->all()
        ]);
        
        // Validate input
        if (!$userId || !$surveyId) {
            Log::error('Missing parameters', [
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
                Log::error('User not found', ['user_id' => $userId]);
                return response()->json(['success' => false, 'message' => 'User not found']);
            }
            
            // Check if survey exists
            $survey = \App\Models\Survey::find($surveyId);
            if (!$survey) {
                Log::error('Survey not found', ['survey_id' => $surveyId]);
                return response()->json(['success' => false, 'message' => 'Survey not found']);
            }
            
            // Check if already exists
            $existingSurveyUser = SurveyUser::where('user_id', $userId)
                ->where('survey_id', $surveyId)
                ->first();
                
            if ($existingSurveyUser) {
                Log::info('User already in survey', ['survey_user_id' => $existingSurveyUser->id]);
                return response()->json(['success' => false, 'message' => 'User sudah terdaftar dalam survey ini']);
            }
            
            $surveyUser = SurveyUser::create([
                'user_id' => $userId,
                'survey_id' => $surveyId,
                'status' => 0
            ]);
            
            Log::info('SurveyUser created successfully', ['survey_user_id' => $surveyUser->id]);
            
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
            Log::error('Add user error', [
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
        Log::info('=== ADD ALUMNI BY GRADUATION YEAR FUNCTION CALLED ===');
        Log::info('Request method: ' . $request->method());
        Log::info('Request URL: ' . $request->url());
        Log::info('Request all data: ', $request->all());
        
        $graduationYear = $request->input('tahun_lulus');
        $surveyId = $request->input('survey_id');
        
        // Debug logging
        Log::info('Add Alumni by Graduation Year Debug', [
            'tahun_lulus' => $graduationYear,
            'survey_id' => $surveyId,
            'request_data' => $request->all()
        ]);
        
        try {
            // Check if survey exists
            $survey = \App\Models\Survey::find($surveyId);
            if (!$survey) {
                Log::error('Survey not found', ['survey_id' => $surveyId]);
                return response()->json([
                    'success' => false, 
                    'message' => 'Survey tidak ditemukan'
                ]);
            }
            
            // Get all alumni with the specified graduation year
            $alumni = \App\Models\Alumni::where('tahun_lulus', $graduationYear)
                ->whereHas('user') // Make sure they have associated user accounts
                ->get();
                
            Log::info('Alumni found', ['count' => $alumni->count()]);
            
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
                    Log::info('Alumni added to survey', [
                        'user_id' => $alumnus->user_id, 
                        'survey_user_id' => $surveyUser->id
                    ]);
                    $addedCount++;
                } else {
                    Log::info('Alumni already in survey', [
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
            
            Log::info('Bulk add alumni result', [
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
            Log::error('Add alumni by graduation year error', [
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

    /**
     * Get next question based on survey flow and branching rules
     */
    public function getNextQuestion(Request $request, $surveyId, $currentQuestionId)
    {
        Log::info('=== GET NEXT QUESTION API CALLED ===', [
            'survey_id' => $surveyId,
            'current_question_id' => $currentQuestionId,
            'request_data' => $request->all(),
            'user_id' => Auth::id()
        ]);

        // Debug: Check if this ID exists as question or block
        $questionExists = TemplatePertanyaan::where('id', $currentQuestionId)->exists();
        $blockExists = \App\Models\SurveyBlock::where('id', $currentQuestionId)->exists();
        
        Log::info('ID collision check', [
            'id' => $currentQuestionId,
            'exists_as_question' => $questionExists,
            'exists_as_block' => $blockExists
        ]);

        try {
            $surveyUser = SurveyUser::where('user_id', Auth::id())
                ->where('survey_id', $surveyId)
                ->first();
                
            if (!$surveyUser) {
                Log::error('Survey user not found', [
                    'user_id' => Auth::id(),
                    'survey_id' => $surveyId
                ]);
                return response()->json(['error' => 'Survey assignment not found'], 404);
            }

            $currentQuestion = TemplatePertanyaan::with(['templateJawaban', 'block'])
                ->where('id', $currentQuestionId)
                ->first();
                
            if (!$currentQuestion) {
                Log::error('Current question not found', [
                    'question_id' => $currentQuestionId
                ]);
                return response()->json(['error' => 'Current question not found'], 404);
            }

            // Get the answer from the request
            $answer = $request->input('answer');
            
            Log::info('Processing next question', [
                'current_question_id' => $currentQuestion->id,
                'question_text' => substr($currentQuestion->pertanyaan, 0, 100),
                'question_type' => $currentQuestion->tipe,
                'block_id' => $currentQuestion->block_id,
                'block_name' => $currentQuestion->block ? $currentQuestion->block->nama : null,
                'answer' => $answer
            ]);

            // Save the current answer before proceeding
            if ($answer !== null && $answer !== '') {
                $this->saveUserAnswer($surveyUser, $currentQuestion, $answer);
                
                // Update current question ID for progress tracking
                $surveyUser->current_question_id = $currentQuestion->id;
                $surveyUser->save();
            }

            // Get the next question using branching logic
            $nextQuestionData = $this->determineNextQuestion($currentQuestion, $answer, $surveyId);
            
            if ($nextQuestionData === null) {
                // Survey is completed, update status
                $surveyUser->status = 1;
                $surveyUser->tanggal_mengisi = now();
                $surveyUser->save();
                
                Log::info('Survey completed for user', [
                    'survey_user_id' => $surveyUser->id,
                    'survey_id' => $surveyId,
                    'user_id' => Auth::id()
                ]);
                
                // Send thank you email
                try {
                    $survey = Survey::find($surveyId);
                    if ($survey) {
                        $this->emailService->sendThankYou(Auth::user(), $survey);
                        Log::info('Thank you email sent', ['user_id' => Auth::id(), 'survey_id' => $surveyId]);
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to send thank you email', [
                        'error' => $e->getMessage(),
                        'user_id' => Auth::id(),
                        'survey_id' => $surveyId
                    ]);
                }
                
                return response()->json(['completed' => true]);
            }
            
            // Format response for frontend
            $response = [
                'question' => $nextQuestionData,
                'block' => $nextQuestionData->block
            ];
            
            Log::info('Returning next question response', [
                'question_id' => $nextQuestionData->id,
                'block_id' => $nextQuestionData->block ? $nextQuestionData->block->id : null,
                'block_name' => $nextQuestionData->block ? $nextQuestionData->block->nama : null
            ]);
            
            return response()->json($response);
            
        } catch (\Exception $e) {
            Log::error('Error in getNextQuestion', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Internal server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Determine the next question based on branching rules and flow
     */
    private function determineNextQuestion($currentQuestion, $answer, $surveyId)
    {
        Log::info('Determining next question', [
            'current_question_id' => $currentQuestion->id,
            'answer' => $answer,
            'question_type' => $currentQuestion->tipe
        ]);
        
        // First check for branching rules based on the answer
        $targetBlockResult = $this->checkBranchingRules($currentQuestion, $answer);
        
        Log::info('Branching rules result', [
            'target_block_result' => $targetBlockResult
        ]);
        
        if ($targetBlockResult === 'end') {
            // User should end the survey
            Log::info('Survey should end based on branching rule');
            return null;
        }
        
        if ($targetBlockResult && is_numeric($targetBlockResult)) {
            // Jump to the target block's first question
            Log::info('Jumping to target block', ['target_block_id' => $targetBlockResult]);
            
            $nextQuestion = TemplatePertanyaan::with(['templateJawaban', 'block'])
                ->where('block_id', $targetBlockResult)
                ->orderBy('urutan')
                ->first();
                
            if ($nextQuestion) {
                Log::info('Found question in target block', ['next_question_id' => $nextQuestion->id]);
            }
            
            return $nextQuestion;
        }

        // No branching rule matched, follow normal flow
        // Try to get next question in the same block
        $nextQuestionInBlock = TemplatePertanyaan::with(['templateJawaban', 'block'])
            ->where('id_survey', $surveyId)
            ->where('block_id', $currentQuestion->block_id)
            ->where('urutan', '>', $currentQuestion->urutan)
            ->orderBy('urutan')
            ->first();

        if ($nextQuestionInBlock) {
            Log::info('Found next question in same block', ['next_question_id' => $nextQuestionInBlock->id]);
            return $nextQuestionInBlock;
        }

        // No more questions in current block, move to next block
        $currentBlock = $currentQuestion->block;
        if (!$currentBlock) {
            Log::info('No current block found');
            return null;
        }

        $nextBlock = \App\Models\SurveyBlock::where('survey_id', $surveyId)
            ->where('urutan', '>', $currentBlock->urutan)
            ->orderBy('urutan')
            ->first();

        if (!$nextBlock) {
            Log::info('No more blocks found');
            return null; // No more blocks
        }

        if ($nextBlock->is_terminal) {
            Log::info('Next block is terminal');
            return null; // Terminal block reached
        }

        // Get first question of next block
        $firstQuestionOfNextBlock = TemplatePertanyaan::with(['templateJawaban', 'block'])
            ->where('block_id', $nextBlock->id)
            ->orderBy('urutan')
            ->first();

        if ($firstQuestionOfNextBlock) {
            Log::info('Found first question of next block', [
                'next_block_id' => $nextBlock->id,
                'next_question_id' => $firstQuestionOfNextBlock->id
            ]);
        }

        return $firstQuestionOfNextBlock;
    }

    /**
     * Check branching rules for the current question and answer
     */
    private function checkBranchingRules($question, $answer)
    {
        Log::info('Checking branching rules', [
            'question_id' => $question->id,
            'question_type' => $question->tipe,
            'answer' => $answer
        ]);
        
        // Only radio and select questions can have navigation rules
        if (!in_array($question->tipe, ['radio', 'select']) || !$answer) {
            Log::info('No branching rules: not radio/select or no answer');
            return null;
        }

        // Find the selected option
        $selectedOption = $question->templateJawaban->where('id', $answer)->first();
        if (!$selectedOption) {
            Log::info('No selected option found', ['option_id' => $answer]);
            return null;
        }

        Log::info('Selected option found', [
            'option_id' => $selectedOption->id,
            'option_text' => $selectedOption->pilihan_jawaban,
            'navigation_target' => $selectedOption->navigation_target
        ]);

        if (!$selectedOption->navigation_target) {
            Log::info('No navigation target set for this option');
            return null;
        }

        $navigationTarget = $selectedOption->navigation_target;

        // Handle different navigation target formats
        if ($navigationTarget === 'end') {
            // Signal to end the survey
            Log::info('Navigation target is end survey');
            return 'end';
        } elseif ($navigationTarget === 'next') {
            // Continue normal flow
            Log::info('Navigation target is next (normal flow)');
            return null;
        } elseif (is_numeric($navigationTarget)) {
            // Navigation target is already a block ID
            Log::info('Navigation target is block ID', ['block_id' => $navigationTarget]);
            
            // Verify the block exists
            $targetBlock = \App\Models\SurveyBlock::where('id', $navigationTarget)
                ->where('survey_id', $question->id_survey)
                ->first();
            
            if ($targetBlock) {
                Log::info('Target block found by ID', [
                    'block_id' => $targetBlock->id,
                    'block_name' => $targetBlock->nama
                ]);
                return (int) $targetBlock->id;
            } else {
                Log::warning('Target block not found by ID', ['block_id' => $navigationTarget]);
            }
        } elseif (strpos($navigationTarget, 'block_') === 0) {
            // Extract block number from 'block_X' format (legacy support)
            $blockNumber = (int) substr($navigationTarget, 6);
            Log::info('Navigation target is specific block (legacy format)', ['block_number' => $blockNumber]);
            
            // Find the block by its order (urutan) 
            $targetBlock = \App\Models\SurveyBlock::where('survey_id', $question->id_survey)
                ->where('urutan', $blockNumber)
                ->first();
            
            if ($targetBlock) {
                Log::info('Target block found by order', [
                    'block_id' => $targetBlock->id,
                    'block_name' => $targetBlock->nama
                ]);
                return $targetBlock->id;
            } else {
                Log::warning('Target block not found by order', ['block_number' => $blockNumber]);
            }
        }

        Log::info('No matching navigation rule found');
        return null;
    }

    /**
     * Save user answer for the current question
     */
    private function saveUserAnswer($surveyUser, $currentQuestion, $answer)
    {
        // Handle different answer formats
        $answerValue = $answer;
        
        // For checkbox answers (array), convert to comma-separated string
        if (is_array($answer)) {
            $answerValue = implode(',', $answer);
        }
        
        // Save or update the answer
        SurveyUserJawaban::updateOrCreate(
            [
                'survey_user_id' => $surveyUser->id,
                'template_pertanyaan_id' => $currentQuestion->id
            ],
            [
                'jawaban' => $answerValue
            ]
        );
        
        Log::info('User answer saved', [
            'survey_user_id' => $surveyUser->id,
            'question_id' => $currentQuestion->id,
            'question_text' => substr($currentQuestion->pertanyaan, 0, 50),
            'answer' => $answerValue
        ]);
    }

    // ...existing methods...
}
