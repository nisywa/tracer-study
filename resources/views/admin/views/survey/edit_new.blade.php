@extends('admin.layouts.app')
@section('title', 'Edit Survey')

@push('styles')
<style>
    .section-block {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        background: #f8fafc;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .section-block:hover {
        border-color: #3b82f6;
        background-color: #f1f5f9;
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    /* Options styling with navigation */
    .option-item {
        position: relative;
        margin-bottom: 12px;
    }

    .option-item .bg-blue-50 {
        border-left: 3px solid #3b82f6;
        transition: all 0.2s ease;
    }

    .option-item .bg-blue-50:hover {
        background-color: #eff6ff;
        border-left-color: #1d4ed8;
    }

    .option-navigation-select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 2.5rem;
    }

    /* Navigation toggle styling */
    .navigation-toggle-label {
        transition: all 0.2s ease;
    }

    .navigation-toggle-label:hover {
        color: #3b82f6;
    }

    /* Block colors */
    .block-color-odd {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        border-color: #0ea5e9;
    }

    .block-color-even {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        border-color: #22c55e;
    }

    /* Icon styling */
    .icon-container {
        display: flex;
        gap: 8px;
    }

    .icon-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        background-color: rgba(255, 255, 255, 0.8);
        color: #6b7280;
        transition: all 0.2s ease;
        border: 1px solid rgba(0, 0, 0, 0.1);
    }

    .icon-link:hover {
        background-color: #ffffff;
        color: #3b82f6;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Question item styling */
    .question-item {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 16px;
        transition: all 0.2s ease;
    }

    .question-item:hover {
        border-color: #3b82f6;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.15);
    }
</style>
@endpush

@section('content')
<div class="flex flex-wrap -mx-3">
    <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <div class="flex flex-wrap -mx-3">
                    <div class="flex items-center w-full max-w-full px-3 shrink-0 md:w-8/12 md:flex-0">
                        <h6 class="mb-0 dark:text-white">Edit Survey</h6>
                    </div>
                </div>
            </div>

            <div class="flex-auto p-6">
                <form id="editSurveyForm" action="{{ route('admin.survey.update', $survey->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Basic Survey Information -->
                    <div class="bg-gray-50 p-6 rounded-lg mb-6">
                        <h3 class="text-lg font-semibold mb-4">Informasi Survey</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Survey <span class="text-red-500">*</span></label>
                                <input type="text" id="nama" name="nama" value="{{ $survey->nama }}" required
                                       class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                                @error('nama')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="type_survei" class="block text-sm font-medium text-gray-700 mb-2">Tipe Survey <span class="text-red-500">*</span></label>
                                <select id="type_survei" name="type_survei" required
                                        class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                                    <option value="">Pilih Tipe Survey</option>
                                    <option value="alumni" {{ $survey->type_survei == 'alumni' ? 'selected' : '' }}>Lulusan</option>
                                    <option value="atasan" {{ $survey->type_survei == 'atasan' ? 'selected' : '' }}>Pengguna Lulusan</option>
                                </select>
                                @error('type_survei')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai <span class="text-red-500">*</span></label>
                                <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ $survey->tanggal_mulai }}" required
                                       class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                                @error('tanggal_mulai')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai <span class="text-red-500">*</span></label>
                                <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="{{ $survey->tanggal_selesai }}" required
                                       class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                                @error('tanggal_selesai')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Survey</label>
                            <textarea id="deskripsi" name="deskripsi" rows="3" placeholder="Deskripsi survey (opsional)"
                                      class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">{{ $survey->deskripsi }}</textarea>
                            @error('deskripsi')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Builder Section -->
                    <div class="bg-white border rounded-lg p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold">Form Builder</h3>
                            <button type="button" id="addSectionBtn" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                                <i class="fas fa-plus mr-2"></i>Tambah Block
                            </button>
                        </div>

                        <div id="sectionsContainer">
                            <!-- Sections will be populated by JavaScript -->
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end items-center mt-6 space-x-4">
                        <a href="{{ route('admin.survey.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            Batal
                        </a>
                        <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                            <i class="fas fa-save mr-2"></i>Update Survey
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let sectionCounter = 0;
let questionCounter = 0;

