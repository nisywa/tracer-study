<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>
        Tracer Study
    </title>
    <link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}" type="image/x-icon" />
    <link rel="stylesheet" href="{{ asset('assets/css/swiper-bundle.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/tailwind.css') }}" />

    <!-- ==== WOW JS ==== -->
    <script src="{{ asset('assets/js/wow.min.js') }}"></script>
    <script>
        new WOW().init();
    </script>
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <style>
        .question-box {
            background-color: #f0f9ff !important; /* Light blue for light theme */
        }
        
        .dark .question-box {
            background-color: var(--dark-3) !important; /* Respect dark theme */
        }
        
        html.dark .question-box {
            background-color: #374151 !important; /* Dark gray for dark theme */
        }
        
        html .submit-btn, 
        body .submit-btn,
        .submit-btn {
            background-color: #2563eb !important; /* blue-600 */
            color: #ffffff !important;
            border: 2px solid #2563eb !important;
        }
        
        html .submit-btn:hover,
        body .submit-btn:hover, 
        .submit-btn:hover {
            background-color: #1d4ed8 !important; /* blue-700 */
            border-color: #1d4ed8 !important;
        }
        
        html .submit-btn:focus,
        body .submit-btn:focus,
        .submit-btn:focus {
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.3) !important;
            outline: none !important;
        }
        
        html.dark .submit-btn,
        body.dark .submit-btn,
        .dark .submit-btn {
            background-color: #f3f4f6 !important; /* gray-100 */
            color: #111827 !important; /* gray-900 */
            border: 2px solid #f3f4f6 !important;
        }
        
        html.dark .submit-btn:hover,
        body.dark .submit-btn:hover,
        .dark .submit-btn:hover {
            background-color: #e5e7eb !important; /* gray-200 */
            border-color: #e5e7eb !important;
        }
        
        html.dark .submit-btn:focus,
        body.dark .submit-btn:focus,
        .dark .submit-btn:focus {
            box-shadow: 0 0 0 3px rgba(156, 163, 175, 0.3) !important;
        }
    </style>
</head>

