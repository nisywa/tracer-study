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

    /* Navigation block animation */
    .navigation-block {
        transition: all 0.3s ease;
    }

    .navigation-block.hidden {
        opacity: 0;
        max-height: 0;
        overflow: hidden;
        margin: 0;
        padding: 0;
    }

    .navigation-block.block {
        opacity: 1;
        max-height: 200px;
    }

    /* Enhanced Question Item Styling with Better Separation */
    .question-item {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        background: white;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        margin-bottom: 20px;
        padding: 20px;
        position: relative;
        transition: all 0.3s ease;
    }

    /* Section Block Spacing */
    .section-block {
        margin-bottom: 30px;
    }
    .question-item:hover {
        border-color: #3b82f6;
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }

    /* Question Number Badge */
    .question-item::before {
        content: attr(data-question-number);
        position: absolute;
        top: -10px;
        left: 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: bold;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        z-index: 10;
    }

    /* Question Separator Line */
    .question-item:not(:last-child)::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 60%;
        height: 2px;
        background: linear-gradient(90deg, transparent, #e5e7eb, transparent);
    }

    /* Questions Container Spacing */
    .questions-container {
        padding: 20px 0;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 12px;
        margin-top: 16px;
    }

    /* Add Block Button Styling */
    .add-block-section {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        border-radius: 12px;
        padding: 20px;
        margin-top: 16px;
        border: 1px dashed #64748b;
        transition: all 0.3s ease;
    }
    .add-block-section:hover {
        border-color: #10b981;
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        transform: translateY(-1px);
    }

    /* Option Controls Styling */
    .option-controls {
        display: flex;
        flex-direction: column;
        gap: 2px;
        margin-left: 8px;
    }
    .option-btn {
        padding: 4px 6px;
        border-radius: 4px;
        border: 1px solid #e5e7eb;
        background: white;
        color: #6b7280;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 10px;
        line-height: 1;
        min-width: 24px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .option-btn:hover {
        background: #f3f4f6;
        color: #374151;
        border-color: #d1d5db;
        transform: translateY(-1px);
    }
    .option-btn:disabled {
        opacity: 0.3;
        cursor: not-allowed;
        transform: none;
    }
    .option-btn.up:hover:not(:disabled) {
        background: #dbeafe;
        color: #1d4ed8;
        border-color: #3b82f6;
    }
    .option-btn.down:hover:not(:disabled) {
        background: #fef3c7;
        color: #d97706;
        border-color: #f59e0b;
    }

    /* Option Item Styling */
    .option-item {
        background: #f9fafb;
        padding: 8px;
        border-radius: 8px;
        border: 1px solid #f3f4f6;
        transition: all 0.2s ease;
    }
    .option-item:hover {
        background: #f3f4f6;
        border-color: #e5e7eb;
    }

    .icon-container {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    .icon-link {
        padding: 8px;
        border-radius: 6px;
        color: #6b7280;
        transition: all 0.2s;
        cursor: pointer;
        background: white;
        border: 1px solid #e5e7eb;
    }
    .icon-link:hover {
        background-color: #f3f4f6;
        color: #374151;
        border-color: #d1d5db;
        transform: translateY(-1px);
    }
    .block-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 12px 16px;
        border-radius: 8px 8px 0 0;
        margin: -24px -24px 16px -24px;
    }
    .question-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 12px 16px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        margin-bottom: 16px;
        position: relative;
    }
    .form-section {
        background: white;
        border-radius: 0 0 8px 8px;
        padding: 16px;
    }
    .required-asterisk {
        color: #ef4444;
        font-weight: bold;
    }
    .error-field {
        border-color: #ef4444 !important;
        background-color: #fef2f2 !important;
    }
    .valid-field {
        border-color: #10b981 !important;
        background-color: #f0fdf4 !important;
    }

    /* Block color variations - Even/Odd only */
    .block-color-even .block-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: white !important;
    }
    .block-color-odd .block-header {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
        color: white !important;
    }

    .block-color-even {
        border-left: 4px solid #667eea !important;
    }
    .block-color-odd {
        border-left: 4px solid #4facfe !important;
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
            <div class="section-block p-6 ${colorClass}" data-section-id="${sectionCounter}">
                <div class="block-header">
                    <div class="flex justify-between items-center">
                        <h4 class="block-title">Block ${sectionCounter}</h4>
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
                        <select name="sections[${sectionCounter}][navigation_type]" data-original-value="${sectionData.navigation_type}" class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
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
                        <div class="questions-list space-y-3" id="questions-${sectionCounter}">
                            <!-- Questions will be loaded here -->
                        </div>
                    </div>

                    <!-- Add Block Button -->
                    <div class="add-block-section mt-6 pt-4">
                        <div class="flex justify-center">
                            <button type="button" onclick="addSectionAfter(${sectionCounter})" class="inline-block px-6 py-3 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-gradient-to-r from-green-500 to-green-600 border-0 rounded-lg shadow-lg cursor-pointer text-sm tracking-tight-rem hover:shadow-xl hover:-translate-y-1 active:opacity-85 hover:from-green-600 hover:to-green-700">
                                <i class="fas fa-plus-circle mr-2"></i> Tambah Block Baru
                            </button>
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
        <div class="question-item" data-question-id="${questionCounter}" data-question-number="Q${questionCounter}">
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
                        <option value="" ${questionData.visualization === '' ? 'selected' : ''}>Tidak ada visualisasi</option>
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
    const optionCount = optionsList.children.length + 1;

    // Check if this question type supports navigation
    const questionTypeSelect = document.querySelector(`select[name="sections[${sectionId}][questions][${questionId}][type]"]`);
    const questionType = questionTypeSelect ? questionTypeSelect.value : 'text';
    const showNavigationToggle = ['radio', 'select'].includes(questionType);

    // Determine if custom navigation is being used
    // Custom navigation is active if navigationValue is not null, undefined, empty, or 'next' (default)
    const isCustomNavigation = navigationValue && navigationValue !== '' && navigationValue !== 'next';

    const optionHtml = `
        <div class="option-item mb-3" data-option-index="${optionCount}">
            <div class="flex items-start gap-2">
                <div class="option-controls">
                    <button type="button" onclick="moveOptionUp(this)" class="option-btn up" title="Move Up">
                        <i class="fas fa-chevron-up"></i>
                    </button>
                    <button type="button" onclick="moveOptionDown(this)" class="option-btn down" title="Move Down">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
                <div class="flex-1">
                    <input type="text" name="sections[${sectionId}][questions][${questionId}][options][]"
                           value="${optionText}" placeholder="Pilihan ${optionCount}" required
                           class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                    ${showNavigationToggle ? `
                    <div class="mt-2 flex items-center gap-2">
                        <label class="flex items-center cursor-pointer navigation-toggle-label">
                            <input type="checkbox" onchange="toggleOptionNavigation(this)" class="mr-2 text-blue-600 rounded focus:ring-blue-500" ${isCustomNavigation ? 'checked' : ''}>
                            <span class="text-xs text-gray-600">🔀 Custom navigation untuk pilihan ini</span>
                        </label>
                    </div>
                    <div class="navigation-block ${isCustomNavigation ? 'block' : 'hidden'} mt-2 ml-4 px-3 py-2 bg-blue-50 border border-blue-200 rounded-lg">
                        <label class="text-xs font-medium text-blue-700 mb-1 block">Jika pilihan ini dipilih, lanjut ke:</label>
                        <select name="sections[${sectionId}][questions][${questionId}][option_navigation][]" ${!isCustomNavigation ? 'disabled' : ''} class="text-xs w-full border border-blue-300 rounded px-2 py-1 bg-white option-navigation-select" data-original-value="${navigationValue || ''}">
                            <option value="">Gunakan navigasi default</option>
                            <option value="next">Block Berikutnya</option>
                            <option value="end">Selesai Survey</option>
                        </select>
                        <input type="hidden" name="sections[${sectionId}][questions][${questionId}][option_navigation][]" value="next" class="hidden-navigation-input" ${isCustomNavigation ? 'disabled' : ''}>
                    </div>
                    ` : ''}
                </div>
                <button type="button" onclick="removeOption(this)" class="text-red-500 hover:text-red-700 p-2">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;

    optionsList.insertAdjacentHTML('beforeend', optionHtml);
    updateOptionButtons(optionsList);

    // Update navigation options after adding the option to populate block options
    setTimeout(() => {
        updateNavigationOptions();
        // Set the correct navigation value after options are populated
        if (isCustomNavigation) {
            const addedOption = optionsList.lastElementChild;
            const select = addedOption.querySelector('.option-navigation-select');
            if (select && navigationValue) {
                // Try to find and select the correct option
                const option = select.querySelector(`option[value="${navigationValue}"]`);
                if (option) {
                    select.value = navigationValue;
                } else {
                    // If the exact option doesn't exist, set to 'end' as fallback
                    select.value = 'end';
                }
            }
        }
    }, 100);
}

// Rest of the JavaScript functions (same as create.blade.php)
function addSection() {
    sectionCounter++;
    const colorClass = (sectionCounter % 2 === 0) ? 'block-color-even' : 'block-color-odd';

    const sectionHtml = `
        <div class="section-block p-6 ${colorClass}" data-section-id="${sectionCounter}">
            <div class="block-header">
                <div class="flex justify-between items-center">
                    <h4 class="block-title">Block ${sectionCounter}</h4>
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
                    <select name="sections[${sectionCounter}][navigation_type]" data-original-value="next" class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
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
                    <div class="questions-list space-y-3" id="questions-${sectionCounter}">
                        <!-- Questions will be added here -->
                    </div>
                </div>

                <!-- Add Block Button -->
                <div class="add-block-section mt-6 pt-4">
                    <div class="flex justify-center">
                        <button type="button" onclick="addSectionAfter(${sectionCounter})" class="inline-block px-6 py-3 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-gradient-to-r from-green-500 to-green-600 border-0 rounded-lg shadow-lg cursor-pointer text-sm tracking-tight-rem hover:shadow-xl hover:-translate-y-1 active:opacity-85 hover:from-green-600 hover:to-green-700">
                            <i class="fas fa-plus-circle mr-2"></i> Tambah Block Baru
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    document.getElementById('sectionsContainer').insertAdjacentHTML('beforeend', sectionHtml);
    updateNavigationOptions();
}

