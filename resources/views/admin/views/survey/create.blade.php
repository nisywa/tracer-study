@extends('admin.layouts.app')
@section('title', 'Tambah Survei')

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
<!-- table 1 -->

<form action="{{ route('admin.survey.store') }}" method="POST">
  @csrf
<div class="flex flex-wrap -mx-3">
          <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                <div class="flex flex-wrap -mx-3">
                    <!-- form start -->
                    <div class="flex-auto p-6">

                        <hr class="h-px mx-0 my-4 bg-transparent border-0 opacity-25 bg-gradient-to-r from-transparent via-black/40 to-transparent dark:bg-gradient-to-r dark:from-transparent dark:via-white dark:to-transparent " />

                        <p class="leading-normal uppercase dark:text-white dark:opacity-60 text-sm">Informasi Survei</p>
                        <div class="flex flex-wrap -mx-3">

                          <div class="w-full max-w-full px-3 shrink-0 md:w-full md:flex-0">
                            <div class="mb-4">
                              <label for="nama" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Nama Survei <span class="text-red-500">*</span></label>
                              <input type="text" name="nama" value="{{ old('nama') }}" required class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                              @error('nama')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                          <div class="w-full max-w-full px-3 shrink-0 md:w-4/12 md:flex-0">
                            <div class="mb-4">
                              <label for="tanggal_mulai" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Tanggal Mulai <span class="text-red-500">*</span></label>
                              <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                              @error('tanggal_mulai')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                          <div class="w-full max-w-full px-3 shrink-0 md:w-4/12 md:flex-0">
                            <div class="mb-4">
                              <label for="tanggal_selesai" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Tanggal Selesai <span class="text-red-500">*</span></label>
                              <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                              @error('tanggal_selesai')
                                  <span class="text-red-500 text-xs">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                          <div class="w-full max-w-full px-3 shrink-0 md:w-4/12 md:flex-0">
                                <label for="type_survei" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Tipe <span class="text-red-500">*</span></label>
                                <select name="type_survei" required class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                                    <option value="" disabled selected hidden>Pilih Tipe</option>
                                    <option value="atasan" {{ old('type_survei') == 'atasan' ? 'selected' : '' }}>Pengguna Lulusan</option>
                                    <option value="alumni" {{ old('type_survei') == 'alumni' ? 'selected' : '' }}>Lulusan</option>
                                </select>
                                @error('type_survei')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                          <div class="w-full max-w-full px-3 shrink-0 md:w-full md:flex-0">
                            <div class="mb-4">
                              <label for="deskripsi" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Deskripsi</label>
                              <input type="text" name="deskripsi" value="{{ old('deskripsi') }}" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                            </div>
                            @error('deskripsi')
                                  <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                          </div>
                        </div>

                        <!-- Form Builder Section -->
                        <hr class="h-px mx-0 my-4 bg-transparent border-0 opacity-25 bg-gradient-to-r from-transparent via-black/40 to-transparent dark:bg-gradient-to-r dark:from-transparent dark:via-white dark:to-transparent" />

                        <div class="flex justify-between items-center mb-4">
                            <p class="leading-normal uppercase dark:text-white dark:opacity-60 text-sm">Form Builder - Blocks & Questions</p>
                            <button type="button" id="addSectionBtn" class="inline-block px-4 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-green-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                                <i class="fas fa-plus mr-2"></i> Tambah Block
                            </button>
                        </div>

                        <!-- Sections Container -->
                        <div id="sectionsContainer" class="space-y-6">
                            <!-- Sections will be added here dynamically -->
                        </div>

                        <div class="flex justify-end items-center mt-6 gap-4">
                            <a href="{{ route('admin.survey.index') }}" class="inline-block px-8 py-2 w-36 h-10 font-bold text-center align-middle transition-all ease-in border border-gray-300 rounded-lg text-gray-700 bg-transparent hover:bg-gray-100 text-xs tracking-tight-rem cursor-pointer">Batal</a>
                            <button type="submit" class="inline-block px-8 py-2 w-36 h-10 font-bold text-center text-white align-middle transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">Simpan</button>
                        </div>

                    </div>
                    <!-- form end -->


                </div>


            </div>
          </div>
        </div>
 </form>