// Load existing form builder data
const formBuilderData = @json($formBuilderData ?? []);

document.addEventListener('DOMContentLoaded', function() {
    // Load existing sections if available
    if (Object.keys(formBuilderData).length > 0) {
        loadExistingSections();
    } else {
        // Add first section by default if no existing data
        addSection();
    }

    // Add section button event
    document.getElementById('addSectionBtn').addEventListener('click', addSection);

    // Update navigation options initially
    setTimeout(() => updateNavigationOptions(), 100);

    // Add form validation before submit
    const form = document.querySelector('#editSurveyForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Always prevent default to handle submission manually

            if (!validateForm()) {
                return false;
            }

            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengupdate...';

            // Submit form using fetch API
            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                return response.json().then(data => ({
                    status: response.status,
                    ok: response.ok,
                    data: data
                }));
            })
            .then(({status, ok, data}) => {
                if (ok && data.success !== false) {
                    // Success - show message and redirect
                    showSuccessMessage('Survey berhasil diupdate!');
                    setTimeout(() => {
                        window.location.href = "{{ route('admin.survey.index') }}";
                    }, 1500);
                } else {
                    // Error response with JSON data
                    throw new Error(data.message || 'Terjadi kesalahan saat mengupdate survey');
                }
            })
            .catch(error => {
                console.error('Error:', error);

                // Show specific error message
                let errorMessage = 'Terjadi kesalahan saat mengupdate survey.';
                if (error.message && error.message !== 'Terjadi kesalahan saat mengupdate survey') {
                    errorMessage = error.message;
                }

                showErrorMessage(errorMessage);

                // Reset button state
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    }
});