function addQuestion(sectionId) {
    // Use timestamp as temporary question ID to avoid conflicts
    const tempQuestionId = Date.now();

    const questionHtml = `
        <div class="question-item" data-question-id="${tempQuestionId}" data-question-number="Q${tempQuestionId}">
            <div class="flex justify-between items-center mb-3">
                <h6 class="text-sm font-semibold">Pertanyaan ${tempQuestionId}</h6>
                <div class="flex space-x-2">
                    <button type="button" onclick="cloneQuestion(${sectionId}, ${tempQuestionId})" class="text-blue-500 hover:text-blue-700" title="Clone Question">
                        <i class="fas fa-copy"></i>
                    </button>
                    <button type="button" onclick="deleteQuestion(${sectionId}, ${tempQuestionId})" class="text-red-500 hover:text-red-700" title="Delete Question">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Pertanyaan <span class="text-red-500">*</span></label>
                    <textarea name="sections[${sectionId}][questions][${tempQuestionId}][question]" rows="2" placeholder="Tulis pertanyaan disini..." required
                              class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="sections[${sectionId}][questions][${tempQuestionId}][description]" rows="2" placeholder="Deskripsi pertanyaan (opsional)"
                              class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"></textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Tipe Pertanyaan</label>
                    <select name="sections[${sectionId}][questions][${tempQuestionId}][type]" onchange="toggleOptions(${sectionId}, ${tempQuestionId})" required
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
                    <select name="sections[${sectionId}][questions][${tempQuestionId}][visualization]"
                            class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                        <option value="">Tidak ada visualisasi</option>
                        <option value="bar">Bar Chart</option>
                        <option value="pie">Pie Chart</option>
                    </select>
                </div>
                <div class="flex items-center pt-6">
                    <input type="checkbox" name="sections[${sectionId}][questions][${tempQuestionId}][required]" value="1"
                           class="mr-2 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label class="text-xs font-medium text-gray-700">Wajib diisi</label>
                </div>
            </div>

            <!-- Options Container -->
            <div class="options-container" id="options-${sectionId}-${tempQuestionId}" style="display: none;">
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-xs font-medium text-gray-700">Pilihan Jawaban</label>
                    <button type="button" onclick="addOption(${sectionId}, ${tempQuestionId})" class="bg-blue-500 text-white px-2 py-1 rounded text-xs hover:bg-blue-600 transition-colors">
                        <i class="fas fa-plus mr-1"></i>Tambah Pilihan
                    </button>
                </div>
                <div class="options-list space-y-2" id="optionsList-${sectionId}-${tempQuestionId}">
                    <!-- Options will be added here -->
                </div>
            </div>
        </div>
    `;

    document.getElementById(`questions-${sectionId}`).insertAdjacentHTML('beforeend', questionHtml);
    
    // After adding the question, reindex all sections and questions to ensure proper order
    setTimeout(() => {
        updateSectionNumbers();
        updateNavigationOptions();
    }, 50);
}