@push('scripts')
<script>
let sectionCounter = 0;

document.addEventListener('DOMContentLoaded', function() {
    // Add first section by default
    addSection();

    // Add section button event
    document.getElementById('addSectionBtn').addEventListener('click', addSection);

    // Update navigation options initially
    setTimeout(() => updateNavigationOptions(), 100);

    // Add form validation before submit
    const form = document.querySelector('form');
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
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';

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
                    showSuccessMessage('Survey berhasil dibuat!');
                    setTimeout(() => {
                        window.location.href = "{{ route('admin.survey.index') }}";
                    }, 1500);
                } else {
                    // Error response with JSON data
                    throw new Error(data.message || 'Terjadi kesalahan saat menyimpan survey');
                }
            })
            .catch(error => {
                console.error('Error:', error);

                // Show specific error message
                let errorMessage = 'Terjadi kesalahan saat menyimpan survey.';
                if (error.message && error.message !== 'Terjadi kesalahan saat menyimpan survey') {
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
                    <select name="sections[${sectionCounter}][navigation_type]" class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none navigation-select">
                        <!-- Navigation options will be populated by updateNavigationOptions() -->
                    </select>
                </div>

                <!-- Questions Section -->
                <div class="border-t-2 border-gray-200 pt-4">
                    <div class="flex justify-between items-center mb-4">
                        <h5 class="text-md font-medium text-gray-700">Pertanyaan</h5>
                        <button type="button" onclick="addQuestion(${sectionCounter})" class="inline-block px-3 py-1 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                            <i class="fas fa-plus mr-1"></i> Tambah Pertanyaan
                        </button>
                    </div>

                    <div id="questionsContainer-${sectionCounter}" class="questions-container space-y-3">
                        <!-- Questions will be added here -->
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
        </div>
    `;

    document.getElementById('sectionsContainer').insertAdjacentHTML('beforeend', sectionHtml);

    // Add first question to new section
    setTimeout(() => {
        addQuestion(sectionCounter);
        updateNavigationOptions();
    }, 100);
}

function addSectionAfter(afterSectionId) {
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
                    <select name="sections[${sectionCounter}][navigation_type]" class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none navigation-select">
                        <!-- Navigation options will be populated by updateNavigationOptions() -->
                    </select>
                </div>

                <!-- Questions Section -->
                <div class="border-t-2 border-gray-200 pt-4">
                    <div class="flex justify-between items-center mb-4">
                        <h5 class="text-md font-medium text-gray-700">Pertanyaan</h5>
                        <button type="button" onclick="addQuestion(${sectionCounter})" class="inline-block px-3 py-1 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                            <i class="fas fa-plus mr-1"></i> Tambah Pertanyaan
                        </button>
                    </div>

                    <div id="questionsContainer-${sectionCounter}" class="questions-container space-y-3">
                        <!-- Questions will be added here -->
                    </div>

                    <!-- Add Block Button -->
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <div class="flex justify-center">
                            <button type="button" onclick="addSectionAfter(${sectionCounter})" class="inline-block px-6 py-3 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-gradient-to-r from-green-500 to-green-600 border-0 rounded-lg shadow-lg cursor-pointer text-sm tracking-tight-rem hover:shadow-xl hover:-translate-y-1 active:opacity-85 hover:from-green-600 hover:to-green-700">
                                <i class="fas fa-plus-circle mr-2"></i> Tambah Block Baru
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Find the section to insert after
    const afterSection = document.querySelector(`[data-section-id="${afterSectionId}"]`);
    if (afterSection) {
        afterSection.insertAdjacentHTML('afterend', sectionHtml);
    } else {
        // Fallback: add to end if section not found
        document.getElementById('sectionsContainer').insertAdjacentHTML('beforeend', sectionHtml);
    }

    // Add first question to new section and update everything
    setTimeout(() => {
        addQuestion(sectionCounter);
        updateSectionNumbers();
        updateNavigationOptions();
    }, 100);
}

function deleteSection(sectionId) {
    if (document.querySelectorAll('.section-block').length <= 1) {
        alert('Minimal harus ada 1 block!');
        return;
    }

    if (confirm('Apakah Anda yakin ingin menghapus block ini?')) {
        const section = document.querySelector(`[data-section-id="${sectionId}"]`);
        if (section) {
            section.remove();
            updateSectionNumbers();
            updateNavigationOptions();
        }
    }
}

function cloneSection(sectionId) {
    const originalSection = document.querySelector(`[data-section-id="${sectionId}"]`);
    if (originalSection) {
        sectionCounter++;
        const colorClass = (sectionCounter % 2 === 0) ? 'block-color-even' : 'block-color-odd';

        // Get all form data from the original section
        const originalData = getFormDataFromSection(originalSection, sectionId);

        const clonedHtml = originalSection.outerHTML
            .replace(new RegExp(`sections\\[${sectionId}\\]`, 'g'), `sections[${sectionCounter}]`)
            .replace(new RegExp(`data-section-id="${sectionId}"`, 'g'), `data-section-id="${sectionCounter}"`)
            .replace(new RegExp(`Block ${sectionId}`, 'g'), `Block ${sectionCounter}`)
            .replace(new RegExp(`questionsContainer-${sectionId}`, 'g'), `questionsContainer-${sectionCounter}`)
            .replace(new RegExp(`deleteSection\\(${sectionId}\\)`, 'g'), `deleteSection(${sectionCounter})`)
            .replace(new RegExp(`cloneSection\\(${sectionId}\\)`, 'g'), `cloneSection(${sectionCounter})`)
            .replace(new RegExp(`addQuestion\\(${sectionId}\\)`, 'g'), `addQuestion(${sectionCounter})`)
            .replace(new RegExp(`addSectionAfter\\(${sectionId}\\)`, 'g'), `addSectionAfter(${sectionCounter})`)
            .replace(/block-color-\w+/, colorClass); // Replace color class

        originalSection.insertAdjacentHTML('afterend', clonedHtml);

        // Restore form data to cloned section
        setTimeout(() => {
            restoreFormDataToSection(sectionCounter, originalData);
            updateSectionNumbers();
            updateNavigationOptions();
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
    const questionsContainer = section.querySelector(`#questionsContainer-${sectionId}`);
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
        const header = section.querySelector('.block-header h4');
        if (header) {
            header.textContent = `Block ${index + 1}`;
        }

        // Update color class
        const colorClass = ((index + 1) % 2 === 0) ? 'block-color-even' : 'block-color-odd';
        section.className = section.className.replace(/block-color-\w+/, colorClass);
    });
}

