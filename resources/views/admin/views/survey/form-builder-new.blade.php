@extends('admin.layouts.app')

@push('styles')
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Alpine.js -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>
    .icon-container {
        display: flex;
        gap: 8px;
        justify-content: center;
        align-items: center;
    }

    .icon-link {
        color: #64748b;
        cursor: pointer;
        padding: 4px;
        border-radius: 4px;
        transition: all 0.2s;
    }

    .icon-link:hover {
        background-color: #f1f5f9;
        color: #3758f9;
    }

    .icon-link.text-purple-600 {
        color: #9333ea;
    }

    .icon-link.text-purple-600:hover {
        color: #7c3aed;
        background-color: #faf5ff;
    }

    .option-container {
        min-width: 200px;
    }

    .option-item {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
    }

    .question-input, .description-input {
        border: none;
        background: transparent;
        resize: none;
        outline: none;
        width: 100%;
    }

    .question-input:focus, .description-input:focus {
        background-color: #f8fafc;
        border: 1px solid #3758f9;
        border-radius: 4px;
        padding: 4px;
    }

    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 2000;
        display: none;
    }

    .modal-overlay.show {
        display: block;
    }

    .modal-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        border-radius: 12px;
        padding: 30px;
        max-width: 600px;
        width: 90%;
        max-height: 80vh;
        overflow-y: auto;
    }

    [x-cloak] {
        display: none !important;
    }
</style>
@endpush