function updateQuestionNumbers(sectionId) {
    const questionsContainer = document.getElementById(`questions-${sectionId}`);
    if (questionsContainer) {
        const questions = questionsContainer.querySelectorAll('.question-item');

        questions.forEach((question, index) => {
            question.setAttribute('data-question-number', `Q${index + 1}`);

            // Update header text
            const header = question.querySelector('h6');
            if (header) {
                header.textContent = `Pertanyaan ${index + 1}`;
            }
        });
    }
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
    const optionCount = optionsList.children.length + 1;

    // Check if this question type supports navigation
    const questionTypeSelect = document.querySelector(`select[name="sections[${sectionId}][questions][${questionId}][type]"]`);
    const questionType = questionTypeSelect ? questionTypeSelect.value : 'text';
    const showNavigationToggle = ['radio', 'select'].includes(questionType);

    const optionHtml = `
        <div class="option-item mb-3" data-option-index="${optionCount}">
            <div class="flex items-start gap-2">
                <div class="option-controls">
                    <button type="button" onclick="moveOptionUp(this)" class="option-btn up" title="Move Up">
                        <i class="fas fa-chevron-up"></i>
                    </button>
                    <button type="button" onclick="moveOptionDown(this)" class="option-btn down" title="Move Down">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
                <div class="flex-1">
                    <input type="text" name="sections[${sectionId}][questions][${questionId}][options][]"
                           placeholder="Pilihan ${optionCount}" required
                           class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                    ${showNavigationToggle ? `
                    <div class="mt-2 flex items-center gap-2">
                        <label class="flex items-center cursor-pointer navigation-toggle-label">
                            <input type="checkbox" onchange="toggleOptionNavigation(this)" class="mr-2 text-blue-600 rounded focus:ring-blue-500">
                            <span class="text-xs text-gray-600">🔀 Custom navigation untuk pilihan ini</span>
                        </label>
                    </div>
                    <div class="navigation-block hidden mt-2 ml-4 px-3 py-2 bg-blue-50 border border-blue-200 rounded-lg">
                        <label class="text-xs font-medium text-blue-700 mb-1 block">Jika pilihan ini dipilih, lanjut ke:</label>
                        <select name="sections[${sectionId}][questions][${questionId}][option_navigation][]" disabled class="text-xs w-full border border-blue-300 rounded px-2 py-1 bg-white option-navigation-select" data-original-value="">
                            <option value="">Gunakan navigasi default</option>
                            <option value="next">Block Berikutnya</option>
                            <option value="end">Selesai Survey</option>
                        </select>
                        <input type="hidden" name="sections[${sectionId}][questions][${questionId}][option_navigation][]" value="next" class="hidden-navigation-input">
                    </div>
                    ` : ''}
                </div>
                <button type="button" onclick="removeOption(this)" class="text-red-500 hover:text-red-700 p-2">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;

    optionsList.insertAdjacentHTML('beforeend', optionHtml);
    updateOptionButtons(optionsList);
    updateOptionPlaceholders(optionsList);

    // Update navigation options for the new option
    if (showNavigationToggle) {
        updateNavigationOptions();
    }
}

// Toggle option navigation
function toggleOptionNavigation(checkbox) {
    const optionItem = checkbox.closest('.option-item');
    const navigationBlock = optionItem.querySelector('.navigation-block');
    const navigationSelect = navigationBlock.querySelector('select');
    const hiddenInput = navigationBlock.querySelector('.hidden-navigation-input');

    if (checkbox.checked) {
        navigationBlock.classList.remove('hidden');
        navigationBlock.classList.add('block');
        // Enable the select element and disable hidden input
        navigationSelect.disabled = false;
        if (hiddenInput) hiddenInput.disabled = true;
    } else {
        navigationBlock.classList.remove('block');
        navigationBlock.classList.add('hidden');
        // Reset and disable select, enable hidden input with default value
        navigationSelect.value = '';
        navigationSelect.disabled = true;
        navigationSelect.setAttribute('data-original-value', '');
        if (hiddenInput) {
            hiddenInput.disabled = false;
            hiddenInput.value = 'next';
        }
    }
}

// Add event listener for navigation select changes
document.addEventListener('change', function(e) {
    if (e.target.matches('.option-navigation-select')) {
        // Update data-original-value when user changes the selection
        e.target.setAttribute('data-original-value', e.target.value);
    }

    // Also handle block navigation selects
    if (e.target.matches('select[name*="[navigation_type]"]')) {
        // Update data-original-value when user changes the block navigation
        e.target.setAttribute('data-original-value', e.target.value);
    }
});

// Update existing options when question type changes
function updateExistingOptionsNavigation(sectionId, questionId, questionType) {
    const optionsList = document.getElementById(`options-list-${sectionId}-${questionId}`);
    const options = optionsList.querySelectorAll('.option-item');
    const showNavigationToggle = ['radio', 'select'].includes(questionType);

    options.forEach((option, index) => {
        // Remove existing navigation toggle and block if any
        const existingToggle = option.querySelector('input[type="checkbox"]');
        const existingNav = option.querySelector('.navigation-block');

        if (existingToggle && existingToggle.closest('.flex.items-center')) {
            existingToggle.closest('.flex.items-center').remove();
        }
        if (existingNav) {
            existingNav.remove();
        }

        // Add navigation toggle and block if needed
        if (showNavigationToggle) {
            const inputElement = option.querySelector('input[type="text"]');
            const navigationHtml = `
                <div class="mt-2 flex items-center gap-2">
                    <label class="flex items-center cursor-pointer navigation-toggle-label">
                        <input type="checkbox" onchange="toggleOptionNavigation(this)" class="mr-2 text-blue-600 rounded focus:ring-blue-500">
                        <span class="text-xs text-gray-600">🔀 Custom navigation untuk pilihan ini</span>
                    </label>
                </div>
                <div class="navigation-block hidden mt-2 ml-4 px-3 py-2 bg-blue-50 border border-blue-200 rounded-lg">
                    <label class="text-xs font-medium text-blue-700 mb-1 block">Jika pilihan ini dipilih, lanjut ke:</label>
                    <select name="sections[${sectionId}][questions][${questionId}][option_navigation][]" disabled class="text-xs w-full border border-blue-300 rounded px-2 py-1 bg-white option-navigation-select">
                        <option value="">Gunakan navigasi default</option>
                        <option value="next">Block Berikutnya</option>
                        <option value="end">Selesai Survey</option>
                    </select>
                </div>
            `;

            inputElement.insertAdjacentHTML('afterend', navigationHtml);
        }
    });

    // Update navigation options
    if (showNavigationToggle) {
        updateNavigationOptions();
    }
}

function deleteSection(sectionId) {
    if (confirm('Apakah Anda yakin ingin menghapus block ini?')) {
        document.querySelector(`[data-section-id="${sectionId}"]`).remove();
        
        // Update all section numbers and form names to be sequential
        updateSectionNumbers();
        updateNavigationOptions();
    }
}

function deleteQuestion(sectionId, questionId) {
    const questionsContainer = document.getElementById(`questions-${sectionId}`);
    if (questionsContainer) {
        const questions = questionsContainer.querySelectorAll('.question-item');

        if (questions.length <= 1) {
            alert('Setiap block minimal harus memiliki 1 pertanyaan!');
            return;
        }

        if (confirm('Apakah Anda yakin ingin menghapus pertanyaan ini?')) {
            const question = questionsContainer.querySelector(`[data-question-id="${questionId}"]`);
            if (question) {
                question.remove();
                
                // After deleting, reindex all sections and questions to ensure proper order
                setTimeout(() => {
                    updateSectionNumbers();
                    updateNavigationOptions();
                }, 50);
            }
        }
    }
}

function cloneSection(sectionId) {
    const originalSection = document.querySelector(`[data-section-id="${sectionId}"]`);
    if (originalSection) {
        // Create temporary section ID for initial creation
        const tempSectionId = Date.now(); // Use timestamp as temp ID
        const colorClass = 'block-color-odd'; // Will be updated by updateSectionNumbers()

        // Get all form data from the original section
        const originalData = getFormDataFromSection(originalSection, sectionId);

        const clonedHtml = originalSection.outerHTML
            .replace(new RegExp(`sections\\[${sectionId}\\]`, 'g'), `sections[${tempSectionId}]`)
            .replace(new RegExp(`data-section-id="${sectionId}"`, 'g'), `data-section-id="${tempSectionId}"`)
            .replace(new RegExp(`Block ${sectionId}`, 'g'), `Block ${tempSectionId}`)
            .replace(new RegExp(`questions-${sectionId}`, 'g'), `questions-${tempSectionId}`)
            .replace(new RegExp(`deleteSection\\(${sectionId}\\)`, 'g'), `deleteSection(${tempSectionId})`)
            .replace(new RegExp(`cloneSection\\(${sectionId}\\)`, 'g'), `cloneSection(${tempSectionId})`)
            .replace(new RegExp(`addQuestion\\(${sectionId}\\)`, 'g'), `addQuestion(${tempSectionId})`)
            .replace(new RegExp(`addSectionAfter\\(${sectionId}\\)`, 'g'), `addSectionAfter(${tempSectionId})`)
            .replace(/block-color-\w+/, colorClass); // Replace color class

        originalSection.insertAdjacentHTML('afterend', clonedHtml);

        // Update all sections after cloning and restore form data
        setTimeout(() => {
            // Find the cloned section by its position
            const clonedSection = originalSection.nextElementSibling;
            if (clonedSection && clonedSection.classList.contains('section-block')) {
                const clonedSectionId = clonedSection.getAttribute('data-section-id');
                
                // Update all section numbers and form names to be sequential
                updateSectionNumbers();
                updateNavigationOptions();
                
                // Restore form data to cloned section using new sequential ID
                const newSectionId = clonedSection.getAttribute('data-section-id');
                restoreFormDataToSection(newSectionId, originalData);
            }
        }, 100);
    }
}

function cloneQuestion(sectionId, questionId) {
    const originalQuestion = document.querySelector(`#questions-${sectionId} [data-question-id="${questionId}"]`);
    if (originalQuestion) {
        console.log('Cloning question:', questionId);

        // Get original question data
        const originalData = {
            question: originalQuestion.querySelector(`textarea[name*="[question]"]`)?.value || '',
            description: originalQuestion.querySelector(`textarea[name*="[description]"]`)?.value || '',
            type: originalQuestion.querySelector(`select[name*="[type]"]`)?.value || 'text',
            required: originalQuestion.querySelector(`input[name*="[required]"]`)?.checked || false,
            visualization: originalQuestion.querySelector(`select[name*="[visualization]"]`)?.value || '',
            options: [],
            optionNavigation: []
        };

        // Get options and their navigation values
        const optionInputs = originalQuestion.querySelectorAll('input[name*="[options]"]');
        optionInputs.forEach((input, index) => {
            if (input.value.trim()) {
                originalData.options.push(input.value.trim());

                // Get navigation value for this option
                const optionItem = input.closest('.option-item');
                const navSelect = optionItem ? optionItem.querySelector('.option-navigation-select') : null;
                const hiddenNav = optionItem ? optionItem.querySelector('.hidden-navigation-input') : null;

                if (navSelect && navSelect.value) {
                    originalData.optionNavigation.push(navSelect.value);
                } else if (hiddenNav && hiddenNav.value) {
                    originalData.optionNavigation.push(hiddenNav.value);
                } else {
                    originalData.optionNavigation.push('next');
                }
            }
        });

        console.log('Original question data:', originalData);

        // Create temporary question using timestamp
        const tempQuestionId = Date.now();
        
        // Clone the HTML and insert it after the original question
        const clonedHtml = originalQuestion.outerHTML
            .replace(new RegExp(`data-question-id="${questionId}"`, 'g'), `data-question-id="${tempQuestionId}"`)
            .replace(new RegExp(`data-question-number="Q\\d+"`, 'g'), `data-question-number="Q${tempQuestionId}"`)
            .replace(new RegExp(`Pertanyaan \\d+`, 'g'), `Pertanyaan ${tempQuestionId}`)
            .replace(new RegExp(`cloneQuestion\\(${sectionId}, ${questionId}\\)`, 'g'), `cloneQuestion(${sectionId}, ${tempQuestionId})`)
            .replace(new RegExp(`deleteQuestion\\(${sectionId}, ${questionId}\\)`, 'g'), `deleteQuestion(${sectionId}, ${tempQuestionId})`)
            .replace(new RegExp(`toggleOptions\\(${sectionId}, ${questionId}\\)`, 'g'), `toggleOptions(${sectionId}, ${tempQuestionId})`)
            .replace(new RegExp(`addOption\\(${sectionId}, ${questionId}\\)`, 'g'), `addOption(${sectionId}, ${tempQuestionId})`)
            .replace(new RegExp(`options-${sectionId}-${questionId}`, 'g'), `options-${sectionId}-${tempQuestionId}`)
            .replace(new RegExp(`optionsList-${sectionId}-${questionId}`, 'g'), `optionsList-${sectionId}-${tempQuestionId}`);

        // Insert the cloned question after the original
        originalQuestion.insertAdjacentHTML('afterend', clonedHtml);

        // After cloning, reindex all sections and questions to ensure proper order
        setTimeout(() => {
            updateSectionNumbers();
            updateNavigationOptions();
            
            // Find the cloned question by its position (should be right after original)
            const clonedQuestion = originalQuestion.nextElementSibling;
            if (clonedQuestion && clonedQuestion.classList.contains('question-item')) {
                console.log('Restoring data to cloned question');
                
                // Restore the original form values to the cloned question
                const questionInput = clonedQuestion.querySelector(`textarea[name*="[question]"]`);
                if (questionInput) {
                    questionInput.value = originalData.question;
                }

                const descInput = clonedQuestion.querySelector(`textarea[name*="[description]"]`);
                if (descInput) {
                    descInput.value = originalData.description;
                }

                const typeSelect = clonedQuestion.querySelector(`select[name*="[type]"]`);
                if (typeSelect) {
                    typeSelect.value = originalData.type;
                    typeSelect.dispatchEvent(new Event('change'));
                }

                const requiredInput = clonedQuestion.querySelector(`input[name*="[required]"]`);
                if (requiredInput) {
                    requiredInput.checked = originalData.required;
                }

                const vizSelect = clonedQuestion.querySelector(`select[name*="[visualization]"]`);
                if (vizSelect) {
                    vizSelect.value = originalData.visualization;
                }

                console.log('Question cloned and data restored successfully');
            }
        }, 100);
    }
}