function updateNavigationOptions() {
    const sections = document.querySelectorAll('.section-block');
    const sectionCount = sections.length;

    sections.forEach((section, index) => {
        // Update the main navigation select to show direct block options
        const navSelect = section.querySelector('select[name*="[navigation_type]"]');
        if (navSelect) {
            const currentValue = navSelect.value;

            // Build navigation options
            let optionsHtml = '';

            // Add "Lanjut ke Block Berikutnya" option
            if (index < sectionCount - 1) {
                optionsHtml += `<option value="next">Lanjut ke Block Berikutnya</option>`;
            }

            // Add specific block options (Block 1, Block 2, etc.)
            for (let i = 1; i <= sectionCount; i++) {
                if (i !== index + 1) { // Don't include current block
                    optionsHtml += `<option value="block_${i}">Block ${i}</option>`;
                }
            }

            // Add "Selesaikan Survey" option
            optionsHtml += `<option value="end">Selesaikan Survey</option>`;

            navSelect.innerHTML = optionsHtml;

            // Restore previous value if it still exists
            if (currentValue && navSelect.querySelector(`option[value="${currentValue}"]`)) {
                navSelect.value = currentValue;
            } else {
                // Default to next or end if last block
                navSelect.value = (index < sectionCount - 1) ? 'next' : 'end';
            }
        }

        // Update option navigation selects within this section
        const optionNavSelects = section.querySelectorAll('.option-navigation-select');
        optionNavSelects.forEach(optionNavSelect => {
            const currentValue = optionNavSelect.value;

            // Build option navigation options
            let optionsHtml = '<option value="next">Block Berikutnya</option>';

            // Add specific block options
            for (let i = 1; i <= sectionCount; i++) {
                if (i !== index + 1) { // Don't include current block
                    optionsHtml += `<option value="block_${i}">Block ${i}</option>`;
                }
            }

            optionsHtml += '<option value="end">Selesai Survey</option>';

            optionNavSelect.innerHTML = optionsHtml;

            // Restore previous value if it still exists
            if (currentValue && optionNavSelect.querySelector(`option[value="${currentValue}"]`)) {
                optionNavSelect.value = currentValue;
            } else {
                optionNavSelect.value = 'next';
            }
        });
    });
}