@section('content')
<div class="form-builder-container" x-data="formBuilder()">
    <form id="surveyForm" @submit.prevent="saveForm()">
        @csrf
        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                    
                    <!-- Header -->
                    <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h6 class="dark:text-white">Form Builder - <span x-text="survey.nama">{{ $survey->nama ?? 'Survey' }}</span></h6>
                                <p class="text-sm text-slate-500">Buat dan kelola pertanyaan survei dengan mudah</p>
                            </div>
                            <div class="flex space-x-2">
                                <button type="button" @click="previewMode = !previewMode"
                                        class="inline-block px-4 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85"
                                        :class="previewMode ? 'bg-orange-500 hover:bg-orange-600' : 'bg-purple-500 hover:bg-purple-600'">
                                    <i class="fas fa-eye mr-2"></i> <span x-text="previewMode ? 'Edit Mode' : 'Preview'"></span>
                                </button>
                                <button type="button" @click="addQuestion()"
                                        class="inline-block px-4 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                                    <i class="fas fa-plus mr-2"></i> Tambah Pertanyaan
                                </button>
                                <button type="submit"
                                        class="inline-block px-4 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-green-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                                    <i class="fas fa-save mr-2"></i> Simpan
                                </button>
                            </div>
                        </div>
                        
                        <!-- Info Panel -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle text-blue-400"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">Form Builder</h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <p>• Tambah pertanyaan dengan berbagai tipe jawaban (text, radio, checkbox, dll)</p>
                                        <p>• Atur blok dan percabangan untuk membuat survei yang dinamis</p>
                                        <p>• Gunakan drag & drop untuk mengurutkan pertanyaan</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Area -->
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <input type="hidden" name="survey_id" :value="surveyId">
                        <div class="p-0 overflow-x-auto">
                            <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                                <thead class="align-bottom">
                                    <tr>
                                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            Pertanyaan
                                        </th>
                                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            Deskripsi
                                        </th>
                                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            Blok
                                        </th>
                                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            Tipe Jawaban
                                        </th>
                                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            Keterangan Jawaban
                                        </th>
                                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            Visualisasi
                                        </th>
                                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(question, index) in questions" :key="question.temp_id || question.id || index">
                                        <tr :class="{'opacity-50': previewMode}">
                                            <!-- Pertanyaan -->
                                            <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <div class="flex flex-col px-2 py-1">
                                                    <textarea x-model="question.pertanyaan"
                                                            @input="updateQuestion(question)"
                                                            class="text-sm leading-normal dark:text-white bg-transparent outline-none question-input"
                                                            placeholder="Tulis Pertanyaan disini"
                                                            rows="3"
                                                            :disabled="previewMode"
                                                            style="resize: none; width: 100%;"
                                                            @input="autoResize($event)"></textarea>
                                                </div>
                                            </td>

                                            <!-- Deskripsi -->
                                            <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <div class="flex flex-col px-2 py-1">
                                                    <textarea x-model="question.deskripsi_pertanyaan"
                                                            @input="updateQuestion(question)"
                                                            class="text-sm leading-normal dark:text-white bg-transparent outline-none description-input"
                                                            placeholder="Tulis Deskripsi disini"
                                                            rows="3"
                                                            :disabled="previewMode"
                                                            style="resize: none; width: 100%;"
                                                            @input="autoResize($event)"></textarea>
                                                </div>
                                            </td>

                                            <!-- Blok -->
                                            <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <div class="flex flex-col px-2 py-1">
                                                    <select x-model="question.survey_block_id" 
                                                            @change="updateQuestion(question)"
                                                            :disabled="previewMode"
                                                            class="text-xs font-semibold leading-tight border border-gray-400 rounded px-2 py-1">
                                                        <option value="">Pilih Blok</option>
                                                        <template x-for="block in blocks" :key="block.id">
                                                            <option :value="block.id" x-text="block.nama"></option>
                                                        </template>
                                                    </select>
                                                </div>
                                            </td>

                                            <!-- Tipe Jawaban -->
                                            <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <select x-model="question.tipe"
                                                        @change="changeQuestionType(question)"
                                                        :disabled="previewMode"
                                                        class="text-xs font-semibold leading-tight border border-gray-400 rounded px-2 py-1">
                                                    <option value="">Pilih Tipe</option>
                                                    <option value="text">Short Answer</option>
                                                    <option value="textarea">Paragraph</option>
                                                    <option value="checkbox">Checkboxes</option>
                                                    <option value="radio">Radio</option>
                                                    <option value="select">Dropdown</option>
                                                    <option value="file">File</option>
                                                    <option value="date">Datepicker</option>
                                                </select>
                                            </td>

                                            <!-- Keterangan Jawaban -->
                                            <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <div x-show="['radio', 'checkbox', 'select'].includes(question.tipe)" class="option-container">
                                                    <template x-for="(option, optIndex) in question.options" :key="optIndex">
                                                        <div class="option-item">
                                                            <input type="text" 
                                                                   x-model="option.text"
                                                                   @input="updateQuestion(question)"
                                                                   :disabled="previewMode"
                                                                   class="text-xs border border-gray-400 rounded px-2 py-1 flex-grow" />
                                                            <div class="flex space-x-1" x-show="!previewMode">
                                                                <button type="button" @click="moveOptionUp(question, optIndex)" 
                                                                        class="text-xs px-2 py-1 border rounded hover:bg-gray-100">
                                                                    <i class="fas fa-arrow-up"></i>
                                                                </button>
                                                                <button type="button" @click="moveOptionDown(question, optIndex)" 
                                                                        class="text-xs px-2 py-1 border rounded hover:bg-gray-100">
                                                                    <i class="fas fa-arrow-down"></i>
                                                                </button>
                                                                <button type="button" @click="removeOption(question, optIndex)" 
                                                                        class="text-xs px-2 py-1 border rounded text-red-500 hover:bg-red-50">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </template>
                                                    <button type="button" @click="addOption(question)" 
                                                            x-show="!previewMode && ['radio', 'checkbox', 'select'].includes(question.tipe)"
                                                            class="text-xs px-2 py-1 border rounded text-blue-500 hover:bg-blue-50 mt-2">
                                                        <i class="fas fa-plus mr-1"></i> Add Option
                                                    </button>
                                                </div>
                                                <span x-show="!['radio', 'checkbox', 'select'].includes(question.tipe)" 
                                                      class="text-xs font-semibold leading-tight text-slate-400" 
                                                      x-text="question.tipe || 'Tipe Jawaban'"></span>
                                            </td>

                                            <!-- Visualisasi -->
                                            <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <select x-model="question.visualisasi"
                                                        @change="updateQuestion(question)"
                                                        :disabled="previewMode"
                                                        class="text-xs font-semibold leading-tight border border-gray-400 rounded px-2 py-1">
                                                    <option value="">Pilih Visualisasi</option>
                                                    <option value="bar">Bar Chart</option>
                                                    <option value="pie">Pie Chart</option>
                                                    <option value="lineChart">Line Chart</option>
                                                </select>
                                            </td>

                                            <!-- Aksi -->
                                            <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <div class="icon-container">
                                                    <template x-if="question.id && !previewMode">
                                                        <a @click="manageBranchRules(question)" 
                                                           class="icon-link text-purple-600 hover:text-purple-800 cursor-pointer" 
                                                           title="Aturan Percabangan">
                                                            <i class="fas fa-code-branch"></i>
                                                        </a>
                                                    </template>
                                                    <template x-if="!previewMode">
                                                        <a @click="duplicateQuestion(question)" class="icon-link cursor-pointer" title="Duplicate">
                                                            <i class="fas fa-copy"></i>
                                                        </a>
                                                    </template>
                                                    <template x-if="!previewMode">
                                                        <a @click="deleteQuestion(index)" class="icon-link cursor-pointer text-red-500" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </template>
                                                    <template x-if="!previewMode">
                                                        <a @click="moveQuestionUp(index)" class="icon-link cursor-pointer" title="Move Up">
                                                            <i class="fas fa-arrow-up"></i>
                                                        </a>
                                                    </template>
                                                    <template x-if="!previewMode">
                                                        <a @click="moveQuestionDown(index)" class="icon-link cursor-pointer" title="Move Down">
                                                            <i class="fas fa-arrow-down"></i>
                                                        </a>
                                                    </template>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Branch Rules Modal -->
    <div class="modal-overlay" :class="{'show': showBranchModal}" x-show="showBranchModal" x-cloak>
        <div class="modal-content">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Aturan Percabangan</h3>
                <button @click="closeBranchModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="mb-4">
                <p class="text-sm text-gray-600">
                    Pertanyaan: <strong x-text="selectedQuestion?.pertanyaan"></strong>
                </p>
            </div>

            <!-- Add New Branch Rule -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <h4 class="font-medium mb-3">Tambah Aturan Baru</h4>
                <form @submit.prevent="saveBranchRule()">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kondisi</label>
                            <select x-model="newBranchRule.condition_type" class="w-full border border-gray-300 rounded-md px-3 py-2">
                                <option value="equals">Sama dengan</option>
                                <option value="contains">Mengandung</option>
                                <option value="greater_than">Lebih dari</option>
                                <option value="less_than">Kurang dari</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nilai</label>
                            <input type="text" x-model="newBranchRule.condition_value" 
                                   class="w-full border border-gray-300 rounded-md px-3 py-2"
                                   placeholder="Nilai kondisi">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Target Blok</label>
                        <select x-model="newBranchRule.target_block_id" class="w-full border border-gray-300 rounded-md px-3 py-2">
                            <option value="">Pilih blok tujuan</option>
                            <template x-for="block in blocks" :key="block.id">
                                <option :value="block.id" x-text="block.nama"></option>
                            </template>
                        </select>
                    </div>
                    <button type="submit" 
                            class="px-4 py-2 bg-purple-500 text-white rounded-md hover:bg-purple-600">
                        <i class="fas fa-plus mr-2"></i> Tambah Aturan
                    </button>
                </form>
            </div>

            <!-- Existing Branch Rules -->
            <div x-show="branchRules.length > 0">
                <h4 class="font-medium mb-3">Aturan yang Ada</h4>
                <div class="space-y-2">
                    <template x-for="rule in branchRules" :key="rule.id">
                        <div class="border border-gray-200 rounded-md p-3 bg-white">
                            <div class="flex items-center justify-between">
                                <div class="text-sm">
                                    <span class="font-medium">Jika</span> 
                                    <span x-text="rule.condition_type"></span> 
                                    "<span x-text="rule.condition_value"></span>"
                                    <span class="font-medium">→ ke</span> 
                                    <span x-text="getBlockName(rule.target_block_id)"></span>
                                </div>
                                <button @click="deleteBranchRule(rule.id)" 
                                        class="text-red-500 hover:text-red-700">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function formBuilder() {
    return {
        // Data
        surveyId: {{ $survey->id ?? 'null' }},
        survey: {!! json_encode($survey ?? []) !!},
        questions: [],
        blocks: [],
        selectedQuestion: null,
        previewMode: false,
        showBranchModal: false,
        branchRules: [],
        newBranchRule: {
            condition_type: 'equals',
            condition_value: '',
            target_block_id: '',
            priority: 1
        },

        // Initialize
        init() {
            this.loadData();
        },

        async loadData() {
            try {
                // Load questions and blocks
                const response = await fetch(`/api/survey/${this.surveyId}/form-builder`);
                const data = await response.json();
                
                this.questions = data.questions || [];
                this.blocks = data.blocks || [];
            } catch (error) {
                console.error('Error loading data:', error);
                this.questions = [];
                this.blocks = [];
            }
        },

        // Question management
        addQuestion() {
            const newQuestion = {
                temp_id: Date.now(),
                pertanyaan: '',
                deskripsi_pertanyaan: '',
                tipe: '',
                survey_block_id: '',
                visualisasi: '',
                options: [],
                is_required: false,
                order: this.questions.length + 1
            };
            this.questions.push(newQuestion);
        },

        duplicateQuestion(question) {
            const duplicate = {
                ...question,
                temp_id: Date.now(),
                id: null,
                pertanyaan: question.pertanyaan + ' (Copy)',
                options: [...(question.options || [])]
            };
            const index = this.questions.findIndex(q => 
                (q.id && q.id === question.id) || (q.temp_id && q.temp_id === question.temp_id)
            );
            this.questions.splice(index + 1, 0, duplicate);
        },

        deleteQuestion(index) {
            if (confirm('Hapus pertanyaan ini?')) {
                this.questions.splice(index, 1);
            }
        },

        moveQuestionUp(index) {
            if (index > 0) {
                const question = this.questions.splice(index, 1)[0];
                this.questions.splice(index - 1, 0, question);
            }
        },

        moveQuestionDown(index) {
            if (index < this.questions.length - 1) {
                const question = this.questions.splice(index, 1)[0];
                this.questions.splice(index + 1, 0, question);
            }
        },

        changeQuestionType(question) {
            // Initialize options for choice-based questions
            if (['radio', 'checkbox', 'select'].includes(question.tipe)) {
                if (!question.options || question.options.length === 0) {
                    question.options = [
                        { text: 'Option 1' },
                        { text: 'Option 2' }
                    ];
                }
            } else {
                question.options = [];
            }
        },

        // Option management
        addOption(question) {
            if (!question.options) question.options = [];
            question.options.push({ text: `Option ${question.options.length + 1}` });
        },

        removeOption(question, index) {
            question.options.splice(index, 1);
        },

        moveOptionUp(question, index) {
            if (index > 0) {
                const option = question.options.splice(index, 1)[0];
                question.options.splice(index - 1, 0, option);
            }
        },

        moveOptionDown(question, index) {
            if (index < question.options.length - 1) {
                const option = question.options.splice(index, 1)[0];
                question.options.splice(index + 1, 0, option);
            }
        },

        // Branch Rules
        manageBranchRules(question) {
            this.selectedQuestion = question;
            this.loadBranchRules(question.id);
            this.showBranchModal = true;
        },

        async loadBranchRules(questionId) {
            try {
                const response = await fetch(`/api/survey/${this.surveyId}/questions/${questionId}/branch-rules`);
                this.branchRules = await response.json();
            } catch (error) {
                console.error('Error loading branch rules:', error);
                this.branchRules = [];
            }
        },

        async saveBranchRule() {
            try {
                const response = await fetch(`/api/survey/${this.surveyId}/questions/${this.selectedQuestion.id}/branch-rules`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(this.newBranchRule)
                });

                if (response.ok) {
                    await this.loadBranchRules(this.selectedQuestion.id);
                    this.newBranchRule = {
                        condition_type: 'equals',
                        condition_value: '',
                        target_block_id: '',
                        priority: 1
                    };
                }
            } catch (error) {
                console.error('Error saving branch rule:', error);
            }
        },

        async deleteBranchRule(ruleId) {
            if (!confirm('Hapus aturan percabangan ini?')) return;

            try {
                const response = await fetch(`/api/survey/${this.surveyId}/questions/${this.selectedQuestion.id}/branch-rules/${ruleId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (response.ok) {
                    await this.loadBranchRules(this.selectedQuestion.id);
                }
            } catch (error) {
                console.error('Error deleting branch rule:', error);
            }
        },

        closeBranchModal() {
            this.showBranchModal = false;
            this.selectedQuestion = null;
            this.branchRules = [];
        },

        // Utilities
        updateQuestion(question) {
            // Auto-save functionality can be added here
        },

        getBlockName(blockId) {
            const block = this.blocks.find(b => b.id == blockId);
            return block ? block.nama : 'Unknown Block';
        },

        autoResize(event) {
            event.target.style.height = 'auto';
            event.target.style.height = (event.target.scrollHeight) + 'px';
        },

        async saveForm() {
            try {
                const response = await fetch(`/api/survey/${this.surveyId}/form-builder`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        questions: this.questions
                    })
                });

                if (response.ok) {
                    alert('Form berhasil disimpan!');
                    await this.loadData(); // Reload to get IDs for new questions
                } else {
                    throw new Error('Failed to save form');
                }
            } catch (error) {
                console.error('Error saving form:', error);
                alert('Gagal menyimpan form!');
            }
        }
    };
}
</script>
@endpush
@endsection