function getFormDataFromSection(sectionElement, sectionId) {
    const data = {
        sectionName: sectionElement.querySelector(`input[name="sections[${sectionId}][section_name]"]`)?.value || '',
        sectionDescription: sectionElement.querySelector(`textarea[name="sections[${sectionId}][section_description]"]`)?.value || '',
        navigationType: sectionElement.querySelector(`select[name="sections[${sectionId}][navigation_type]"]`)?.value || 'next',
        questions: []
    };

    // Get questions data
    const questions = sectionElement.querySelectorAll('.question-item');
    questions.forEach((questionEl, index) => {
        const questionData = {
            question: questionEl.querySelector(`textarea[name*="[question]"]`)?.value || '',
            description: questionEl.querySelector(`textarea[name*="[description]"]`)?.value || '',
            type: questionEl.querySelector(`select[name*="[type]"]`)?.value || 'text',
            required: questionEl.querySelector(`input[name*="[required]"]`)?.checked || false,
            visualization: questionEl.querySelector(`select[name*="[visualization]"]`)?.value || '',
            options: []
        };

        // Get options
        const optionInputs = questionEl.querySelectorAll('input[name*="[options]"]');
        optionInputs.forEach(input => {
            if (input.value.trim()) {
                questionData.options.push(input.value.trim());
            }
        });

        data.questions.push(questionData);
    });

    return data;
}