<body>

    <!-- Preloader -->
    <div class="preloader">
        <div class="preloader-inner">
            <div class="preloader-icon">
                <span></span>
                <span></span>
            </div>
        </div>
    </div>
    <!-- /End Preloader -->

    @include('user.layouts.navigation')


    @yield('content')


    <!-- ====== form question Start (Full Page) ====== -->
    <section id="contact" class="relative min-h-screen pt-[80px] dark:bg-dark">
        <div class="absolute top-0 left-0 -z-[1] w-full dark:bg-dark h-full bg-white"></div>
        <div class="w-full h-full">
            <div class="flex justify-center items-start min-h-screen">
                <!-- Survey Header -->
                <div class="w-full max-w-4xl mx-auto px-4 py-8">
                    <div class="text-center mb-8 pt-12 sm:pt-16 md:pt-20 lg:pt-24">
                        <h1 class="mb-4 text-3xl font-bold text-dark dark:text-white sm:text-4xl md:text-[40px] md:leading-[1.2]">
                            {{ $survey->nama }}
                        </h1>
                        <p class="mb-3 text-base text-body-color dark:text-dark-6">
                            {{ $survey->tanggal_mulai . '--' . $survey->tanggal_selesai }}
                        </p>
                        <p class="mb-6 text-base text-body-color dark:text-dark-6">
                            Silakan isi formulir di bawah ini dengan informasi yang sesuai.
                        </p>
                    </div>
                                    <!-- Survey Form -->
                    <div x-data="surveyFlow()" class="w-full">
                        <!-- Progress Bar -->
                        <div class="bg-white dark:bg-dark-2 rounded-lg shadow-lg p-4 mb-6">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-blue-800 dark:text-white">Progres Survey</span>
                                <span class="text-sm text-blue-600 dark:text-dark-6" x-text="Math.round(progress) + '%'"></span>
                            </div>
                            <div class="w-full bg-blue-100 dark:bg-dark-3 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" 
                                     :style="'width: ' + progress + '%'"></div>
                            </div>
                        </div>

                        <!-- Survey Content -->
                        <form @submit.prevent="handleSubmit" method="POST" class="w-full" x-show="!isCompleted">
                            @csrf
                            <div class="bg-white dark:bg-dark-2 rounded-lg shadow-lg p-6 md:p-8 lg:p-10">
                                <!-- Current Block Header -->
                                <div class="mb-8" x-show="currentBlock">
                                    <h3 class="mb-6 text-xl font-semibold md:text-2xl text-blue-800 dark:text-white border-b border-blue-200 dark:border-dark-3 pb-3" 
                                        x-text="currentBlock?.nama || 'Loading...'">
                                    </h3>
                                    <p class="text-sm text-blue-600 dark:text-dark-6" x-text="currentBlock?.deskripsi || ''"></p>
                                </div>

                                <!-- Current Question -->
                                <template x-for="question in currentQuestions" :key="question.id">
                                    <div class="mb-8 p-4 bg-sky-50 border border-sky-100 dark:bg-dark-3 dark:border-dark-3 rounded-lg shadow-sm question-box"
                                         x-show="currentQuestionIndex >= 0 && question.id === currentQuestions[currentQuestionIndex]?.id">
                                        
                                        <!-- Question Title -->
                                        <label :for="'question_' + question.id"
                                               class="font-semibold block mb-3 text-base text-blue-900 dark:text-white"
                                               x-text="question.pertanyaan"></label>
                                        
                                        <!-- Question Description -->
                                        <label :for="'question_' + question.id"
                                               class="block mb-4 text-sm text-blue-700 dark:text-dark-6"
                                               x-text="question.deskripsi_pertanyaan || ''"></label>

                                        <!-- Question Input Based on Type -->
                                        <div x-show="question.tipe === 'text'">
                                            <input type="text" 
                                                   :name="'answer_' + question.id"
                                                   :id="'question_' + question.id"
                                                   x-model="answers[question.id]"
                                                   class="w-full p-3 text-blue-800 dark:text-dark-6 bg-white dark:bg-dark-2 border border-blue-200 dark:border-dark-3 rounded-md focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:focus:border-primary dark:focus:ring-primary/20" />
                                        </div>

                                        <div x-show="question.tipe === 'textarea'">
                                            <textarea :name="'answer_' + question.id"
                                                      :id="'question_' + question.id"
                                                      x-model="answers[question.id]"
                                                      rows="4"
                                                      class="w-full p-3 text-blue-800 dark:text-dark-6 bg-white dark:bg-dark-2 border border-blue-200 dark:border-dark-3 rounded-md focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:focus:border-primary dark:focus:ring-primary/20"></textarea>
                                        </div>

                                        <div x-show="question.tipe === 'number'">
                                            <input type="number" 
                                                   :name="'answer_' + question.id"
                                                   :id="'question_' + question.id"
                                                   x-model="answers[question.id]"
                                                   class="w-full p-3 text-blue-800 dark:text-dark-6 bg-white dark:bg-dark-2 border border-blue-200 dark:border-dark-3 rounded-md focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:focus:border-primary dark:focus:ring-primary/20" />
                                        </div>

                                        <div x-show="question.tipe === 'radio'">
                                            <div class="flex flex-col gap-y-3 mb-2 text-blue-700 dark:text-dark-6">
                                                <template x-for="option in question.template_jawaban" :key="option.id">
                                                    <label class="flex items-center gap-x-3 p-2 hover:bg-blue-100 dark:hover:bg-dark-2 rounded cursor-pointer transition-colors"
                                                           @click="selectRadioOption(question.id, option.id, option.pilihan_jawaban)">
                                                        <input type="radio" 
                                                               :name="'answer_' + question.id"
                                                               :value="option.id"
                                                               x-model="answers[question.id]"
                                                               class="text-blue-600 focus:ring-blue-500 dark:text-primary dark:focus:ring-primary">
                                                        <span x-text="option.pilihan_jawaban"></span>
                                                    </label>
                                                </template>
                                            </div>
                                        </div>

                                        <div x-show="question.tipe === 'select'">
                                            <select :name="'answer_' + question.id"
                                                    :id="'question_' + question.id"
                                                    x-model="answers[question.id]"
                                                    @change="handleSelectChange(question.id, $event.target.value)"
                                                    class="w-full p-3 text-blue-800 dark:text-dark-6 bg-white dark:bg-dark-2 border border-blue-200 dark:border-dark-3 rounded-md focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:focus:border-primary dark:focus:ring-primary/20">
                                                <option value="" disabled selected>Choose</option>
                                                <template x-for="option in question.template_jawaban" :key="option.id">
                                                    <option :value="option.id" x-text="option.pilihan_jawaban"></option>
                                                </template>
                                            </select>
                                        </div>

                                        <div x-show="question.tipe === 'checkbox'">
                                            <div class="space-y-2">
                                                <template x-for="option in question.template_jawaban" :key="option.id">
                                                    <label :for="'option_' + question.id + '_' + option.id"
                                                           class="flex items-center text-sm text-blue-700 dark:text-dark-6 p-2 hover:bg-blue-100 dark:hover:bg-dark-2 rounded cursor-pointer transition-colors">
                                                        <input type="checkbox" 
                                                               :id="'option_' + question.id + '_' + option.id"
                                                               :name="'answer_' + question.id + '[]'"
                                                               :value="option.id"
                                                               @change="handleCheckboxChange(question.id, option.id, $event.target.checked)"
                                                               class="w-4 h-4 text-blue-600 border border-blue-300 dark:border-dark-3 focus:ring-blue-500 dark:text-primary dark:focus:ring-primary rounded" />
                                                        <span class="ml-3" x-text="option.pilihan_jawaban"></span>
                                                    </label>
                                                </template>
                                            </div>
                                        </div>

                                        <div x-show="question.tipe === 'date'">
                                            <input type="date" 
                                                   :name="'answer_' + question.id"
                                                   :id="'question_' + question.id"
                                                   x-model="answers[question.id]"
                                                   class="w-full p-3 text-blue-800 dark:text-dark-6 bg-white dark:bg-dark-2 border border-blue-200 dark:border-dark-3 rounded-md focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:focus:border-primary dark:focus:ring-primary/20" />
                                        </div>

                                        <div x-show="question.tipe === 'file'">
                                            <input type="file" 
                                                   :name="'answer_' + question.id"
                                                   :id="'question_' + question.id"
                                                   @change="handleFileChange(question.id, $event)"
                                                   class="w-full p-3 text-blue-800 dark:text-dark-6 bg-white dark:bg-dark-2 border border-blue-200 dark:border-dark-3 rounded-md focus:border-blue-500 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-blue-600 file:text-white dark:file:bg-primary" />
                                        </div>
                                    </div>
                                </template>

                                <!-- Navigation Buttons -->
                                <div class="flex justify-between gap-4 mt-8 pt-6 border-t border-blue-200 dark:border-dark-3">
                                    <button type="button" 
                                            @click="previousQuestion()" 
                                            x-show="canGoPrevious"
                                            class="inline-flex items-center justify-center px-8 py-3 text-base font-medium text-blue-600 dark:text-dark-6 bg-blue-50 dark:bg-dark-3 hover:bg-blue-100 dark:hover:bg-dark-2 transition duration-300 ease-in-out rounded-md border border-blue-200 dark:border-dark-3">
                                        <i class="fas fa-arrow-left mr-2"></i>
                                        Sebelumnya
                                    </button>
                                    
                                    <div class="flex gap-4">
                                        <a href="/" class="inline-flex items-center justify-center px-8 py-3 text-base font-medium text-blue-600 dark:text-dark-6 bg-blue-50 dark:bg-dark-3 hover:bg-blue-100 dark:hover:bg-dark-2 transition duration-300 ease-in-out rounded-md border border-blue-200 dark:border-dark-3">
                                            Batal
                                        </a>
                                        
                                        <button type="button" 
                                                @click="nextQuestion()" 
                                                x-show="!isLastQuestion"
                                                :disabled="!canProceed"
                                                :class="canProceed ? 'submit-btn' : 'bg-gray-400 cursor-not-allowed'"
                                                class="inline-flex items-center justify-center px-8 py-3 text-base font-medium text-white transition duration-300 ease-in-out rounded-md">
                                            Selanjutnya
                                            <i class="fas fa-arrow-right ml-2"></i>
                                        </button>
                                        
                                        <button type="submit" 
                                                x-show="isLastQuestion"
                                                :disabled="!canProceed"
                                                :class="canProceed ? 'submit-btn' : 'bg-gray-400 cursor-not-allowed'"
                                                class="inline-flex items-center justify-center px-8 py-3 text-base font-medium text-white transition duration-300 ease-in-out rounded-md">
                                            Kirim Survey
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- Completion Message -->
                        <div x-show="isCompleted" class="text-center">
                            <div class="bg-green-50 border border-green-200 rounded-lg p-8">
                                <div class="text-green-600 mb-4">
                                    <i class="fas fa-check-circle text-4xl"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-green-800 mb-2">Survey Selesai!</h3>
                                <p class="text-green-700">Terima kasih atas partisipasi Anda dalam survey ini.</p>
                                <a href="/" class="inline-flex items-center justify-center px-6 py-2 mt-4 text-base font-medium text-white bg-green-600 hover:bg-green-700 transition duration-300 ease-in-out rounded-md">
                                    Kembali ke Beranda
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ====== Contact End ====== -->


    @include('user.layouts.footer')

    <!-- ====== Back To Top Start -->
    <a href="javascript:void(0)"
        class="back-to-top fixed bottom-8 left-auto right-8 z-[999] hidden h-10 w-10 items-center justify-center rounded-md bg-primary text-white shadow-md transition duration-300 ease-in-out hover:bg-dark">
        <span class="mt-[6px] h-3 w-3 rotate-45 border-l border-t border-white"></span>
    </a>
    <!-- ====== Back To Top End -->



    <!-- ====== All Scripts -->

    <script src="{{ asset('assets/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script>
        // Survey Flow Component
        function surveyFlow() {
            return {
                surveyId: {{ $survey->id }},
                surveyData: @json($surveyPertanyaan),
                blockStructure: @json($blockStructure ?? []),
                existingAnswers: @json($existingAnswers ?? []),
                allBlocks: [],
                currentBlockIndex: 0,
                currentBlock: null,
                currentQuestions: [],
                currentQuestionIndex: 0,
                answers: {},
                progress: 0,
                isCompleted: false,
                questionHistory: [], // Track navigation history for back button

                init() {
                    console.log('Survey Flow Initialized', this.surveyData);
                    this.loadSurveyStructure();
                    this.loadExistingAnswers();
                    this.startSurvey();
                },

                loadSurveyStructure() {
                    // Use block structure if available (new block-based surveys)
                    if (this.blockStructure && this.blockStructure.length > 0) {
                        console.log('Using block structure:', this.blockStructure);
                        this.allBlocks = this.blockStructure.map((block, index) => ({
                            id: block.id,
                            urutan: block.urutan,
                            nama: block.nama,
                            deskripsi: block.deskripsi,
                            questions: (this.surveyData[block.nama] || []).map(q => ({
                                ...q,
                                template_jawaban: q.template_jawaban || q.templateJawaban || []
                            }))
                        }));
                    } else {
                        // Fallback: Convert surveyData object to blocks array (legacy support)
                        console.log('Using legacy structure, surveyData:', this.surveyData);
                        this.allBlocks = Object.entries(this.surveyData).map(([blockName, questions], index) => ({
                            id: index + 1,
                            urutan: index + 1,
                            nama: blockName,
                            deskripsi: '',
                            questions: questions.map(q => ({
                                ...q,
                                template_jawaban: q.templateJawaban || q.template_jawaban || []
                            }))
                        }));
                    }
                    console.log('Loaded blocks with structure:', this.allBlocks);
                    
                    // Debug: Check if navigation targets are loaded
                    this.allBlocks.forEach(block => {
                        block.questions.forEach(question => {
                            if (question.tipe === 'radio' && question.template_jawaban && question.template_jawaban.length > 0) {
                                console.log(`Radio question "${question.pertanyaan}" has options:`, 
                                    question.template_jawaban.map(opt => ({
                                        text: opt.pilihan_jawaban,
                                        target: opt.navigation_target
                                    }))
                                );
                            }
                        });
                    });
                },

                startSurvey() {
                    if (this.allBlocks.length === 0) {
                        console.error('No blocks found');
                        return;
                    }
                    
                    this.currentBlockIndex = 0;
                    this.loadCurrentBlock();
                    this.updateProgress(); // Initialize progress at start
                },

                loadCurrentBlock() {
                    console.log('=== LOADING CURRENT BLOCK ===');
                    console.log('Current block index:', this.currentBlockIndex, 'Total blocks:', this.allBlocks.length);
                    
                    if (this.currentBlockIndex >= this.allBlocks.length) {
                        console.log('🏁 No more blocks, completing survey');
                        this.completeSurvey();
                        return;
                    }

                    this.currentBlock = this.allBlocks[this.currentBlockIndex];
                    this.currentQuestions = this.currentBlock.questions || [];
                    this.currentQuestionIndex = 0;
                    
                    console.log('✅ Block loaded:', {
                        blockName: this.currentBlock.nama,
                        blockId: this.currentBlock.id,
                        blockOrder: this.currentBlock.urutan,
                        questionsCount: this.currentQuestions.length,
                        firstQuestion: this.currentQuestions[0]?.pertanyaan || 'No questions'
                    });
                },

                async nextQuestion() {
                    // Save current answer
                    this.saveCurrentAnswer();
                    this.addToHistory();

                    const currentQuestion = this.currentQuestions[this.currentQuestionIndex];
                    if (!currentQuestion) {
                        return;
                    }

                    // Log current answer being sent
                    console.log('📤 Saving answer for question:', {
                        questionId: currentQuestion.id,
                        questionText: currentQuestion.pertanyaan,
                        answer: this.answers[currentQuestion.id],
                        answerType: typeof this.answers[currentQuestion.id]
                    });

                                        // Check for API-based branching if available
                    try {
                        console.log('🔄 Making API call to:', `/user/survey/${this.surveyId}/next-question/${currentQuestion.id}`);
                        console.log('📤 Sending answer:', this.answers[currentQuestion.id]);
                        
                        const response = await fetch(`/user/survey/${this.surveyId}/next-question/${currentQuestion.id}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            body: JSON.stringify({
                                answer: this.answers[currentQuestion.id]
                            })
                        });

                        console.log('📨 Response status:', response.status, response.statusText);

                        if (response.ok) {
                            const data = await response.json();
                            
                            console.log('📥 API response received:', data);
                            
                            if (data.completed) {
                                console.log('✅ Survey completed from API response');
                                this.completeSurvey();
                                return;
                            }

                            if (data.question && data.block) {
                                // API returned a specific next question, use server-side branching logic
                                console.log('✅ API provided next question:', data.question.id, 'in block:', data.block.nama);
                                console.log('📝 Full API response data:', data);
                                
                                // Check if this is the same question as current
                                const currentQuestion = this.currentQuestions[this.currentQuestionIndex];
                                if (currentQuestion && data.question.id === currentQuestion.id) {
                                    console.log('⚠️ API returned same question, continuing with normal flow instead');
                                    // Fall back to normal flow since navigation leads to same question
                                } else {
                                    this.jumpToSpecificQuestion(data.question, data.block);
                                    return;
                                }
                            }
                            
                            console.warn('❌ API response missing question or block data:', data);
                        } else {
                            const errorText = await response.text();
                            console.warn('❌ API response not ok:', response.status, response.statusText);
                            console.warn('❌ Error details:', errorText);
                        }
                    } catch (error) {
                        console.warn('API branching failed, using client-side logic:', error);
                    }

                    // Fallback to client-side branching logic
                    console.log('=== FALLBACK TO CLIENT-SIDE BRANCHING ===');
                    const nextBlockResult = this.checkBranchingRules(currentQuestion);
                    console.log('Branching result:', nextBlockResult);

                    if (nextBlockResult === 'end') {
                        // End the survey
                        console.log('🏁 ENDING SURVEY based on navigation rule');
                        this.completeSurvey();
                        return;
                    } else if (nextBlockResult && typeof nextBlockResult === 'number') {
                        // Jump to specific block
                        console.log('🎯 JUMPING TO SPECIFIC BLOCK:', nextBlockResult);
                        this.jumpToBlock(nextBlockResult);
                        return;
                    }

                    console.log('➡️ CONTINUING NORMAL FLOW');
                    // Move to next question in current block
                    if (this.currentQuestionIndex < this.currentQuestions.length - 1) {
                        console.log('Moving to next question in same block');
                        this.currentQuestionIndex++;
                    } else {
                        // Move to next block
                        console.log('Moving to next block');
                        this.currentBlockIndex++;
                        this.loadCurrentBlock();
                    }
                    
                    this.updateProgress();
                },

                previousQuestion() {
                    if (this.questionHistory.length > 0) {
                        const lastPosition = this.questionHistory.pop();
                        this.currentBlockIndex = lastPosition.blockIndex;
                        this.currentQuestionIndex = lastPosition.questionIndex;
                        this.loadCurrentBlock();
                        this.updateProgress();
                    }
                },

                jumpToSpecificQuestion(question, block) {
                    console.log('=== JUMPING TO SPECIFIC QUESTION ===');
                    console.log('Target question ID:', question.id);
                    console.log('Target block ID:', block.id, 'Name:', block.nama);
                    console.log('Available blocks:', this.allBlocks.map(b => ({ id: b.id, nama: b.nama })));
                    
                    // Find the block index by ID (more reliable than name)
                    const blockIndex = this.allBlocks.findIndex(b => b.id === block.id);
                    if (blockIndex === -1) {
                        console.error('Block not found by ID:', block.id);
                        console.log('Trying to find by name as fallback:', block.nama);
                        
                        // Fallback: try to find by name
                        const fallbackIndex = this.allBlocks.findIndex(b => b.nama === block.nama);
                        if (fallbackIndex === -1) {
                            console.error('Block not found by name either:', block.nama);
                            return;
                        }
                        
                        console.log('Found block by name fallback at index:', fallbackIndex);
                        this.currentBlockIndex = fallbackIndex;
                        this.currentBlock = this.allBlocks[fallbackIndex];
                    } else {
                        console.log('Found block by ID at index:', blockIndex);
                        this.currentBlockIndex = blockIndex;
                        this.currentBlock = this.allBlocks[blockIndex];
                    }
                    
                    // Check if we need to load questions for this block
                    if (!this.currentBlock.questions || this.currentBlock.questions.length === 0) {
                        console.log('Block has no questions loaded, using question from API response');
                        this.currentBlock.questions = [question];
                        this.currentQuestions = [question];
                        this.currentQuestionIndex = 0;
                    } else {
                        // Find question index within the block
                        const questionIndex = this.currentBlock.questions.findIndex(q => q.id === question.id);
                        if (questionIndex === -1) {
                            console.log('Question not found in block questions, adding it');
                            this.currentBlock.questions.push(question);
                            this.currentQuestions = this.currentBlock.questions;
                            this.currentQuestionIndex = this.currentBlock.questions.length - 1;
                        } else {
                            console.log('Found question at index:', questionIndex);
                            this.currentQuestions = this.currentBlock.questions;
                            this.currentQuestionIndex = questionIndex;
                        }
                    }
                    
                    console.log('Jump completed. Current block:', this.currentBlock.nama, 'Question:', this.currentQuestions[this.currentQuestionIndex]?.pertanyaan);
                    this.updateProgress();
                },

                jumpToBlock(blockId) {
                    console.log('=== JUMPING TO BLOCK ===');
                    console.log('Target block ID:', blockId);
                    console.log('Available blocks:', this.allBlocks.map(b => ({ id: b.id, nama: b.nama, urutan: b.urutan })));
                    
                    // Find block by ID
                    const blockIndex = this.allBlocks.findIndex(block => block.id === blockId);
                    
                    if (blockIndex !== -1) {
                        const targetBlock = this.allBlocks[blockIndex];
                        console.log('✅ Block found, jumping to:', {
                            blockId: blockId,
                            blockIndex: blockIndex,
                            blockName: targetBlock.nama,
                            blockOrder: targetBlock.urutan,
                            questionsCount: targetBlock.questions?.length || 0
                        });
                        
                        this.currentBlockIndex = blockIndex;
                        this.loadCurrentBlock();
                        this.updateProgress();
                        
                        console.log('✅ Jump completed. Current state:', {
                            currentBlockIndex: this.currentBlockIndex,
                            currentBlockName: this.currentBlock?.nama,
                            currentQuestions: this.currentQuestions?.length || 0
                        });
                    } else {
                        console.error('❌ Block not found for jump:', { 
                            blockId,
                            availableBlocks: this.allBlocks.map(b => ({ id: b.id, nama: b.nama }))
                        });
                    }
                },

                addToHistory() {
                    // Add current position to history for back navigation
                    this.questionHistory.push({
                        blockIndex: this.currentBlockIndex,
                        questionIndex: this.currentQuestionIndex
                    });
                    
                    // Limit history size to prevent memory issues
                    if (this.questionHistory.length > 50) {
                        this.questionHistory = this.questionHistory.slice(-25);
                    }
                },

                checkBranchingRules(question) {
                    console.log('=== CHECKING BRANCHING RULES ===');
                    console.log('Question:', {
                        id: question.id,
                        type: question.tipe,
                        text: question.pertanyaan
                    });

                    if (!question || !['radio', 'select'].includes(question.tipe)) {
                        console.log('❌ No branching rules: not radio/select type');
                        return null;
                    }

                    const selectedAnswerId = this.answers[question.id];
                    console.log('Selected answer ID:', selectedAnswerId);
                    
                    if (!selectedAnswerId) {
                        console.log('❌ No selected answer');
                        return null;
                    }

                    // Find the selected option
                    const selectedOption = question.template_jawaban.find(opt => opt.id == selectedAnswerId);
                    console.log('Available options:', question.template_jawaban);
                    
                    if (!selectedOption) {
                        console.log('❌ Selected option not found', { 
                            selectedAnswerId,
                            availableOptions: question.template_jawaban.map(opt => ({ id: opt.id, text: opt.pilihan_jawaban }))
                        });
                        return null;
                    }

                    console.log('✅ Selected option found:', {
                        option_id: selectedOption.id,
                        option_text: selectedOption.pilihan_jawaban,
                        navigation_target: selectedOption.navigation_target
                    });

                    if (!selectedOption.navigation_target || selectedOption.navigation_target === '' || selectedOption.navigation_target === null) {
                        console.log('❌ No navigation target set for this option');
                        return null;
                    }

                    const navigationTarget = selectedOption.navigation_target;
                    console.log('Processing navigation target:', navigationTarget);

                    // Handle different navigation target formats
                    if (navigationTarget === 'end') {
                        console.log('🏁 Navigation target is END SURVEY');
                        return 'end';
                    } else if (navigationTarget === 'next') {
                        console.log('➡️ Navigation target is NEXT (normal flow)');
                        return null;
                    } else if (navigationTarget.startsWith('block_')) {
                        // Extract block number from 'block_X' format
                        const blockNumber = parseInt(navigationTarget.substring(6));
                        console.log('🎯 Navigation target is specific block:', blockNumber);
                        
                        console.log('Available blocks for search:', this.allBlocks.map(b => ({ 
                            id: b.id, 
                            nama: b.nama, 
                            urutan: b.urutan 
                        })));
                        
                        // Find the block by its order (urutan)
                        const targetBlock = this.allBlocks.find(block => block.urutan === blockNumber);
                        
                        if (targetBlock) {
                            console.log('✅ Target block found:', {
                                block_id: targetBlock.id,
                                block_name: targetBlock.nama,
                                block_order: targetBlock.urutan
                            });
                            return targetBlock.id;
                        } else {
                            console.log('❌ Target block not found for order:', blockNumber);
                            console.log('Available block orders:', this.allBlocks.map(b => b.urutan));
                        }
                    } else {
                        console.log('❓ Unknown navigation target format:', navigationTarget);
                    }

                    console.log('❌ No matching navigation rule found');
                    return null;
                },

                loadExistingAnswers() {
                    // Load existing answers if user is resuming the survey
                    if (this.existingAnswers && Object.keys(this.existingAnswers).length > 0) {
                        Object.entries(this.existingAnswers).forEach(([questionId, answer]) => {
                            // Handle different answer formats
                            if (typeof answer === 'string' && answer.includes(',')) {
                                // Checkbox answers stored as comma-separated values
                                this.answers[questionId] = answer.split(',');
                            } else {
                                this.answers[questionId] = answer;
                            }
                        });
                        console.log('Loaded existing answers:', this.answers);
                    }
                },

                saveCurrentAnswer() {
                    // This method is called to save current state before navigation
                    // The actual saving to server happens in nextQuestion() or handleSubmit()
                    console.log('Saving current answer state');
                },

                selectRadioOption(questionId, optionId, optionText) {
                    this.answers[questionId] = optionId;
                    console.log('Selected radio option:', {
                        questionId: questionId,
                        optionId: optionId,
                        optionText: optionText,
                        currentAnswers: this.answers
                    });
                    
                    // Find the selected option and log its navigation target
                    const currentQuestion = this.currentQuestions[this.currentQuestionIndex];
                    if (currentQuestion && currentQuestion.template_jawaban) {
                        const selectedOption = currentQuestion.template_jawaban.find(opt => opt.id == optionId);
                        if (selectedOption) {
                            console.log('Selected option details:', {
                                option: selectedOption,
                                navigation_target: selectedOption.navigation_target
                            });
                        }
                    }
                },

                handleSelectChange(questionId, value) {
                    this.answers[questionId] = value;
                },

                handleCheckboxChange(questionId, optionId, checked) {
                    if (!this.answers[questionId]) {
                        this.answers[questionId] = [];
                    }
                    
                    if (checked) {
                        if (!this.answers[questionId].includes(optionId)) {
                            this.answers[questionId].push(optionId);
                        }
                    } else {
                        this.answers[questionId] = this.answers[questionId].filter(id => id !== optionId);
                    }
                },

                handleFileChange(questionId, event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.answers[questionId] = file;
                    }
                },

                updateProgress() {
                    const totalQuestions = this.allBlocks.reduce((total, block) => total + block.questions.length, 0);
                    let completedQuestions = 0;
                    
                    // Count completed questions from previous blocks
                    for (let i = 0; i < this.currentBlockIndex; i++) {
                        completedQuestions += this.allBlocks[i].questions.length;
                    }
                    
                    // Add current question index + 1 (since we're working on current question)
                    completedQuestions += this.currentQuestionIndex + 1;
                    
                    // Calculate progress percentage
                    this.progress = totalQuestions > 0 ? Math.min((completedQuestions / totalQuestions) * 100, 100) : 0;
                    
                    console.log('Progress update:', {
                        currentBlock: this.currentBlockIndex,
                        currentQuestion: this.currentQuestionIndex,
                        completedQuestions: completedQuestions,
                        totalQuestions: totalQuestions,
                        progress: this.progress
                    });
                },

                completeSurvey() {
                    this.isCompleted = true;
                    this.progress = 100;
                },

                async handleSubmit() {
                    try {
                        // Prepare form data
                        const formData = new FormData();
                        
                        // Add CSRF token
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                                         document.querySelector('input[name="_token"]')?.value;
                        if (csrfToken) {
                            formData.append('_token', csrfToken);
                        }

                        // Add answers with correct field names for backend
                        Object.entries(this.answers).forEach(([questionId, answer]) => {
                            if (Array.isArray(answer)) {
                                // Checkbox answers
                                answer.forEach(value => {
                                    formData.append(`answer_${questionId}[]`, value);
                                });
                            } else if (answer instanceof File) {
                                // File upload
                                formData.append(`answer_${questionId}`, answer);
                            } else {
                                // Regular answers
                                formData.append(`answer_${questionId}`, answer);
                            }
                        });

                        // Submit to server
                        const response = await fetch(`/user/survey/${this.surveyId}`, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                            }
                        });

                        if (response.ok) {
                            const result = await response.json();
                            if (result.success) {
                                console.log('✅ Survey submitted successfully:', result.message);
                                this.completeSurvey();
                                // Show success message for 3 seconds then redirect
                                setTimeout(() => {
                                    window.location.href = result.redirect || '/user/profile';
                                }, 3000);
                            } else {
                                throw new Error(result.message || 'Survey submission failed');
                            }
                        } else {
                            const errorResult = await response.json();
                            throw new Error(errorResult.message || 'Survey submission failed');
                        }
                    } catch (error) {
                        console.error('Error submitting survey:', error);
                        alert('Terjadi kesalahan saat mengirim survey. Silakan coba lagi.');
                    }
                },

                get canGoPrevious() {
                    return this.questionHistory.length > 0;
                },

                get canProceed() {
                    const currentQuestion = this.currentQuestions[this.currentQuestionIndex];
                    if (!currentQuestion) return false;

                    const answer = this.answers[currentQuestion.id];
                    
                    // Check if question has an answer based on question type
                    switch (currentQuestion.tipe) {
                        case 'radio':
                        case 'select':
                            return answer && answer !== '';
                        case 'checkbox':
                            return Array.isArray(answer) && answer.length > 0;
                        case 'text':
                        case 'textarea':
                        case 'number':
                        case 'date':
                            return answer && answer.toString().trim() !== '';
                        case 'file':
                            return answer instanceof File;
                        default:
                            return true; // For info questions or other types
                    }
                },

                get isLastQuestion() {
                    return this.currentBlockIndex >= this.allBlocks.length - 1 && 
                           this.currentQuestionIndex >= this.currentQuestions.length - 1;
                }
            }
        }

        // ==== for menu scroll
        const pageLink = document.querySelectorAll(".ud-menu-scroll");

        pageLink.forEach((elem) => {
            elem.addEventListener("click", (e) => {
                e.preventDefault();
                document.querySelector(elem.getAttribute("href")).scrollIntoView({
                    behavior: "smooth",
                    offsetTop: 1 - 60,
                });
            });
        });

        // section menu active
        function onScroll(event) {
            const sections = document.querySelectorAll(".ud-menu-scroll");
            const scrollPos =
                window.pageYOffset ||
                document.documentElement.scrollTop ||
                document.body.scrollTop;

            for (let i = 0; i < sections.length; i++) {
                const currLink = sections[i];
                const val = currLink.getAttribute("href");
                const refElement = document.querySelector(val);
                const scrollTopMinus = scrollPos + 73;
                if (
                    refElement.offsetTop <= scrollTopMinus &&
                    refElement.offsetTop + refElement.offsetHeight > scrollTopMinus
                ) {
                    document
                        .querySelector(".ud-menu-scroll")
                        .classList.remove("active");
                    currLink.classList.add("active");
                } else {
                    currLink.classList.remove("active");
                }
            }
        }

        window.document.addEventListener("scroll", onScroll);

        // Testimonial
        const testimonialSwiper = new Swiper(".testimonial-carousel", {
            slidesPerView: 1,
            spaceBetween: 30,

            // Navigation arrows
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },

            breakpoints: {
                640: {
                    slidesPerView: 2,
                    spaceBetween: 30,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                },
                1280: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                },
            },
        });
    </script>
</body>

</html>