function addQuestion(sectionId) {
    const questionCount = document.querySelectorAll(`#questionsContainer-${sectionId} .question-item`).length + 1;

    const questionHtml = `
        <div class="question-item" data-question-id="${questionCount}" data-question-number="Q${questionCount}">
            <div class="question-header">
                <div class="flex justify-between items-start">
                    <h6 class="text-sm font-medium text-gray-600">Pertanyaan ${questionCount}</h6>
                    <div class="icon-container">
                        <button type="button" onclick="cloneQuestion(${sectionId}, ${questionCount})" class="icon-link" title="Clone Question">
                            <i class="fas fa-copy text-blue-600"></i>
                        </button>
                        <button type="button" onclick="deleteQuestion(${sectionId}, ${questionCount})" class="icon-link" title="Delete Question">
                            <i class="fas fa-trash text-red-600"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="space-y-3">
                <div>
                    <label class="inline-block mb-1 text-xs font-semibold text-slate-600">Teks Pertanyaan <span class="text-red-500">*</span></label>
                    <textarea name="sections[${sectionId}][questions][${questionCount}][question]" rows="2" placeholder="Tulis pertanyaan disini..." required
                              class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"></textarea>
                </div>

                <div>
                    <label class="inline-block mb-1 text-xs font-semibold text-slate-600">Deskripsi/Instruksi</label>
                    <textarea name="sections[${sectionId}][questions][${questionCount}][description]" rows="1" placeholder="Tulis deskripsi disini..."
                              class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"></textarea>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    <div>
                        <select name="sections[${sectionId}][questions][${questionCount}][type]" onchange="handleQuestionTypeChange(${sectionId}, ${questionCount}, this.value)"
                                class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                            <option value="text">Text Input</option>
                            <option value="textarea">Text Area</option>
                            <option value="radio">Radio Button</option>
                            <option value="checkbox">Checkbox</option>
                            <option value="select">Select Dropdown</option>
                            <option value="file">File Upload</option>
                            <option value="date">Date</option>
                        </select>
                    </div>

                    <div>
                        <select name="sections[${sectionId}][questions][${questionCount}][visualization]"
                                class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                            <option value="">Tidak ada visualisasi</option>
                            <option value="bar">Bar Chart</option>
                            <option value="pie">Pie Chart</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-center">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="sections[${sectionId}][questions][${questionCount}][required]" value="1" class="sr-only peer">
                            <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                            <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">Required</span>
                        </label>
                    </div>
                </div>

                <!-- Options container (will be shown for radio, checkbox, select) -->
                <div id="optionsContainer-${sectionId}-${questionCount}" class="mt-3" style="display: none;">
                    <h6 class="text-sm font-medium text-gray-600 mb-2">Pilihan Jawaban</h6>
                    <div class="text-xs text-gray-500 mb-2">Pilih tipe jawaban terlebih dahulu</div>
                    <div id="optionsList-${sectionId}-${questionCount}" class="space-y-2">
                        <!-- Options will be added here -->
                    </div>
                    <button type="button" onclick="addOption(${sectionId}, ${questionCount})" class="mt-2 px-3 py-1 text-xs bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                        <i class="fas fa-plus mr-1"></i> Tambah Pilihan
                    </button>
                </div>
            </div>
        </div>
    `;

    document.getElementById(`questionsContainer-${sectionId}`).insertAdjacentHTML('beforeend', questionHtml);

    // Update question numbers for visual consistency
    updateQuestionNumbers(sectionId);
}