function restoreFormDataToSection(sectionId, data) {
    const section = document.querySelector(`[data-section-id="${sectionId}"]`);
    if (!section) return;

    // Restore section data
    const nameInput = section.querySelector(`input[name="sections[${sectionId}][section_name]"]`);
    if (nameInput) nameInput.value = data.sectionName;

    const descInput = section.querySelector(`textarea[name="sections[${sectionId}][section_description]"]`);
    if (descInput) descInput.value = data.sectionDescription;

    const navSelect = section.querySelector(`select[name="sections[${sectionId}][navigation_type]"]`);
    if (navSelect) {
        navSelect.value = data.navigationType;
        // Trigger change event
        navSelect.dispatchEvent(new Event('change'));
    }    // Clear existing questions and add cloned questions
    const questionsContainer = section.querySelector(`#questions-${sectionId}`);
    if (questionsContainer) {
        questionsContainer.innerHTML = '';

        data.questions.forEach((questionData, index) => {
            // Add question first
            addQuestion(sectionId);

            // Restore question data with proper timing
            setTimeout(() => {
                const questionCount = index + 1;
                const questionElements = questionsContainer.querySelectorAll('.question-item');
                const questionEl = questionElements[index]; // Use index instead of data-question-id

                if (questionEl) {
                    console.log('Restoring question data:', questionData);

                    const questionInput = questionEl.querySelector(`textarea[name="sections[${sectionId}][questions][${questionCount}][question]"]`);
                    if (questionInput) {
                        questionInput.value = questionData.question;
                        console.log('Set question text:', questionData.question);
                    }

                    const descInput = questionEl.querySelector(`textarea[name="sections[${sectionId}][questions][${questionCount}][description]"]`);
                    if (descInput) {
                        descInput.value = questionData.description;
                        console.log('Set description:', questionData.description);
                    }

                    const typeSelect = questionEl.querySelector(`select[name="sections[${sectionId}][questions][${questionCount}][type]"]`);
                    if (typeSelect) {
                        typeSelect.value = questionData.type;
                        // Trigger change event for options container
                        typeSelect.dispatchEvent(new Event('change'));
                        console.log('Set type:', questionData.type);
                    }

                    const requiredInput = questionEl.querySelector(`input[name="sections[${sectionId}][questions][${questionCount}][required]"]`);
                    if (requiredInput) {
                        requiredInput.checked = questionData.required;
                        console.log('Set required:', questionData.required);
                    }

                    const vizSelect = questionEl.querySelector(`select[name="sections[${sectionId}][questions][${questionCount}][visualization]"]`);
                    if (vizSelect) {
                        vizSelect.value = questionData.visualization;
                        console.log('Set visualization:', questionData.visualization);
                    }

                    // Add options if needed
                    if (['radio', 'checkbox', 'select'].includes(questionData.type) && questionData.options.length > 0) {
                        setTimeout(() => {
                            const optionsList = questionEl.querySelector(`#optionsList-${sectionId}-${questionCount}`);
                            if (optionsList) {
                                optionsList.innerHTML = '';
                                questionData.options.forEach((optionText, optIndex) => {
                                    addOption(sectionId, questionCount);
                                    // Set option value after a short delay
                                    setTimeout(() => {
                                        const optionInputs = optionsList.querySelectorAll('input[type="text"]');
                                        if (optionInputs[optIndex]) {
                                            optionInputs[optIndex].value = optionText;
                                            console.log('Set option:', optionText);
                                        }
                                    }, 50);
                                });
                            }
                        }, 300);
                    }
                }
            }, 200 * (index + 1)); // Stagger the restoration
        });
    }
}
function updateSectionNumbers() {
    const sections = document.querySelectorAll('.section-block');
    
    sections.forEach((section, index) => {
        const newSectionId = index + 1;
        const oldSectionId = section.getAttribute('data-section-id');
        
        // Update data-section-id attribute
        section.setAttribute('data-section-id', newSectionId);
        
        // Update visual header
        const header = section.querySelector('.block-header h4');
        if (header) {
            header.textContent = `Block ${newSectionId}`;
        }

        // Update color class
        const colorClass = (newSectionId % 2 === 0) ? 'block-color-even' : 'block-color-odd';
        section.className = section.className.replace(/block-color-\w+/, colorClass);
        
        // Update all form field names in this section
        updateSectionFormNames(section, oldSectionId, newSectionId);
        
        // Update all onclick handlers for this section
        updateSectionOnclickHandlers(section, oldSectionId, newSectionId);
    });
}

