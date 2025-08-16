@extends('admin.layouts.app')

@push('styles')
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Alpine.js -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Google+Sans:wght@300;400;500;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
<style>
    .gform-wrapper {
        background: #f8f9fc;
        min-height: calc(100vh - 120px);
        font-family: 'Roboto', sans-serif;
    }

    .gform-container {
        max-width: 768px;
        margin: 0 auto;
        padding: 20px;
    }

    .gform-header-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
        margin-bottom: 12px;
        overflow: hidden;
        border-top: 8px solid #673ab7;
    }

    .gform-header {
        padding: 24px;
        background: linear-gradient(135deg, #673ab7 0%, #512da8 100%);
        color: white;
    }

    .gform-title {
        font-size: 32px;
        font-weight: 400;
        margin: 0 0 8px 0;
        line-height: 1.2;
        font-family: 'Google Sans', sans-serif;
    }

    .gform-description {
        font-size: 14px;
        opacity: 0.87;
        margin: 0;
        line-height: 1.4;
    }

    .gform-question {
        background: white;
        margin: 12px 0;
        border-radius: 8px;
        border: 1px solid #dadce0;
        transition: all 0.15s ease-in-out;
        position: relative;
        overflow: hidden;
    }

    .gform-question:hover {
        box-shadow: 0 1px 3px rgba(60,64,67,0.3), 0 4px 8px 3px rgba(60,64,67,0.15);
    }

    .gform-question.focused {
        border-left: 4px solid #4285f4;
        box-shadow: 0 1px 3px rgba(60,64,67,0.3), 0 4px 8px 3px rgba(60,64,67,0.15);
    }

    .gform-question-content {
        padding: 24px;
    }

    .gform-question-title {
        font-size: 16px;
        font-weight: 400;
        color: #202124;
        margin: 0 0 8px 0;
        border: none;
        outline: none;
        width: 100%;
        background: transparent;
        font-family: 'Google Sans', sans-serif;
        border-bottom: 1px solid transparent;
        padding-bottom: 8px;
    }

    .gform-question-title:focus {
        border-bottom-color: #4285f4;
    }

    .gform-question-title::placeholder {
        color: #5f6368;
    }

    .gform-question-description {
        font-size: 14px;
        color: #5f6368;
        margin: 0 0 16px 0;
        border: none;
        outline: none;
        width: 100%;
        background: transparent;
        resize: none;
        font-family: 'Roboto', sans-serif;
        border-bottom: 1px solid transparent;
        padding-bottom: 4px;
    }

    .gform-question-description:focus {
        border-bottom-color: #4285f4;
    }

    .gform-question-description::placeholder {
        color: #5f6368;
    }

    .gform-question-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 24px 16px 24px;
        border-top: 1px solid #f0f0f0;
        margin-top: 16px;
    }

    .gform-type-selector {
        position: relative;
    }

    .gform-type-button {
        background: white;
        border: 1px solid #dadce0;
        border-radius: 4px;
        padding: 8px 12px;
        font-size: 14px;
        color: #5f6368;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        min-width: 160px;
        justify-content: space-between;
    }

    .gform-type-button:hover {
        background: #f8f9fa;
        border-color: #4285f4;
    }

    .gform-type-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #dadce0;
        border-radius: 4px;
        box-shadow: 0 2px 6px 2px rgba(60,64,67,0.15);
        z-index: 1000;
        max-height: 300px;
        overflow-y: auto;
        margin-top: 2px;
    }

    .gform-type-option {
        padding: 12px 16px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 14px;
        color: #3c4043;
        border-bottom: 1px solid #f0f0f0;
    }

    .gform-type-option:last-child {
        border-bottom: none;
    }

    .gform-type-option:hover {
        background: #f8f9fa;
    }

    .gform-options-container {
        margin-top: 16px;
    }

    .gform-option-item {
        display: flex;
        align-items: center;
        margin-bottom: 12px;
        gap: 12px;
        padding: 4px 0;
    }

    .gform-option-input {
        flex: 1;
        border: none;
        outline: none;
        padding: 8px 0;
        border-bottom: 1px solid transparent;
        font-size: 14px;
        background: transparent;
        font-family: 'Roboto', sans-serif;
    }

    .gform-option-input:focus {
        border-bottom-color: #4285f4;
    }

    .gform-option-input::placeholder {
        color: #5f6368;
    }

    .gform-radio-icon, .gform-checkbox-icon {
        width: 20px;
        height: 20px;
        border: 2px solid #5f6368;
        flex-shrink: 0;
    }

    .gform-radio-icon {
        border-radius: 50%;
    }

    .gform-checkbox-icon {
        border-radius: 2px;
    }

    .gform-add-option {
        display: flex;
        align-items: center;
        gap: 12px;
        color: #5f6368;
        font-size: 14px;
        cursor: pointer;
        padding: 8px 0;
        border: none;
        background: transparent;
        margin-top: 8px;
    }

    .gform-add-option:hover {
        color: #1a73e8;
    }

    .gform-question-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .gform-action-btn {
        width: 40px;
        height: 40px;
        border: none;
        background: transparent;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #5f6368;
        transition: all 0.15s ease-in-out;
    }

    .gform-action-btn:hover {
        background: #f8f9fa;
        color: #1a73e8;
    }

    .gform-required-toggle {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: #5f6368;
    }

    .gform-required-switch {
        position: relative;
        width: 36px;
        height: 20px;
        background: #dadce0;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.15s ease-in-out;
    }

    .gform-required-switch.active {
        background: #1a73e8;
    }

    .gform-required-switch::after {
        content: '';
        position: absolute;
        top: 2px;
        left: 2px;
        width: 16px;
        height: 16px;
        background: white;
        border-radius: 50%;
        transition: all 0.15s ease-in-out;
        box-shadow: 0 1px 3px rgba(0,0,0,0.4);
    }

    .gform-required-switch.active::after {
        transform: translateX(16px);
    }

    .gform-add-question {
        position: fixed;
        right: 24px;
        bottom: 24px;
        width: 56px;
        height: 56px;
        background: #1a73e8;
        border: none;
        border-radius: 50%;
        box-shadow: 0 2px 8px 0 rgba(26,115,232,0.4);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
        transition: all 0.15s ease-in-out;
        z-index: 100;
    }

    .gform-add-question:hover {
        background: #1765cc;
        box-shadow: 0 4px 12px 0 rgba(26,115,232,0.6);
        transform: scale(1.05);
    }

    .gform-preview-input {
        border: none;
        outline: none;
        border-bottom: 1px solid #dadce0;
        padding: 8px 0;
        font-size: 14px;
        background: transparent;
        width: 100%;
        font-family: 'Roboto', sans-serif;
    }

    .gform-preview-input:focus {
        border-bottom-color: #4285f4;
    }

    .gform-preview-textarea {
        border: 1px solid #dadce0;
        border-radius: 4px;
        padding: 12px;
        font-size: 14px;
        resize: vertical;
        min-height: 80px;
        width: 100%;
        font-family: 'Roboto', sans-serif;
    }

    .gform-preview-textarea:focus {
        outline: none;
        border-color: #4285f4;
    }

    .gform-file-upload {
        border: 2px dashed #dadce0;
        border-radius: 4px;
        padding: 24px;
        text-align: center;
        color: #5f6368;
        background: #fafbfc;
    }

    .gform-file-upload:hover {
        border-color: #4285f4;
        background: #f8f9fc;
    }

    /* Admin Template Integration */
    .admin-toolbar {
        background: white;
        padding: 16px 24px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .admin-toolbar h6 {
        margin: 0;
        color: #202124;
        font-size: 20px;
        font-weight: 500;
        font-family: 'Google Sans', sans-serif;
    }

    .admin-toolbar-actions {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .question-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.1);
        border-left: 4px solid var(--blue-500);
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }

    .question-card:hover {
        box-shadow: 0 4px 20px rgba(59, 130, 246, 0.15);
        transform: translateY(-2px);
    }

    .question-card.active {
        border-left-color: var(--blue-600);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.2);
    }

    .add-question-btn {
        background: white;
        border: 2px dashed var(--blue-300);
        border-radius: 12px;
        color: var(--blue-600);
        transition: all 0.3s ease;
        min-height: 100px;
    }

    .add-question-btn:hover {
        border-color: var(--blue-500);
        background-color: var(--blue-50);
        color: var(--blue-700);
    }

    .question-type-selector {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 15px;
    }

    .question-type-option {
        background: var(--blue-50);
        border: 1px solid var(--blue-200);
        border-radius: 8px;
        padding: 8px 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 14px;
        color: var(--blue-700);
    }

    .question-type-option:hover {
        background: var(--blue-100);
        border-color: var(--blue-400);
    }

    .question-type-option.selected {
        background: var(--blue-500);
        border-color: var(--blue-600);
        color: white;
    }

    .option-input {
        background: var(--blue-50);
        border: 1px solid var(--blue-200);
        border-radius: 6px;
        padding: 8px 12px;
        margin-bottom: 8px;
        transition: all 0.3s ease;
    }

    .option-input:focus {
        outline: none;
        border-color: var(--blue-500);
        background: white;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .block-sidebar {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.1);
        padding: 0;
        margin-bottom: 20px;
        position: sticky;
        top: 20px;
    }

    .block-item {
        background: var(--blue-50);
        border: 1px solid var(--blue-200);
        border-radius: 8px;
        padding: 12px;
        margin-bottom: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .block-item:hover {
        background: var(--blue-100);
        border-color: var(--blue-400);
    }

    .block-item.active {
        background: var(--blue-500);
        border-color: var(--blue-600);
        color: white;
    }

    .branch-rule-indicator {
        width: 8px;
        height: 8px;
        background: var(--blue-500);
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
    }

    .question-toolbar {
        display: flex;
        gap: 8px;
        align-items: center;
        padding: 10px 0;
        border-top: 1px solid var(--blue-100);
        margin-top: 15px;
    }

    .toolbar-btn {
        background: var(--blue-50);
        border: 1px solid var(--blue-200);
        border-radius: 6px;
        padding: 6px 12px;
        color: var(--blue-700);
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .toolbar-btn:hover {
        background: var(--blue-100);
        border-color: var(--blue-400);
    }

    .toolbar-btn.active {
        background: var(--blue-500);
        border-color: var(--blue-600);
        color: white;
    }

    .section-header {
        background: var(--blue-500);
        color: white;
        padding: 15px 20px;
        border-radius: 8px 8px 0 0;
        margin-bottom: 0;
        font-weight: 600;
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

    .preview-mode {
        background: var(--blue-50);
        border: 2px solid var(--blue-200);
    }

    .preview-mode .question-card {
        pointer-events: none;
        opacity: 0.8;
    }
</style>
@endpush

@section('content')
<div class="gform-wrapper" x-data="formBuilder()">
    <!-- Admin Toolbar -->
    <div class="admin-toolbar">
        <div>
            <h6>Form Builder</h6>
            <p class="text-sm text-gray-600 mb-0">{{ $survey->nama }}</p>
        </div>
        <div class="admin-toolbar-actions">
            <button @click="previewMode = !previewMode" 
                    class="inline-block px-4 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85"
                    :class="previewMode ? 'bg-orange-500 hover:bg-orange-600' : 'bg-gray-500 hover:bg-gray-600'">
                <i class="fas fa-eye mr-2"></i> Preview
            </button>
            <button @click="saveForm()" 
                    data-save-form
                    class="inline-block px-4 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-green-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85 hover:bg-green-600">
                <i class="fas fa-save mr-2"></i> Save
            </button>
        </div>
    </div>

    <div class="gform-container">
        <!-- Form Header -->
        <div class="gform-header-card">
            <div class="gform-header">
                <h1 class="gform-title">{{ $survey->nama }}</h1>
                <p class="gform-description">{{ $survey->deskripsi }}</p>
            </div>
        </div>

        <!-- Questions -->
        <div class="space-y-3">
            <template x-for="(question, index) in questions" :key="question.id || index">
                <div class="gform-question" 
                     :class="{'focused': selectedQuestion?.id === question.id}"
                     @click="selectQuestion(question)">
                    
                    <div class="gform-question-content">
                        <!-- Question Number -->
                        <div class="text-sm text-gray-500 mb-2" x-text="`${index + 1}.`"></div>
                        
                        <!-- Question Title -->
                        <input type="text" 
                               class="gform-question-title"
                               x-model="question.pertanyaan"
                               @input="updateQuestion(question)"
                               placeholder="Question"
                               :placeholder="`Question ${index + 1}`">

                        <!-- Question Description -->
                        <textarea class="gform-question-description"
                                  x-model="question.deskripsi_pertanyaan"
                                  @input="updateQuestion(question)"
                                  placeholder="Help text (optional)"
                                  rows="1"></textarea>

                        <!-- Answer Options (for radio, checkbox, select) -->
                        <div x-show="['radio', 'checkbox', 'select'].includes(question.tipe)" class="gform-options-container">
                            <template x-for="(option, optionIndex) in question.options" :key="optionIndex">
                                <div class="gform-option-item">
                                    <!-- Radio/Checkbox Icon -->
                                    <template x-if="question.tipe === 'radio'">
                                        <div class="gform-radio-icon"></div>
                                    </template>
                                    <template x-if="question.tipe === 'checkbox'">
                                        <div class="gform-checkbox-icon"></div>
                                    </template>
                                    <template x-if="question.tipe === 'select'">
                                        <span class="text-sm text-gray-500 w-6" x-text="optionIndex + 1 + '.'"></span>
                                    </template>
                                    
                                    <!-- Option Input -->
                                    <input type="text" 
                                           class="gform-option-input"
                                           x-model="option.text"
                                           @input="updateQuestion(question)"
                                           :placeholder="`Option ${optionIndex + 1}`">
                                    
                                    <!-- Remove Option -->
                                    <button @click="removeOption(question, optionIndex)" 
                                            class="gform-action-btn"
                                            x-show="question.options.length > 1">
                                        <i class="fas fa-times text-sm"></i>
                                    </button>
                                </div>
                            </template>
                            
                            <!-- Add Option Button -->
                            <button @click="addOption(question)" class="gform-add-option">
                                <i class="fas fa-plus"></i>
                                <span>Add option</span>
                            </button>
                        </div>

                        <!-- Preview for other question types -->
                        <div x-show="!['radio', 'checkbox', 'select'].includes(question.tipe)" class="mt-4">
                            <template x-if="question.tipe === 'text'">
                                <input type="text" placeholder="Short answer text" class="gform-preview-input" disabled>
                            </template>
                            <template x-if="question.tipe === 'textarea'">
                                <textarea placeholder="Long answer text" class="gform-preview-textarea" disabled></textarea>
                            </template>
                            <template x-if="question.tipe === 'date'">
                                <input type="date" class="gform-preview-input" disabled>
                            </template>
                            <template x-if="question.tipe === 'file'">
                                <div class="gform-file-upload">
                                    <i class="fas fa-cloud-upload-alt text-2xl mb-2"></i>
                                    <div>Add file</div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Question Toolbar -->
                    <div class="gform-question-toolbar">
                        <div class="flex items-center gap-4">
                            <!-- Question Type Selector -->
                            <div class="gform-type-selector" x-data="{ dropdownOpen: false }">
                                <button @click="dropdownOpen = !dropdownOpen" class="gform-type-button">
                                    <div class="flex items-center gap-2">
                                        <i :class="getQuestionTypeIcon(question.tipe)" class="text-sm"></i>
                                        <span x-text="getQuestionTypeLabel(question.tipe)"></span>
                                    </div>
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </button>
                                
                                <div x-show="dropdownOpen" 
                                     @click.away="dropdownOpen = false"
                                     x-transition
                                     class="gform-type-dropdown">
                                    <template x-for="type in questionTypes" :key="type.value">
                                        <div class="gform-type-option"
                                             @click="changeQuestionType(question, type.value); dropdownOpen = false">
                                            <i :class="type.icon" class="text-sm"></i>
                                            <span x-text="type.label"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Required Toggle -->
                            <div class="gform-required-toggle">
                                <span>Required</span>
                                <div class="gform-required-switch" 
                                     :class="{'active': question.required}"
                                     @click="question.required = !question.required; updateQuestion(question)">
                                </div>
                            </div>
                        </div>

                        <!-- Question Actions -->
                        <div class="gform-question-actions">
                            <button @click.stop="duplicateQuestion(question)" 
                                    class="gform-action-btn" 
                                    title="Duplicate">
                                <i class="fas fa-copy"></i>
                            </button>
                            <button @click.stop="deleteQuestion(index)" 
                                    class="gform-action-btn" 
                                    title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                            <button @click.stop="moveQuestion(index, 'up')" 
                                    class="gform-action-btn"
                                    title="Move up"
                                    :disabled="index === 0">
                                <i class="fas fa-arrow-up"></i>
                            </button>
                            <button @click.stop="moveQuestion(index, 'down')" 
                                    class="gform-action-btn"
                                    title="Move down"
                                    :disabled="index === questions.length - 1">
                                <i class="fas fa-arrow-down"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Floating Add Question Button -->
    <button @click="addQuestion()" class="gform-add-question" title="Add question">
        <i class="fas fa-plus"></i>
    </button>
</div>

@push('scripts')
<script>
                                            <template x-for="rule in getBranchRules(selectedQuestion?.id)" :key="rule.id">
                                                <div class="border border-gray-200 rounded-md p-3 bg-gray-50">
                                                    <div class="text-xs font-medium mb-1">
                                                        If answer = "<span x-text="rule.condition_value"></span>"
                                                    </div>
                                                    <div class="text-xs text-gray-600">
                                                        → Go to <span x-text="getBlockName(rule.target_block_id)"></span>
                                                    </div>
                                                    <div class="flex items-center justify-end mt-2 space-x-1">
                                                        <button @click="editBranchRule(rule)" class="toolbar-btn text-xs">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button @click="deleteBranchRule(rule)" class="toolbar-btn text-xs text-red-600">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Main Content Area - Form Builder -->
                            <div class="col-span-9">
                                <div class="form-builder-content" :class="{'preview-mode': previewMode}">
                                    
                                    <!-- Add Question Button -->
                                    <div class="mb-6">
                                        <button @click="addQuestion()" 
                                                class="add-question-btn flex items-center justify-center cursor-pointer w-full">
                                            <div class="text-center">
                                                <i class="fas fa-plus text-2xl mb-2"></i>
                                                <div class="font-medium">Tambah Pertanyaan</div>
                                                <div class="text-sm opacity-75">Klik untuk menambah pertanyaan baru</div>
                                            </div>
                                        </button>
                                    </div>

                                    <!-- Questions List -->
                                    <div class="space-y-4">
                                        <template x-for="(question, index) in questions" :key="question.id || index">
                                            <div class="question-card" 
                                                 :class="{'active': selectedQuestion?.id === question.id}"
                                                 @click="selectQuestion(question)">
                                                
                                                <!-- Question Header -->
                                                <div class="p-4 border-b border-gray-100">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex items-center space-x-2">
                                                            <span class="text-sm font-medium text-gray-500">Question</span>
                                                            <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full" 
                                                                  x-text="index + 1"></span>
                                                            <template x-if="getQuestionBranchRules(question.id).length > 0">
                                                                <span class="branch-rule-indicator" title="Has branch rules"></span>
                                                            </template>
                                                        </div>
                                                        <div class="flex items-center space-x-2">
                                                            <select x-model="question.block_id" 
                                                                    @change="updateQuestion(question)"
                                                                    class="text-xs border border-gray-300 rounded px-2 py-1">
                                                                <option value="">No Block</option>
                                                                <template x-for="block in blocks" :key="block.id">
                                                                    <option :value="block.id" x-text="block.nama"></option>
                                                                </template>
                                                            </select>
                                                            <button @click.stop="duplicateQuestion(question)" class="toolbar-btn">
                                                                <i class="fas fa-copy"></i>
                                                            </button>
                                                            <button @click.stop="deleteQuestion(index)" class="toolbar-btn text-red-600">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                <!-- Question Content -->
                                <div class="p-4">
                                    <!-- Question Title -->
                                    <textarea x-model="question.pertanyaan"
                                            @input="updateQuestion(question)"
                                            placeholder="Enter your question here"
                                            class="w-full text-lg font-medium border-none resize-none focus:outline-none focus:ring-0 bg-transparent"
                                            rows="2"></textarea>

                                    <!-- Question Description -->
                                    <textarea x-model="question.deskripsi_pertanyaan"
                                            @input="updateQuestion(question)"
                                            placeholder="Description (optional)"
                                            class="w-full text-sm text-gray-600 border-none resize-none focus:outline-none focus:ring-0 bg-transparent mt-2"
                                            rows="1"></textarea>

                                    <!-- Question Type Selector -->
                                    <div class="question-type-selector">
                                        <template x-for="type in questionTypes" :key="type.value">
                                            <div class="question-type-option"
                                                 :class="{'selected': question.tipe === type.value}"
                                                 @click="changeQuestionType(question, type.value)">
                                                <i :class="type.icon" class="mr-1"></i>
                                                <span x-text="type.label"></span>
                                            </div>
                                        </template>
                                    </div>

                                    <!-- Answer Options (for radio, checkbox, select) -->
                                    <div x-show="['radio', 'checkbox', 'select'].includes(question.tipe)" class="mt-4">
                                        <div class="space-y-2">
                                            <template x-for="(option, optionIndex) in question.options" :key="optionIndex">
                                                <div class="flex items-center space-x-2">
                                                    <div class="flex-shrink-0">
                                                        <template x-if="question.tipe === 'radio'">
                                                            <div class="w-4 h-4 border-2 border-gray-300 rounded-full"></div>
                                                        </template>
                                                        <template x-if="question.tipe === 'checkbox'">
                                                            <div class="w-4 h-4 border-2 border-gray-300 rounded"></div>
                                                        </template>
                                                        <template x-if="question.tipe === 'select'">
                                                            <span class="text-sm text-gray-500" x-text="optionIndex + 1 + '.'"></span>
                                                        </template>
                                                    </div>
                                                    <input type="text" 
                                                           x-model="option.text"
                                                           @input="updateQuestion(question)"
                                                           placeholder="Option text"
                                                           class="flex-1 option-input">
                                                    <button @click="removeOption(question, optionIndex)" 
                                                            class="toolbar-btn text-red-600">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </template>
                                            
                                            <button @click="addOption(question)" 
                                                    class="flex items-center space-x-2 text-blue-600 hover:text-blue-700 text-sm">
                                                <i class="fas fa-plus"></i>
                                                <span>Add option</span>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Preview for other question types -->
                                    <div x-show="!['radio', 'checkbox', 'select'].includes(question.tipe)" class="mt-4">
                                        <template x-if="question.tipe === 'text'">
                                            <input type="text" placeholder="Short answer text" class="w-full option-input" disabled>
                                        </template>
                                        <template x-if="question.tipe === 'textarea'">
                                            <textarea placeholder="Long answer text" class="w-full option-input" rows="3" disabled></textarea>
                                        </template>
                                        <template x-if="question.tipe === 'date'">
                                            <input type="date" class="option-input" disabled>
                                        </template>
                                        <template x-if="question.tipe === 'file'">
                                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center text-gray-500">
                                                <i class="fas fa-upload text-2xl mb-2"></i>
                                                <div>Upload file</div>
                                            </div>
                                        </template>
                                    </div>

                                    <!-- Question Settings -->
                                    <div class="question-toolbar">
                                        <label class="flex items-center space-x-2 text-sm">
                                            <input type="checkbox" 
                                                   x-model="question.required" 
                                                   @change="updateQuestion(question)"
                                                   class="rounded border-gray-300">
                                            <span>Required</span>
                                        </label>
                                        
                                        <select x-model="question.visualisasi" 
                                                @change="updateQuestion(question)"
                                                class="toolbar-btn">
                                            <option value="">No Chart</option>
                                            <option value="bar">Bar Chart</option>
                                            <option value="pie">Pie Chart</option>
                                        </select>
                                        
                                        <div class="flex-1"></div>
                                        
                                        <button @click="moveQuestion(questions.indexOf(question), 'up')" 
                                                class="toolbar-btn"
                                                :disabled="index === 0">
                                            <i class="fas fa-arrow-up"></i>
                                        </button>
                                        <button @click="moveQuestion(questions.indexOf(question), 'down')" 
                                                class="toolbar-btn"
                                                :disabled="index === questions.length - 1">
                                            <i class="fas fa-arrow-down"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <!-- Add New Question Button -->
                        <div class="add-question-btn flex items-center justify-center cursor-pointer"
                             @click="addQuestion()">
                            <div class="text-center">
                                <i class="fas fa-plus text-2xl mb-2"></i>
                                <div class="font-medium">Add Question</div>
                                <div class="text-sm opacity-75">Click to add a new question</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <!-- Block Edit Modal -->
    <div class="modal-overlay" x-show="showBlockModal" x-cloak>
        <div class="modal-content">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Edit Block</h3>
                <button @click="showBlockModal = false" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form @submit.prevent="saveBlock()">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Block Code</label>
                        <input type="text" x-model="editingBlock.kode" class="w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Block Name</label>
                        <input type="text" x-model="editingBlock.nama" class="w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea x-model="editingBlock.deskripsi" class="w-full border border-gray-300 rounded-md px-3 py-2" rows="3"></textarea>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" x-model="editingBlock.is_terminal" id="isTerminal">
                        <label for="isTerminal" class="text-sm text-gray-700">Terminal Block (End of survey)</label>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" @click="showBlockModal = false" 
                            class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-soft-blue-500 text-white rounded-md hover:bg-soft-blue-600">
                        Save Block
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Branch Rule Modal -->
    <div class="modal-overlay" x-show="showBranchModal" x-cloak>
        <div class="modal-content">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Branch Rule</h3>
                <button @click="showBranchModal = false" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form @submit.prevent="saveBranchRule()">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Condition Type</label>
                        <select x-model="editingBranchRule.condition_type" class="w-full border border-gray-300 rounded-md px-3 py-2">
                            <option value="equals">Equals</option>
                            <option value="contains">Contains</option>
                            <option value="greater_than">Greater than</option>
                            <option value="less_than">Less than</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Condition Value</label>
                        <input type="text" x-model="editingBranchRule.condition_value" 
                               placeholder="Value to match" 
                               class="w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Target Block</label>
                        <select x-model="editingBranchRule.target_block_id" class="w-full border border-gray-300 rounded-md px-3 py-2">
                            <option value="">Select target block</option>
                            <template x-for="block in blocks" :key="block.id">
                                <option :value="block.id" x-text="block.nama"></option>
                            </template>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                        <input type="number" x-model="editingBranchRule.priority" 
                               class="w-full border border-gray-300 rounded-md px-3 py-2" 
                               min="1" value="1">
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" @click="showBranchModal = false" 
                            class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-purple-500 text-white rounded-md hover:bg-purple-600">
                        Save Rule
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function formBuilder() {
    return {
        questions: @json($questions ?? []),
        blocks: @json($blocks ?? []),
        branchRules: @json($branchRules ?? []),
        selectedQuestion: null,
        selectedBlock: null,
        previewMode: false,
        showBlockModal: false,
        showBranchModal: false,
        newBlockName: '',
        editingBlock: {},
        editingBranchRule: {},
        
        questionTypes: [
            { value: 'text', label: 'Short Answer', icon: 'fas fa-font' },
            { value: 'textarea', label: 'Paragraph', icon: 'fas fa-align-left' },
            { value: 'radio', label: 'Multiple Choice', icon: 'far fa-dot-circle' },
            { value: 'checkbox', label: 'Checkboxes', icon: 'far fa-check-square' },
            { value: 'select', label: 'Dropdown', icon: 'fas fa-caret-down' },
            { value: 'date', label: 'Date', icon: 'far fa-calendar-alt' },
            { value: 'file', label: 'File Upload', icon: 'fas fa-cloud-upload-alt' }
        ],

        init() {
            // Initialize default options for choice questions
            this.questions.forEach(question => {
                if (['radio', 'checkbox', 'select'].includes(question.tipe) && !question.options) {
                    question.options = question.template_jawaban ? 
                        question.template_jawaban.map(j => ({text: j.pilihan_jawaban})) : 
                        [{text: 'Option 1'}];
                }
            });
        },

        addQuestion() {
            const newQuestion = {
                id: Date.now(),
                pertanyaan: '',
                deskripsi_pertanyaan: '',
                tipe: 'text',
                block_id: this.selectedBlock?.id || '',
                options: [],
                required: false,
                visualisasi: ''
            };
            this.questions.push(newQuestion);
            this.selectQuestion(newQuestion);
        },

        selectQuestion(question) {
            this.selectedQuestion = question;
        },

        selectBlock(block) {
            this.selectedBlock = block;
        },

        changeQuestionType(question, type) {
            question.tipe = type;
            if (['radio', 'checkbox', 'select'].includes(type) && !question.options) {
                question.options = [{text: 'Option 1'}];
            }
            this.updateQuestion(question);
        },

        addOption(question) {
            if (!question.options) question.options = [];
            question.options.push({text: 'Option ' + (question.options.length + 1)});
            this.updateQuestion(question);
        },

        removeOption(question, index) {
            question.options.splice(index, 1);
            this.updateQuestion(question);
        },

        duplicateQuestion(question) {
            const duplicate = JSON.parse(JSON.stringify(question));
            duplicate.id = Date.now();
            duplicate.pertanyaan = duplicate.pertanyaan + ' (Copy)';
            this.questions.push(duplicate);
        },

        deleteQuestion(index) {
            if (confirm('Are you sure you want to delete this question?')) {
                this.questions.splice(index, 1);
            }
        },

        moveQuestion(index, direction) {
            if (direction === 'up' && index > 0) {
                [this.questions[index], this.questions[index - 1]] = 
                [this.questions[index - 1], this.questions[index]];
            } else if (direction === 'down' && index < this.questions.length - 1) {
                [this.questions[index], this.questions[index + 1]] = 
                [this.questions[index + 1], this.questions[index]];
            }
        },

        addBlock() {
            if (!this.newBlockName.trim()) return;
            
            const newBlock = {
                id: Date.now(),
                survey_id: {{ $survey->id }},
                kode: this.newBlockName.charAt(0).toUpperCase(),
                nama: this.newBlockName,
                deskripsi: '',
                urutan: this.blocks.length + 1,
                is_terminal: false
            };
            
            this.blocks.push(newBlock);
            this.newBlockName = '';
            this.selectBlock(newBlock);
        },

        editBlock(block) {
            this.editingBlock = JSON.parse(JSON.stringify(block));
            this.showBlockModal = true;
        },

        deleteBlock(block) {
            if (confirm('Are you sure you want to delete this block?')) {
                this.blocks = this.blocks.filter(b => b.id !== block.id);
                if (this.selectedBlock?.id === block.id) {
                    this.selectedBlock = null;
                }
            }
        },

        saveBlock() {
            const index = this.blocks.findIndex(b => b.id === this.editingBlock.id);
            if (index !== -1) {
                this.blocks[index] = {...this.editingBlock};
            }
            this.showBlockModal = false;
        },

        getBlockQuestionCount(blockId) {
            return this.questions.filter(q => q.block_id === blockId).length;
        },

        getBlockName(blockId) {
            const block = this.blocks.find(b => b.id === blockId);
            return block ? block.nama : 'Unknown Block';
        },

        addBranchRule() {
            if (!this.selectedQuestion) return;
            
            this.editingBranchRule = {
                id: Date.now(),
                question_id: this.selectedQuestion.id,
                condition_type: 'equals',
                condition_value: '',
                target_block_id: '',
                priority: 1
            };
            this.showBranchModal = true;
        },

        editBranchRule(rule) {
            this.editingBranchRule = JSON.parse(JSON.stringify(rule));
            this.showBranchModal = true;
        },

        deleteBranchRule(rule) {
            if (confirm('Are you sure you want to delete this branch rule?')) {
                this.branchRules = this.branchRules.filter(r => r.id !== rule.id);
            }
        },

        saveBranchRule() {
            const index = this.branchRules.findIndex(r => r.id === this.editingBranchRule.id);
            if (index !== -1) {
                this.branchRules[index] = {...this.editingBranchRule};
            } else {
                this.branchRules.push({...this.editingBranchRule});
            }
            this.showBranchModal = false;
        },

        getBranchRules(questionId) {
            return this.branchRules.filter(r => r.question_id === questionId);
        },

        getQuestionBranchRules(questionId) {
            return this.branchRules.filter(r => r.question_id === questionId);
        },

        updateQuestion(question) {
            // This method can be used to trigger auto-save or validation
            console.log('Question updated:', question);
        },

        async saveForm() {
            try {
                const formData = {
                    survey_id: {{ $survey->id }},
                    questions: this.questions,
                    blocks: this.blocks,
                    branchRules: this.branchRules
                };

                // Show loading state
                const saveBtn = document.querySelector('[data-save-form]');
                if (saveBtn) {
                    const originalText = saveBtn.innerHTML;
                    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Saving...';
                    saveBtn.disabled = true;
                }

                const response = await fetch('{{ route("admin.survey.create_question") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    // Show success message
                    this.showNotification('Form saved successfully!', 'success');
                } else {
                    throw new Error(result.message || 'Failed to save form');
                }

                // Restore button state
                if (saveBtn) {
                    saveBtn.innerHTML = '<i class="fas fa-save"></i> Save';
                    saveBtn.disabled = false;
                }

            } catch (error) {
                console.error('Error saving form:', error);
                this.showNotification('Error saving form: ' + error.message, 'error');
                
                // Restore button state
                const saveBtn = document.querySelector('[data-save-form]');
                if (saveBtn) {
                    saveBtn.innerHTML = '<i class="fas fa-save"></i> Save';
                    saveBtn.disabled = false;
                }
            }
        },

        showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
                type === 'success' ? 'bg-green-500 text-white' : 
                type === 'error' ? 'bg-red-500 text-white' : 
                'bg-blue-500 text-white'
            }`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            // Remove after 3 seconds
            setTimeout(() => {
                notification.remove();
            }, 3000);
        },

        getQuestionTypeIcon(type) {
            const typeMap = {
                'text': 'fas fa-font',
                'textarea': 'fas fa-align-left',
                'radio': 'far fa-dot-circle',
                'checkbox': 'far fa-check-square',
                'select': 'fas fa-caret-down',
                'date': 'far fa-calendar-alt',
                'file': 'fas fa-cloud-upload-alt'
            };
            return typeMap[type] || 'fas fa-question';
        },

        getQuestionTypeLabel(type) {
            const typeMap = {
                'text': 'Short Answer',
                'textarea': 'Paragraph',
                'radio': 'Multiple Choice',
                'checkbox': 'Checkboxes',
                'select': 'Dropdown',
                'date': 'Date',
                'file': 'File Upload'
            };
            return typeMap[type] || 'Unknown';
        }
    }
}
</script>
@endpush