// Load existing sections from backend data
function loadExistingSections() {
    Object.keys(formBuilderData).forEach(sectionId => {
        const sectionData = formBuilderData[sectionId];
        sectionCounter++;

        const colorClass = (sectionCounter % 2 === 0) ? 'block-color-even' : 'block-color-odd';

        const sectionHtml = `
            <div class="section-block p-6 bg-white rounded-lg ${colorClass}" data-section-id="${sectionCounter}">
                <div class="block-header">
                    <div class="flex justify-between items-center">
                        <h4 class="text-lg font-semibold">Block ${sectionCounter}</h4>
                        <div class="icon-container">
                            <button type="button" onclick="cloneSection(${sectionCounter})" class="icon-link" title="Clone Block">
                                <i class="fas fa-copy"></i>
                            </button>
                            <button type="button" onclick="deleteSection(${sectionCounter})" class="icon-link" title="Delete Block">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <!-- Block Info -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-4">
                        <h5 class="text-sm font-medium text-gray-700 mb-3">Nama Block <span class="text-red-500">*</span></h5>
                        <input type="text" name="sections[${sectionCounter}][section_name]" value="${sectionData.section_name || ''}" placeholder="Tulis nama blok disini..." required
                               class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg mb-4">
                        <h5 class="text-sm font-medium text-gray-700 mb-3">Deskripsi Block</h5>
                        <textarea name="sections[${sectionCounter}][section_description]" rows="2" placeholder="Tulis deskripsi blok disini..."
                                  class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">${sectionData.section_description || ''}</textarea>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg mb-4">
                        <h5 class="text-sm font-medium text-gray-700 mb-3">Navigasi Block</h5>
                        <select name="sections[${sectionCounter}][navigation_type]" class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                            <option value="next" ${sectionData.navigation_type === 'next' ? 'selected' : ''}>Lanjut ke block berikutnya</option>
                            <option value="end" ${sectionData.navigation_type === 'end' ? 'selected' : ''}>Akhiri survey</option>
                        </select>
                    </div>

                    <!-- Questions Container -->
                    <div class="questions-container" data-section-id="${sectionCounter}">
                        <div class="flex justify-between items-center mb-4">
                            <h5 class="text-sm font-medium text-gray-700">Pertanyaan</h5>
                            <button type="button" onclick="addQuestion(${sectionCounter})" class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600 transition-colors">
                                <i class="fas fa-plus mr-1"></i>Tambah Pertanyaan
                            </button>
                        </div>
                        <div class="questions-list" id="questions-${sectionCounter}">
                            <!-- Questions will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.getElementById('sectionsContainer').insertAdjacentHTML('beforeend', sectionHtml);

        // Load questions for this section
        if (sectionData.questions) {
            Object.keys(sectionData.questions).forEach(questionId => {
                const questionData = sectionData.questions[questionId];
                loadExistingQuestion(sectionCounter, questionData);
            });
        }
    });

    updateNavigationOptions();
}

// Load existing question
function loadExistingQuestion(sectionId, questionData) {
    questionCounter++;

    const questionHtml = `
        <div class="question-item" data-question-id="${questionCounter}">
            <div class="flex justify-between items-center mb-3">
                <h6 class="text-sm font-semibold">Pertanyaan ${questionCounter}</h6>
                <div class="flex space-x-2">
                    <button type="button" onclick="cloneQuestion(${sectionId}, ${questionCounter})" class="text-blue-500 hover:text-blue-700" title="Clone Question">
                        <i class="fas fa-copy"></i>
                    </button>
                    <button type="button" onclick="deleteQuestion(${sectionId}, ${questionCounter})" class="text-red-500 hover:text-red-700" title="Delete Question">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Pertanyaan <span class="text-red-500">*</span></label>
                    <textarea name="sections[${sectionId}][questions][${questionCounter}][question]" rows="2" placeholder="Tulis pertanyaan disini..." required
                              class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">${questionData.question || ''}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="sections[${sectionId}][questions][${questionCounter}][description]" rows="2" placeholder="Deskripsi pertanyaan (opsional)"
                              class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">${questionData.description || ''}</textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Tipe Pertanyaan</label>
                    <select name="sections[${sectionId}][questions][${questionCounter}][type]" onchange="toggleOptions(${sectionId}, ${questionCounter})" required
                            class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                        <option value="text" ${questionData.type === 'text' ? 'selected' : ''}>Text Input</option>
                        <option value="textarea" ${questionData.type === 'textarea' ? 'selected' : ''}>Text Area</option>
                        <option value="radio" ${questionData.type === 'radio' ? 'selected' : ''}>Radio Button</option>
                        <option value="checkbox" ${questionData.type === 'checkbox' ? 'selected' : ''}>Checkbox</option>
                        <option value="select" ${questionData.type === 'select' ? 'selected' : ''}>Dropdown</option>
                        <option value="file" ${questionData.type === 'file' ? 'selected' : ''}>File Upload</option>
                        <option value="date" ${questionData.type === 'date' ? 'selected' : ''}>Date</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Visualisasi</label>
                    <select name="sections[${sectionId}][questions][${questionCounter}][visualization]"
                            class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                        <option value="bar" ${questionData.visualization === 'bar' ? 'selected' : ''}>Bar Chart</option>
                        <option value="pie" ${questionData.visualization === 'pie' ? 'selected' : ''}>Pie Chart</option>
                    </select>
                </div>
                <div class="flex items-center pt-6">
                    <input type="checkbox" name="sections[${sectionId}][questions][${questionCounter}][required]" value="1" ${questionData.required === '1' ? 'checked' : ''}
                           class="mr-2 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label class="text-xs font-medium text-gray-700">Wajib diisi</label>
                </div>
            </div>

            <!-- Options Container -->
            <div class="options-container" id="options-${sectionId}-${questionCounter}" style="display: ${['radio', 'checkbox', 'select'].includes(questionData.type) ? 'block' : 'none'};">
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-xs font-medium text-gray-700">Pilihan Jawaban</label>
                    <button type="button" onclick="addOption(${sectionId}, ${questionCounter})" class="bg-blue-500 text-white px-2 py-1 rounded text-xs hover:bg-blue-600 transition-colors">
                        <i class="fas fa-plus mr-1"></i>Tambah Pilihan
                    </button>
                </div>
                <div class="options-list space-y-2" id="options-list-${sectionId}-${questionCounter}">
                    <!-- Options will be loaded here -->
                </div>
            </div>
        </div>
    `;

    document.getElementById(`questions-${sectionId}`).insertAdjacentHTML('beforeend', questionHtml);

    // Load options for this question if it's a choice-based question
    if (['radio', 'checkbox', 'select'].includes(questionData.type) && questionData.options) {
        questionData.options.forEach((option, index) => {
            loadExistingOption(sectionId, questionCounter, option, questionData.option_navigation ? questionData.option_navigation[index] : 'next');
        });
    }
}

// Load existing option
function loadExistingOption(sectionId, questionId, optionText, navigationValue) {
    const optionsList = document.getElementById(`options-list-${sectionId}-${questionId}`);
    const optionIndex = optionsList.children.length;

    // Check if this question type supports navigation
    const questionTypeSelect = document.querySelector(`select[name="sections[${sectionId}][questions][${questionId}][type]"]`);
    const questionType = questionTypeSelect ? questionTypeSelect.value : 'text';
    const supportsNavigation = ['radio', 'select'].includes(questionType);

    const optionHtml = `
        <div class="option-item">
            <div class="flex items-center space-x-3 bg-blue-50 p-3 rounded-lg">
                <div class="flex-grow">
                    <input type="text" name="sections[${sectionId}][questions][${questionId}][options][]"
                           value="${optionText}" placeholder="Pilihan ${optionIndex + 1}" required
                           class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                </div>
                ${supportsNavigation ? `
                <div class="flex items-center space-x-2">
                    <label class="navigation-toggle-label">
                        <input type="checkbox" class="navigation-toggle mr-1" onchange="toggleOptionNavigation(this, ${sectionId}, ${questionId}, ${optionIndex})" ${navigationValue !== 'next' ? 'checked' : ''}>
                        <span class="text-xs text-gray-600">Custom Navigation</span>
                    </label>
                    <select name="sections[${sectionId}][questions][${questionId}][option_navigation][]"
                            class="option-navigation-select text-xs px-2 py-1 border border-gray-300 rounded bg-white"
                            style="display: ${navigationValue !== 'next' ? 'block' : 'none'};">
                        <option value="next" ${navigationValue === 'next' ? 'selected' : ''}>Lanjut</option>
                        <option value="end" ${navigationValue === 'end' ? 'selected' : ''}>Selesai</option>
                    </select>
                    <input type="hidden" name="sections[${sectionId}][questions][${questionId}][option_navigation][]" value="${navigationValue}" class="default-navigation-input">
                </div>
                ` : ''}
                <button type="button" onclick="this.parentElement.parentElement.remove(); updateNavigationOptions();" class="text-red-500 hover:text-red-700">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;

    optionsList.insertAdjacentHTML('beforeend', optionHtml);
}

// Rest of the JavaScript functions (same as create.blade.php)
function addSection() {
    sectionCounter++;
    const colorClass = (sectionCounter % 2 === 0) ? 'block-color-even' : 'block-color-odd';

    const sectionHtml = `
        <div class="section-block p-6 bg-white rounded-lg ${colorClass}" data-section-id="${sectionCounter}">
            <div class="block-header">
                <div class="flex justify-between items-center">
                    <h4 class="text-lg font-semibold">Block ${sectionCounter}</h4>
                    <div class="icon-container">
                        <button type="button" onclick="cloneSection(${sectionCounter})" class="icon-link" title="Clone Block">
                            <i class="fas fa-copy"></i>
                        </button>
                        <button type="button" onclick="deleteSection(${sectionCounter})" class="icon-link" title="Delete Block">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <!-- Block Info -->
                <div class="bg-gray-50 p-4 rounded-lg mb-4">
                    <h5 class="text-sm font-medium text-gray-700 mb-3">Nama Block <span class="text-red-500">*</span></h5>
                    <input type="text" name="sections[${sectionCounter}][section_name]" placeholder="Tulis nama blok disini..." required
                           class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                </div>

                <div class="bg-gray-50 p-4 rounded-lg mb-4">
                    <h5 class="text-sm font-medium text-gray-700 mb-3">Deskripsi Block</h5>
                    <textarea name="sections[${sectionCounter}][section_description]" rows="2" placeholder="Tulis deskripsi blok disini..."
                              class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"></textarea>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg mb-4">
                    <h5 class="text-sm font-medium text-gray-700 mb-3">Navigasi Block</h5>
                    <select name="sections[${sectionCounter}][navigation_type]" class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                        <option value="next">Lanjut ke block berikutnya</option>
                        <option value="end">Akhiri survey</option>
                    </select>
                </div>

                <!-- Questions Container -->
                <div class="questions-container" data-section-id="${sectionCounter}">
                    <div class="flex justify-between items-center mb-4">
                        <h5 class="text-sm font-medium text-gray-700">Pertanyaan</h5>
                        <button type="button" onclick="addQuestion(${sectionCounter})" class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600 transition-colors">
                            <i class="fas fa-plus mr-1"></i>Tambah Pertanyaan
                        </button>
                    </div>
                    <div class="questions-list" id="questions-${sectionCounter}">
                        <!-- Questions will be added here -->
                    </div>
                </div>
            </div>
        </div>
    `;

    document.getElementById('sectionsContainer').insertAdjacentHTML('beforeend', sectionHtml);
    updateNavigationOptions();
}

function addQuestion(sectionId) {
    questionCounter++;

    const questionHtml = `
        <div class="question-item" data-question-id="${questionCounter}">
            <div class="flex justify-between items-center mb-3">
                <h6 class="text-sm font-semibold">Pertanyaan ${questionCounter}</h6>
                <div class="flex space-x-2">
                    <button type="button" onclick="cloneQuestion(${sectionId}, ${questionCounter})" class="text-blue-500 hover:text-blue-700" title="Clone Question">
                        <i class="fas fa-copy"></i>
                    </button>
                    <button type="button" onclick="deleteQuestion(${sectionId}, ${questionCounter})" class="text-red-500 hover:text-red-700" title="Delete Question">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Pertanyaan <span class="text-red-500">*</span></label>
                    <textarea name="sections[${sectionId}][questions][${questionCounter}][question]" rows="2" placeholder="Tulis pertanyaan disini..." required
                              class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="sections[${sectionId}][questions][${questionCounter}][description]" rows="2" placeholder="Deskripsi pertanyaan (opsional)"
                              class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"></textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Tipe Pertanyaan</label>
                    <select name="sections[${sectionId}][questions][${questionCounter}][type]" onchange="toggleOptions(${sectionId}, ${questionCounter})" required
                            class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                        <option value="text">Text Input</option>
                        <option value="textarea">Text Area</option>
                        <option value="radio">Radio Button</option>
                        <option value="checkbox">Checkbox</option>
                        <option value="select">Dropdown</option>
                        <option value="file">File Upload</option>
                        <option value="date">Date</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Visualisasi</label>
                    <select name="sections[${sectionId}][questions][${questionCounter}][visualization]"
                            class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                        <option value="bar">Bar Chart</option>
                        <option value="pie">Pie Chart</option>
                    </select>
                </div>
                <div class="flex items-center pt-6">
                    <input type="checkbox" name="sections[${sectionId}][questions][${questionCounter}][required]" value="1"
                           class="mr-2 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label class="text-xs font-medium text-gray-700">Wajib diisi</label>
                </div>
            </div>

            <!-- Options Container -->
            <div class="options-container" id="options-${sectionId}-${questionCounter}" style="display: none;">
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-xs font-medium text-gray-700">Pilihan Jawaban</label>
                    <button type="button" onclick="addOption(${sectionId}, ${questionCounter})" class="bg-blue-500 text-white px-2 py-1 rounded text-xs hover:bg-blue-600 transition-colors">
                        <i class="fas fa-plus mr-1"></i>Tambah Pilihan
                    </button>
                </div>
                <div class="options-list space-y-2" id="options-list-${sectionId}-${questionCounter}">
                    <!-- Options will be added here -->
                </div>
            </div>
        </div>
    `;

    document.getElementById(`questions-${sectionId}`).insertAdjacentHTML('beforeend', questionHtml);
}

function toggleOptions(sectionId, questionId) {
    const questionType = document.querySelector(`select[name="sections[${sectionId}][questions][${questionId}][type]"]`).value;
    const optionsContainer = document.getElementById(`options-${sectionId}-${questionId}`);

    if (['radio', 'checkbox', 'select'].includes(questionType)) {
        optionsContainer.style.display = 'block';

        // Add default options if none exist
        const optionsList = document.getElementById(`options-list-${sectionId}-${questionId}`);
        if (optionsList.children.length === 0) {
            addOption(sectionId, questionId);
            addOption(sectionId, questionId);
        }

        // Update existing options for navigation support
        updateExistingOptionsNavigation(sectionId, questionId, questionType);
    } else {
        optionsContainer.style.display = 'none';
    }
}

function addOption(sectionId, questionId) {
    const optionsList = document.getElementById(`options-list-${sectionId}-${questionId}`);
    const optionIndex = optionsList.children.length;

    // Check if this question type supports navigation
    const questionTypeSelect = document.querySelector(`select[name="sections[${sectionId}][questions][${questionId}][type]"]`);
    const questionType = questionTypeSelect ? questionTypeSelect.value : 'text';
    const supportsNavigation = ['radio', 'select'].includes(questionType);

    const optionHtml = `
        <div class="option-item">
            <div class="flex items-center space-x-3 bg-blue-50 p-3 rounded-lg">
                <div class="flex-grow">
                    <input type="text" name="sections[${sectionId}][questions][${questionId}][options][]"
                           placeholder="Pilihan ${optionIndex + 1}" required
                           class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                </div>
                ${supportsNavigation ? `
                <div class="flex items-center space-x-2">
                    <label class="navigation-toggle-label">
                        <input type="checkbox" class="navigation-toggle mr-1" onchange="toggleOptionNavigation(this, ${sectionId}, ${questionId}, ${optionIndex})">
                        <span class="text-xs text-gray-600">Custom Navigation</span>
                    </label>
                    <select name="sections[${sectionId}][questions][${questionId}][option_navigation][]"
                            class="option-navigation-select text-xs px-2 py-1 border border-gray-300 rounded bg-white"
                            style="display: none;">
                        <option value="next">Lanjut</option>
                        <option value="end">Selesai</option>
                    </select>
                    <input type="hidden" name="sections[${sectionId}][questions][${questionId}][option_navigation][]" value="next" class="default-navigation-input">
                </div>
                ` : ''}
                <button type="button" onclick="this.parentElement.parentElement.remove(); updateNavigationOptions();" class="text-red-500 hover:text-red-700">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;

    optionsList.insertAdjacentHTML('beforeend', optionHtml);
    updateNavigationOptions();
}

// Toggle option navigation
function toggleOptionNavigation(checkbox, sectionId, questionId, optionIndex) {
    const optionItem = checkbox.closest('.option-item');
    const navigationSelect = optionItem.querySelector('.option-navigation-select');
    const hiddenInput = optionItem.querySelector('.default-navigation-input');

    if (checkbox.checked) {
        // Show custom navigation dropdown
        navigationSelect.style.display = 'block';
        hiddenInput.disabled = true; // Disable hidden input when using custom navigation
    } else {
        // Hide custom navigation dropdown and use default
        navigationSelect.style.display = 'none';
        hiddenInput.disabled = false; // Enable hidden input for default navigation
        navigationSelect.value = 'next'; // Reset to default
    }
}

// Update existing options when question type changes
function updateExistingOptionsNavigation(sectionId, questionId, questionType) {
    const optionItems = document.querySelectorAll(`#options-list-${sectionId}-${questionId} .option-item`);
    const supportsNavigation = ['radio', 'select'].includes(questionType);

    optionItems.forEach((optionItem, index) => {
        const existingNavigationControls = optionItem.querySelector('.navigation-toggle-label');

        if (supportsNavigation && !existingNavigationControls) {
            // Add navigation controls if they don't exist
            const optionContent = optionItem.querySelector('.bg-blue-50');
            const deleteButton = optionContent.querySelector('button[onclick*="remove"]');

            const navigationHtml = `
                <div class="flex items-center space-x-2">
                    <label class="navigation-toggle-label">
                        <input type="checkbox" class="navigation-toggle mr-1" onchange="toggleOptionNavigation(this, ${sectionId}, ${questionId}, ${index})">
                        <span class="text-xs text-gray-600">Custom Navigation</span>
                    </label>
                    <select name="sections[${sectionId}][questions][${questionId}][option_navigation][]"
                            class="option-navigation-select text-xs px-2 py-1 border border-gray-300 rounded bg-white"
                            style="display: none;">
                        <option value="next">Lanjut</option>
                        <option value="end">Selesai</option>
                    </select>
                    <input type="hidden" name="sections[${sectionId}][questions][${questionId}][option_navigation][]" value="next" class="default-navigation-input">
                </div>
            `;

            deleteButton.insertAdjacentHTML('beforebegin', navigationHtml);
        } else if (!supportsNavigation && existingNavigationControls) {
            // Remove navigation controls if question type doesn't support them
            existingNavigationControls.parentElement.remove();
        }
    });
}

function deleteSection(sectionId) {
    if (confirm('Apakah Anda yakin ingin menghapus block ini?')) {
        document.querySelector(`[data-section-id="${sectionId}"]`).remove();
        updateNavigationOptions();
    }
}

function deleteQuestion(sectionId, questionId) {
    if (confirm('Apakah Anda yakin ingin menghapus pertanyaan ini?')) {
        document.querySelector(`[data-question-id="${questionId}"]`).remove();
    }
}

function cloneSection(sectionId) {
    // Implementation for cloning sections
    console.log('Clone section:', sectionId);
}

function cloneQuestion(sectionId, questionId) {
    // Implementation for cloning questions
    console.log('Clone question:', sectionId, questionId);
}

function updateNavigationOptions() {
    // Implementation for updating navigation options
    console.log('Navigation options updated');
}

function validateForm() {
    const errors = [];

    // Validate basic survey info
    const surveyName = document.querySelector('input[name="nama"]');
    if (!surveyName || !surveyName.value.trim()) {
        errors.push('Nama Survey wajib diisi');
        markFieldError(surveyName);
    } else {
        markFieldValid(surveyName);
    }

    const startDate = document.querySelector('input[name="tanggal_mulai"]');
    if (!startDate || !startDate.value) {
        errors.push('Tanggal Mulai wajib diisi');
        markFieldError(startDate);
    } else {
        markFieldValid(startDate);
    }

    const endDate = document.querySelector('input[name="tanggal_selesai"]');
    if (!endDate || !endDate.value) {
        errors.push('Tanggal Selesai wajib diisi');
        markFieldError(endDate);
    } else {
        markFieldValid(endDate);
    }

    const surveyType = document.querySelector('select[name="type_survei"]');
    if (!surveyType || !surveyType.value) {
        errors.push('Tipe Survei wajib dipilih');
        markFieldError(surveyType);
    } else {
        markFieldValid(surveyType);
    }

    // Validate blocks and questions
    const sections = document.querySelectorAll('.section-block');
    if (sections.length === 0) {
        errors.push('Minimal harus ada 1 block');
    }

    sections.forEach((section, sectionIndex) => {
        const sectionName = section.querySelector('input[name*="[section_name]"]');
        if (!sectionName || !sectionName.value.trim()) {
            errors.push(`Nama Block ${sectionIndex + 1} wajib diisi`);
            markFieldError(sectionName);
        } else {
            markFieldValid(sectionName);
        }

        const questions = section.querySelectorAll('.question-item');
        if (questions.length === 0) {
            errors.push(`Block ${sectionIndex + 1} harus memiliki minimal 1 pertanyaan`);
        }

        questions.forEach((question, questionIndex) => {
            const questionText = question.querySelector('textarea[name*="[question]"]');
            if (!questionText || !questionText.value.trim()) {
                errors.push(`Pertanyaan ${questionIndex + 1} di Block ${sectionIndex + 1} wajib diisi`);
                markFieldError(questionText);
            } else {
                markFieldValid(questionText);
            }

            // Validate options for select types
            const questionType = question.querySelector('select[name*="[type]"]');
            if (questionType && ['radio', 'checkbox', 'select'].includes(questionType.value)) {
                const options = question.querySelectorAll('input[name*="[options]"]');
                const filledOptions = Array.from(options).filter(opt => opt.value.trim());
                if (filledOptions.length < 2) {
                    errors.push(`Pertanyaan ${questionIndex + 1} di Block ${sectionIndex + 1} dengan tipe ${questionType.value} harus memiliki minimal 2 pilihan`);
                    options.forEach(opt => markFieldError(opt));
                } else {
                    options.forEach(opt => markFieldValid(opt));
                }
            }
        });
    });

    // Show errors if any
    if (errors.length > 0) {
        showErrorMessage('Terdapat kesalahan pada form:\n\n' + errors.join('\n'));
        return false;
    }

    return true;
}

function markFieldError(field) {
    if (field) {
        field.classList.add('border-red-500', 'bg-red-50');
        field.classList.remove('border-green-500', 'bg-green-50');
    }
}

function markFieldValid(field) {
    if (field) {
        field.classList.remove('border-red-500', 'bg-red-50');
        field.classList.add('border-green-500', 'bg-green-50');
    }
}

// Clear validation styling on input
document.addEventListener('input', function(e) {
    if (e.target.matches('input, textarea, select')) {
        e.target.classList.remove('border-red-500', 'bg-red-50', 'border-green-500', 'bg-green-50');
    }
});

// Function to show error message
function showErrorMessage(message) {
    // Remove any existing messages
    removeMessages();

    const messageDiv = document.createElement('div');
    messageDiv.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 max-w-md';
    messageDiv.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle mr-3"></i>
            <div>
                <h4 class="font-semibold">Error!</h4>
                <p class="text-sm mt-1">${message}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;

    document.body.appendChild(messageDiv);

    // Auto remove after 10 seconds
    setTimeout(() => {
        if (messageDiv.parentElement) {
            messageDiv.remove();
        }
    }, 10000);
}

// Function to show success message
function showSuccessMessage(message) {
    // Remove any existing messages
    removeMessages();

    const messageDiv = document.createElement('div');
    messageDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 max-w-md';
    messageDiv.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-3"></i>
            <div>
                <h4 class="font-semibold">Berhasil!</h4>
                <p class="text-sm mt-1">${message}</p>
            </div>
        </div>
    `;

    document.body.appendChild(messageDiv);

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (messageDiv.parentElement) {
            messageDiv.remove();
        }
    }, 5000);
}

// Function to remove existing messages
function removeMessages() {
    const existingMessages = document.querySelectorAll('.fixed.top-4.right-4');
    existingMessages.forEach(msg => msg.remove());
}
</script>
@endpush

@endsection