function updateSectionFormNames(section, oldId, newId) {
    // Update section-level form names
    const sectionInputs = section.querySelectorAll(`[name*="sections[${oldId}]"]`);
    sectionInputs.forEach(input => {
        input.name = input.name.replace(`sections[${oldId}]`, `sections[${newId}]`);
    });
    
    // Update question container ID
    const questionsContainer = section.querySelector(`#questions-${oldId}`);
    if (questionsContainer) {
        questionsContainer.id = `questions-${newId}`;
    }
    
    // Update questions form names and reindex them sequentially
    const questions = section.querySelectorAll('.question-item');
    questions.forEach((question, questionIndex) => {
        const newQuestionId = questionIndex + 1;
        const oldQuestionId = question.getAttribute('data-question-id');
        
        // Update question data attribute
        question.setAttribute('data-question-id', newQuestionId);
        
        // Update question number display
        question.setAttribute('data-question-number', `Q${newQuestionId}`);
        
        // Update question header text
        const questionHeader = question.querySelector('h6');
        if (questionHeader) {
            questionHeader.textContent = `Pertanyaan ${newQuestionId}`;
        }
        
        // Update all question form field names
        const questionInputs = question.querySelectorAll(`[name*="sections[${newId}][questions]"]`);
        questionInputs.forEach(input => {
            // Replace the old question index with new sequential index
            input.name = input.name.replace(
                new RegExp(`sections\\[${newId}\\]\\[questions\\]\\[\\d+\\]`), 
                `sections[${newId}][questions][${questionIndex}]`
            );
        });
        
        // Update onclick handlers for questions
        updateQuestionOnclickHandlers(question, newId, oldQuestionId, newQuestionId);
    });
}