function updateQuestionNumbers(sectionId) {
    const questionsContainer = document.getElementById(`questionsContainer-${sectionId}`);
    const questions = questionsContainer.querySelectorAll('.question-item');

    questions.forEach((question, index) => {
        question.setAttribute('data-question-number', `Q${index + 1}`);

        // Update header text
        const header = question.querySelector('.question-header h6');
        if (header) {
            header.textContent = `Pertanyaan ${index + 1}`;
        }
    });
}

function deleteQuestion(sectionId, questionId) {
    const questionsContainer = document.getElementById(`questionsContainer-${sectionId}`);
    const questions = questionsContainer.querySelectorAll('.question-item');

    if (questions.length <= 1) {
        alert('Setiap block minimal harus memiliki 1 pertanyaan!');
        return;
    }

    if (confirm('Apakah Anda yakin ingin menghapus pertanyaan ini?')) {
        const question = questionsContainer.querySelector(`[data-question-id="${questionId}"]`);
        if (question) {
            question.remove();
            // Update question numbers after deletion
            updateQuestionNumbers(sectionId);
        }
    }
}

function cloneQuestion(sectionId, questionId) {
    const originalQuestion = document.querySelector(`#questionsContainer-${sectionId} [data-question-id="${questionId}"]`);
    if (originalQuestion) {
        console.log('Cloning question:', questionId);

        // Get original question data
        const originalData = {
            question: originalQuestion.querySelector(`textarea[name*="[question]"]`)?.value || '',
            description: originalQuestion.querySelector(`textarea[name*="[description]"]`)?.value || '',
            type: originalQuestion.querySelector(`select[name*="[type]"]`)?.value || 'text',
            required: originalQuestion.querySelector(`input[name*="[required]"]`)?.checked || false,
            visualization: originalQuestion.querySelector(`select[name*="[visualization]"]`)?.value || '',
            options: []
        };

        // Get options
        const optionInputs = originalQuestion.querySelectorAll('input[name*="[options]"]');
        optionInputs.forEach(input => {
            if (input.value.trim()) {
                originalData.options.push(input.value.trim());
            }
        });

        console.log('Original question data:', originalData);

        // Add new question
        addQuestion(sectionId);

        // Restore data to new question
        setTimeout(() => {
            const questionsContainer = document.querySelector(`#questionsContainer-${sectionId}`);
            const allQuestions = questionsContainer.querySelectorAll('.question-item');
            const newQuestion = allQuestions[allQuestions.length - 1]; // Get the last added question

            if (newQuestion) {
                console.log('Restoring to new question');

                const questionInput = newQuestion.querySelector(`textarea[name*="[question]"]`);
                if (questionInput) {
                    questionInput.value = originalData.question;
                    console.log('Set question text:', originalData.question);
                }

                const descInput = newQuestion.querySelector(`textarea[name*="[description]"]`);
                if (descInput) {
                    descInput.value = originalData.description;
                    console.log('Set description:', originalData.description);
                }

                const typeSelect = newQuestion.querySelector(`select[name*="[type]"]`);
                if (typeSelect) {
                    typeSelect.value = originalData.type;
                    // Trigger change event
                    typeSelect.dispatchEvent(new Event('change'));
                    console.log('Set type:', originalData.type);
                }

                const requiredInput = newQuestion.querySelector(`input[name*="[required]"]`);
                if (requiredInput) {
                    requiredInput.checked = originalData.required;
                    console.log('Set required:', originalData.required);
                }

                const vizSelect = newQuestion.querySelector(`select[name*="[visualization]"]`);
                if (vizSelect) {
                    vizSelect.value = originalData.visualization;
                    console.log('Set visualization:', originalData.visualization);
                }

                // Add options if needed
                if (['radio', 'checkbox', 'select'].includes(originalData.type) && originalData.options.length > 0) {
                    setTimeout(() => {
                        const optionsList = newQuestion.querySelector(`div[id*="optionsList"]`);
                        if (optionsList) {
                            console.log('Adding options:', originalData.options);
                            optionsList.innerHTML = '';

                            // Extract section and question numbers from the new question's name attributes
                            const questionTextarea = newQuestion.querySelector('textarea[name*="[question]"]');
                            if (questionTextarea) {
                                const nameAttr = questionTextarea.name;
                                const matches = nameAttr.match(/sections\[(\d+)\]\[questions\]\[(\d+)\]/);
                                if (matches) {
                                    const newSectionId = matches[1];
                                    const newQuestionId = matches[2];

                                    originalData.options.forEach((optionText, optIndex) => {
                                        addOption(newSectionId, newQuestionId);
                                        // Set option value after a short delay
                                        setTimeout(() => {
                                            const optionInputs = optionsList.querySelectorAll('input[type="text"]');
                                            if (optionInputs[optIndex]) {
                                                optionInputs[optIndex].value = optionText;
                                                console.log('Set option:', optionText);
                                            }
                                        }, 100 * (optIndex + 1));
                                    });
                                }
                            }
                        }
                    }, 300);
                }
            }
        }, 200);
    }
}