function updateSectionOnclickHandlers(section, oldId, newId) {
    // Update clone section button
    const cloneBtn = section.querySelector(`[onclick*="cloneSection(${oldId})"]`);
    if (cloneBtn) {
        cloneBtn.setAttribute('onclick', `cloneSection(${newId})`);
    }
    
    // Update delete section button
    const deleteBtn = section.querySelector(`[onclick*="deleteSection(${oldId})"]`);
    if (deleteBtn) {
        deleteBtn.setAttribute('onclick', `deleteSection(${newId})`);
    }
    
    // Update add question button
    const addQuestionBtn = section.querySelector(`[onclick*="addQuestion(${oldId})"]`);
    if (addQuestionBtn) {
        addQuestionBtn.setAttribute('onclick', `addQuestion(${newId})`);
    }
    
    // Update add section after button
    const addSectionBtn = section.querySelector(`[onclick*="addSectionAfter(${oldId})"]`);
    if (addSectionBtn) {
        addSectionBtn.setAttribute('onclick', `addSectionAfter(${newId})`);
    }
}

function updateQuestionOnclickHandlers(question, sectionId, oldQuestionId, newQuestionId) {
    // Update clone question button
    const cloneBtn = question.querySelector(`[onclick*="cloneQuestion(${sectionId}, ${oldQuestionId})"]`);
    if (cloneBtn) {
        cloneBtn.setAttribute('onclick', `cloneQuestion(${sectionId}, ${newQuestionId})`);
    }
    
    // Update delete question button
    const deleteBtn = question.querySelector(`[onclick*="deleteQuestion(${sectionId}, ${oldQuestionId})"]`);
    if (deleteBtn) {
        deleteBtn.setAttribute('onclick', `deleteQuestion(${sectionId}, ${newQuestionId})`);
    }
}
function updateNavigationOptions() {
    const sections = document.querySelectorAll('.section-block');
    const sectionCount = sections.length;

    sections.forEach((section, index) => {
        // Update the main navigation select to show direct block options
        const navSelect = section.querySelector('select[name*="[navigation_type]"]');
        if (navSelect) {
            // Get original value from data attribute or current value
            const originalValue = navSelect.getAttribute('data-original-value');
            const currentValue = navSelect.value;
            const valueToRestore = originalValue || currentValue || 'next';

            let optionsHtml = '<option value="next">Block Berikutnya</option>';

            // Add specific block options
            for (let i = 1; i <= sectionCount; i++) {
                if (i !== index + 1) { // Don't allow navigating to self
                    optionsHtml += `<option value="block_${i}">Ke Block ${i}</option>`;
                }
            }

            // Add "Selesaikan Survey" option
            optionsHtml += `<option value="end">Selesaikan Survey</option>`;

            navSelect.innerHTML = optionsHtml;

            // Restore previous value if it still exists
            if (valueToRestore && navSelect.querySelector(`option[value="${valueToRestore}"]`)) {
                navSelect.value = valueToRestore;
                navSelect.setAttribute('data-original-value', valueToRestore);
            } else {
                navSelect.value = 'next';
                navSelect.setAttribute('data-original-value', 'next');
            }
        }

        // Update option navigation selects within this section
        const optionNavSelects = section.querySelectorAll('.option-navigation-select');
        optionNavSelects.forEach(optionNavSelect => {
            // Get the original value from data attribute, current value, or hidden input
            const originalValue = optionNavSelect.getAttribute('data-original-value');
            const currentValue = optionNavSelect.value;
            const optionItem = optionNavSelect.closest('.option-item');
            const hiddenInput = optionItem ? optionItem.querySelector('.hidden-navigation-input') : null;
            const hiddenValue = hiddenInput ? hiddenInput.value : '';

            // Prioritize: originalValue > currentValue > hiddenValue
            const valueToRestore = originalValue || currentValue || hiddenValue || '';

            // Build option navigation options
            let optionsHtml = '<option value="">Gunakan navigasi default</option>';
            optionsHtml += '<option value="next">Block Berikutnya</option>';

            // Add specific block options
            for (let i = 1; i <= sectionCount; i++) {
                optionsHtml += `<option value="block_${i}">Ke Block ${i}</option>`;
            }

            optionsHtml += '<option value="end">Selesai Survey</option>';

            optionNavSelect.innerHTML = optionsHtml;

            // Restore the value
            if (valueToRestore && optionNavSelect.querySelector(`option[value="${valueToRestore}"]`)) {
                optionNavSelect.value = valueToRestore;
                // Update the data attribute to keep track
                optionNavSelect.setAttribute('data-original-value', valueToRestore);
            } else {
                optionNavSelect.value = '';
                optionNavSelect.setAttribute('data-original-value', '');
            }

            // Update hidden input to match if it exists
            if (hiddenInput) {
                hiddenInput.value = optionNavSelect.value || 'next';
            }
        });
    });
}

// Function to add a section after a specific section (for the "Tambah Block Baru" button)
function addSectionAfter(afterSectionId) {
    // Get reference to the section we're inserting after
    const currentSection = document.querySelector(`[data-section-id="${afterSectionId}"]`);
    if (!currentSection) {
        console.error('Could not find section to insert after:', afterSectionId);
        return;
    }
    
    // Create temporary section ID for initial creation
    const tempSectionId = Date.now(); // Use timestamp as temp ID
    const colorClass = 'block-color-odd'; // Will be updated by updateSectionNumbers()

    const sectionHtml = `
        <div class="section-block p-6 ${colorClass}" data-section-id="${tempSectionId}">
            <div class="block-header">
                <div class="flex justify-between items-center">
                    <h4 class="block-title">Block ${tempSectionId}</h4>
                    <div class="icon-container">
                        <button type="button" onclick="cloneSection(${tempSectionId})" class="icon-link" title="Clone Block">
                            <i class="fas fa-copy"></i>
                        </button>
                        <button type="button" onclick="deleteSection(${tempSectionId})" class="icon-link" title="Delete Block">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <!-- Block Info -->
                <div class="bg-gray-50 p-4 rounded-lg mb-4">
                    <h5 class="text-sm font-medium text-gray-700 mb-3">Nama Block <span class="text-red-500">*</span></h5>
                    <input type="text" name="sections[${tempSectionId}][section_name]" placeholder="Tulis nama blok disini..." required
                           class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                </div>

                <div class="bg-gray-50 p-4 rounded-lg mb-4">
                    <h5 class="text-sm font-medium text-gray-700 mb-3">Deskripsi Block</h5>
                    <textarea name="sections[${tempSectionId}][section_description]" rows="2" placeholder="Tulis deskripsi blok disini..."
                              class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"></textarea>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg mb-4">
                    <h5 class="text-sm font-medium text-gray-700 mb-3">Navigasi Block</h5>
                    <select name="sections[${tempSectionId}][navigation_type]" data-original-value="next" class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                        <option value="next">Lanjut ke block berikutnya</option>
                        <option value="end">Akhiri survey</option>
                    </select>
                </div>

                <!-- Questions Container -->
                <div class="questions-container" data-section-id="${tempSectionId}">
                    <div class="flex justify-between items-center mb-4">
                        <h5 class="text-sm font-medium text-gray-700">Pertanyaan</h5>
                        <button type="button" onclick="addQuestion(${tempSectionId})" class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600 transition-colors">
                            <i class="fas fa-plus mr-1"></i>Tambah Pertanyaan
                        </button>
                    </div>
                    <div class="questions-list space-y-3" id="questions-${tempSectionId}">
                        <!-- Questions will be added here -->
                    </div>
                </div>

                <!-- Add Block Button -->
                <div class="add-block-section mt-6 pt-4">
                    <div class="flex justify-center">
                        <button type="button" onclick="addSectionAfter(${tempSectionId})" class="inline-block px-6 py-3 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-gradient-to-r from-green-500 to-green-600 border-0 rounded-lg shadow-lg cursor-pointer text-sm tracking-tight-rem hover:shadow-xl hover:-translate-y-1 active:opacity-85 hover:from-green-600 hover:to-green-700">
                            <i class="fas fa-plus-circle mr-2"></i> Tambah Block Baru
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Insert the new section after the current section
    currentSection.insertAdjacentHTML('afterend', sectionHtml);

    // Now update all section numbers and form names to be sequential
    setTimeout(() => {
        updateSectionNumbers();
        updateNavigationOptions();
        
        // Add first question to the newly created section
        // Find the new section by its position (it should be right after currentSection)
        const newSection = currentSection.nextElementSibling;
        if (newSection && newSection.classList.contains('section-block')) {
            const newSectionId = newSection.getAttribute('data-section-id');
            addQuestion(newSectionId);
        }
    }, 100);
}

// Function to move option up
function moveOptionUp(button) {
    const optionItem = button.closest('.option-item');
    const previousOption = optionItem.previousElementSibling;

    if (previousOption) {
        optionItem.parentNode.insertBefore(optionItem, previousOption);
        const optionsList = optionItem.closest('[id*="optionsList"]') || optionItem.parentNode;
        updateOptionButtons(optionsList);
        updateOptionPlaceholders(optionsList);
    }
}

// Function to move option down
function moveOptionDown(button) {
    const optionItem = button.closest('.option-item');
    const nextOption = optionItem.nextElementSibling;

    if (nextOption) {
        optionItem.parentNode.insertBefore(nextOption, optionItem);
        const optionsList = optionItem.closest('[id*="optionsList"]') || optionItem.parentNode;
        updateOptionButtons(optionsList);
        updateOptionPlaceholders(optionsList);
    }
}

// Function to remove option
function removeOption(button) {
    const optionItem = button.closest('.option-item');
    const optionsList = optionItem.closest('[id*="optionsList"]') || optionItem.parentNode;

    optionItem.remove();
    updateOptionButtons(optionsList);
    updateOptionPlaceholders(optionsList);
}

// Function to update option buttons (enable/disable based on position)
function updateOptionButtons(optionsList) {
    const options = optionsList.querySelectorAll('.option-item');

    options.forEach((option, index) => {
        const upButton = option.querySelector('.option-btn.up');
        const downButton = option.querySelector('.option-btn.down');

        if (upButton) {
            upButton.disabled = index === 0;
        }
        if (downButton) {
            downButton.disabled = index === options.length - 1;
        }

        // Update data-option-index
        option.setAttribute('data-option-index', index + 1);
    });
}

function updateOptionPlaceholders(optionsList) {
    const options = optionsList.querySelectorAll('.option-item');

    options.forEach((option, index) => {
        const input = option.querySelector('input[type="text"]');
        if (input) {
            input.placeholder = `Pilihan ${index + 1}`;
        }
    });
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