function handleQuestionTypeChange(sectionId, questionId, type) {
    const optionsContainer = document.getElementById(`optionsContainer-${sectionId}-${questionId}`);

    if (['radio', 'checkbox', 'select'].includes(type)) {
        optionsContainer.style.display = 'block';
        // Add default options if none exist
        const optionsList = document.getElementById(`optionsList-${sectionId}-${questionId}`);
        if (optionsList.children.length === 0) {
            addOption(sectionId, questionId);
            addOption(sectionId, questionId);
        } else {
            // Update existing options to add/remove navigation based on type
            updateExistingOptionsNavigation(sectionId, questionId, type);
        }
    } else {
        optionsContainer.style.display = 'none';
    }
}

function updateExistingOptionsNavigation(sectionId, questionId, type) {
    const optionsList = document.getElementById(`optionsList-${sectionId}-${questionId}`);
    const options = optionsList.querySelectorAll('.option-item');
    const showNavigationToggle = ['radio', 'select'].includes(type);

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
                    <input type="hidden" name="sections[${sectionId}][questions][${questionId}][option_navigation][]" value="next" class="hidden-navigation-input">
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

function addOption(sectionId, questionId) {
    const optionsList = document.getElementById(`optionsList-${sectionId}-${questionId}`);
    const optionCount = optionsList.children.length + 1;

    // Check if this is a radio or select question type
    const questionElement = optionsList.closest('.question-item');
    const questionTypeSelect = questionElement.querySelector('select[name*="[type]"]');
    const questionType = questionTypeSelect ? questionTypeSelect.value : 'text';
    const showNavigationToggle = ['radio', 'select'].includes(questionType);

    const optionHtml = `
        <div class="option-item mb-3" data-option-index="${optionCount}">
            <div class="flex items-start gap-2">
                <div class="option-controls">
                    <button type="button" onclick="moveOptionUp(this)" class="option-btn up" title="Pindah ke atas">
                        <i class="fas fa-chevron-up"></i>
                    </button>
                    <button type="button" onclick="moveOptionDown(this)" class="option-btn down" title="Pindah ke bawah">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
                <div class="flex-1">
                    <input type="text" name="sections[${sectionId}][questions][${questionId}][options][]" placeholder="Masukkan pilihan ${optionCount}"
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
                        <select name="sections[${sectionId}][questions][${questionId}][option_navigation][]" disabled class="text-xs w-full border border-blue-300 rounded px-2 py-1 bg-white option-navigation-select">
                            <option value="">Gunakan navigasi default</option>
                            <option value="next">Block Berikutnya</option>
                            <option value="end">Selesai Survey</option>
                        </select>
                        <input type="hidden" name="sections[${sectionId}][questions][${questionId}][option_navigation][]" value="next" class="hidden-navigation-input">
                    </div>
                    ` : ''}
                </div>
                <button type="button" onclick="removeOption(this)" class="px-2 py-1 text-red-600 hover:bg-red-50 rounded" title="Hapus pilihan">
                    <i class="fas fa-trash text-xs"></i>
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

function moveOptionUp(button) {
    const optionItem = button.closest('.option-item');
    const previousSibling = optionItem.previousElementSibling;

    if (previousSibling) {
        optionItem.parentNode.insertBefore(optionItem, previousSibling);
        const optionsList = optionItem.closest('[id*="optionsList"]');
        updateOptionButtons(optionsList);
        updateOptionPlaceholders(optionsList);
    }
}

function moveOptionDown(button) {
    const optionItem = button.closest('.option-item');
    const nextSibling = optionItem.nextElementSibling;

    if (nextSibling) {
        optionItem.parentNode.insertBefore(nextSibling, optionItem);
        const optionsList = optionItem.closest('[id*="optionsList"]');
        updateOptionButtons(optionsList);
        updateOptionPlaceholders(optionsList);
    }
}

function removeOption(button) {
    const optionItem = button.closest('.option-item');
    const optionsList = optionItem.closest('[id*="optionsList"]');

    optionItem.remove();
    updateOptionButtons(optionsList);
    updateOptionPlaceholders(optionsList);
}

function updateOptionButtons(optionsList) {
    const options = optionsList.querySelectorAll('.option-item');

    options.forEach((option, index) => {
        const upBtn = option.querySelector('.option-btn.up');
        const downBtn = option.querySelector('.option-btn.down');

        // Disable up button for first item
        upBtn.disabled = (index === 0);
        // Disable down button for last item
        downBtn.disabled = (index === options.length - 1);

        // Update data-option-index
        option.setAttribute('data-option-index', index + 1);
    });
}

function updateOptionPlaceholders(optionsList) {
    const options = optionsList.querySelectorAll('.option-item');

    options.forEach((option, index) => {
        const input = option.querySelector('input[type="text"]');
        if (input) {
            input.placeholder = `Masukkan pilihan ${index + 1}`;
        }
    });
}

// Debug function to test navigation block
function debugOptionAdd(sectionId, questionId) {
    console.log('Debug: Force adding option with navigation');
    const optionsList = document.getElementById(`optionsList-${sectionId}-${questionId}`);
    const optionCount = optionsList.children.length + 1;

    const optionHtml = `
        <div class="option-item mb-3" data-option-index="${optionCount}">
            <div class="flex items-start gap-2">
                <div class="option-controls">
                    <button type="button" onclick="moveOptionUp(this)" class="option-btn up" title="Pindah ke atas">
                        <i class="fas fa-chevron-up"></i>
                    </button>
                    <button type="button" onclick="moveOptionDown(this)" class="option-btn down" title="Pindah ke bawah">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
                <div class="flex-1">
                    <input type="text" name="sections[${sectionId}][questions][${questionId}][options][]" placeholder="Debug Option ${optionCount}"
                           class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                </div>
                <button type="button" onclick="removeOption(this)" class="px-2 py-1 text-red-600 hover:bg-red-50 rounded" title="Hapus pilihan">
                    <i class="fas fa-trash text-xs"></i>
                </button>
            </div>
            <div class="mt-2 ml-12 px-3 py-2 bg-blue-50 border border-blue-200 rounded-lg navigation-block">
                <label class="text-xs font-medium text-blue-700 mb-1 block">🔀 DEBUG: Jika dipilih, lanjut ke:</label>
                <select name="sections[${sectionId}][questions][${questionId}][option_navigation][]" class="text-xs w-full border border-blue-300 rounded px-2 py-1 bg-white option-navigation-select">
                    <option value="next">Block Berikutnya</option>
                    <option value="end">Selesai Survey</option>
                </select>
            </div>
        </div>
    `;

    optionsList.insertAdjacentHTML('beforeend', optionHtml);
    console.log('Debug option added with navigation block');
}

// Toggle navigation block visibility
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
        navigationBlock.classList.add('hidden');
        navigationBlock.classList.remove('block');
        // Reset and disable select, enable hidden input with default value
        navigationSelect.value = '';
        navigationSelect.disabled = true;
        if (hiddenInput) {
            hiddenInput.disabled = false;
            hiddenInput.value = 'next';
        }
    }
}

function validateForm() {
    const errors = [];

    // Validate basic survey info
    const surveyName = document.querySelector('input[name="nama"]');
    if (!surveyName || !surveyName.value.trim()) {
        errors.push('Nama Survei wajib diisi');
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
        alert('Terdapat kesalahan pada form:\n\n' + errors.join('\n'));
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