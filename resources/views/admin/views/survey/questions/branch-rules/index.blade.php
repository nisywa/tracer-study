@extends('admin.layouts.main')

@section('title', 'Aturan Percabangan - ' . $question->pertanyaan)

@section('content')
<div x-data="branchRuleManager()" class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Aturan Percabangan</h1>
            <p class="text-gray-600">{{ $survey->nama }}</p>
            <div class="mt-2">
                <span class="text-sm text-gray-500">Pertanyaan:</span>
                <span class="text-sm font-medium text-gray-900">{{ $question->pertanyaan }}</span>
                @if($question->block)
                    <span class="ml-2 px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                        Blok {{ $question->block->kode }}
                    </span>
                @endif
            </div>
        </div>
        <div class="flex space-x-3">
            <button @click="showCreateModal = true" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Tambah Aturan
            </button>
            <a href="{{ route('admin.survey.add_question', $survey->id) }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Pertanyaan
            </a>
        </div>
    </div>

    <!-- Info Panel -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Tentang Aturan Percabangan</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li>Aturan percabangan memungkinkan responden melompat ke blok tertentu berdasarkan jawaban mereka</li>
                        <li>Aturan dieksekusi berdasarkan prioritas (angka kecil = prioritas tinggi)</li>
                        <li>Jika tidak ada aturan yang cocok, sistem akan melanjutkan ke pertanyaan berikutnya secara normal</li>
                        <li>Aturan berbasis opsi: untuk pertanyaan pilihan ganda (radio, checkbox, select)</li>
                        <li>Aturan berbasis nilai: untuk pertanyaan input (text, number) dengan operator perbandingan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Rules -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Aturan yang Sudah Dibuat</h3>
        </div>
        
        @if($rules->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Prioritas
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kondisi
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Target Blok
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($rules as $rule)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">
                                        {{ $rule->priority }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        @if($rule->isOptionBased())
                                            <span class="text-blue-600">Jika pilihan:</span>
                                            <span class="font-medium">"{{ $rule->answerOption->pilihan_jawaban }}"</span>
                                        @elseif($rule->isValueBased())
                                            <span class="text-green-600">Jika nilai:</span>
                                            <span class="font-medium">
                                                {{ $rule->operator }} 
                                                @if(is_array($rule->value_json))
                                                    [{{ implode(', ', $rule->value_json) }}]
                                                @else
                                                    "{{ $rule->value_json }}"
                                                @endif
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($rule->targetBlock)
                                        <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                            {{ $rule->targetBlock->kode }}
                                        </span>
                                        <span class="ml-2 text-sm text-gray-600">{{ $rule->targetBlock->nama }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button @click="editRule({{ $rule->id }})" 
                                                class="text-blue-600 hover:text-blue-900 transition-colors">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button @click="deleteRule({{ $rule->id }})" 
                                                class="text-red-600 hover:text-red-900 transition-colors">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <div class="text-gray-500">
                    <i class="fas fa-code-branch text-4xl mb-4"></i>
                    <p class="text-lg">Belum ada aturan percabangan</p>
                    <p class="text-sm">Tambahkan aturan untuk mengatur alur survei berdasarkan jawaban</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Create/Edit Modal -->
    <div x-show="showCreateModal || showEditModal" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeModal()"></div>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <form :action="formAction" method="POST">
                    @csrf
                    <div x-show="showEditModal" style="display: none;">
                        @method('PUT')
                    </div>

                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" x-text="modalTitle">
                                </h3>

                                <div class="space-y-4">
                                    <!-- Rule Type (only for create) -->
                                    <div x-show="showCreateModal">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Jenis Aturan *
                                        </label>
                                        <div class="space-y-2">
                                            <label class="flex items-center">
                                                <input type="radio" 
                                                       name="rule_type" 
                                                       value="option"
                                                       x-model="formData.rule_type"
                                                       @change="updateRuleType()"
                                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                                <span class="ml-2 text-sm text-gray-700">
                                                    Berdasarkan pilihan jawaban (untuk radio/checkbox/select)
                                                </span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="radio" 
                                                       name="rule_type" 
                                                       value="value"
                                                       x-model="formData.rule_type"
                                                       @change="updateRuleType()"
                                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                                <span class="ml-2 text-sm text-gray-700">
                                                    Berdasarkan nilai (untuk input text/number)
                                                </span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Option-based rule -->
                                    <div x-show="formData.rule_type === 'option'">
                                        <label for="answer_option_id" class="block text-sm font-medium text-gray-700 mb-1">
                                            Pilihan Jawaban *
                                        </label>
                                        <select name="answer_option_id" 
                                                id="answer_option_id"
                                                x-model="formData.answer_option_id"
                                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            <option value="">-- Pilih Jawaban --</option>
                                            @foreach($question->template_jawaban as $option)
                                                <option value="{{ $option->id }}">{{ $option->pilihan_jawaban }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Value-based rule -->
                                    <div x-show="formData.rule_type === 'value'" style="display: none;">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label for="operator" class="block text-sm font-medium text-gray-700 mb-1">
                                                    Operator *
                                                </label>
                                                <select name="operator" 
                                                        id="operator"
                                                        x-model="formData.operator"
                                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                                    <option value="">-- Pilih Operator --</option>
                                                    @foreach(\App\Models\SurveyBranchRule::OPERATORS as $key => $label)
                                                        <option value="{{ $key }}">{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label for="value" class="block text-sm font-medium text-gray-700 mb-1">
                                                    Nilai *
                                                </label>
                                                <input type="text" 
                                                       name="value" 
                                                       id="value"
                                                       x-model="formData.value"
                                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                       :placeholder="getValuePlaceholder()">
                                            </div>
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500">
                                            <span x-show="formData.operator === 'in'">Untuk operator 'In array', pisahkan nilai dengan koma (contoh: A,B,C)</span>
                                            <span x-show="formData.operator && formData.operator !== 'in'">Masukkan nilai yang akan dibandingkan</span>
                                        </p>
                                    </div>

                                    <!-- Target Block -->
                                    <div>
                                        <label for="target_block_id" class="block text-sm font-medium text-gray-700 mb-1">
                                            Blok Tujuan *
                                        </label>
                                        <select name="target_block_id" 
                                                id="target_block_id"
                                                x-model="formData.target_block_id"
                                                required
                                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            <option value="">-- Pilih Blok Tujuan --</option>
                                            @foreach($availableBlocks as $block)
                                                <option value="{{ $block->id }}">
                                                    {{ $block->kode }} - {{ $block->nama }}
                                                    @if($block->is_terminal) (Terminal) @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        <p class="mt-1 text-xs text-gray-500">Responden akan diarahkan ke blok ini jika kondisi terpenuhi</p>
                                    </div>

                                    <!-- Priority -->
                                    <div>
                                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">
                                            Prioritas *
                                        </label>
                                        <input type="number" 
                                               name="priority" 
                                               id="priority"
                                               x-model="formData.priority"
                                               required 
                                               min="1"
                                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <p class="mt-1 text-xs text-gray-500">Aturan dengan prioritas lebih kecil akan diperiksa terlebih dahulu</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" 
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            <span x-show="showCreateModal">Simpan Aturan</span>
                            <span x-show="showEditModal" style="display: none;">Update Aturan</span>
                        </button>
                        <button type="button" 
                                @click="closeModal()"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function branchRuleManager() {
    return {
        showCreateModal: false,
        showEditModal: false,
        formAction: '',
        modalTitle: '',
        formData: {
            rule_type: 'option',
            answer_option_id: '',
            operator: '',
            value: '',
            target_block_id: '',
            priority: {{ $rules->count() + 1 }}
        },

        updateRuleType() {
            // Reset relevant fields when rule type changes
            if (this.formData.rule_type === 'option') {
                this.formData.operator = '';
                this.formData.value = '';
            } else {
                this.formData.answer_option_id = '';
            }
        },

        getValuePlaceholder() {
            switch (this.formData.operator) {
                case 'in':
                    return 'nilai1,nilai2,nilai3';
                case 'gt':
                case 'gte':
                case 'lt':
                case 'lte':
                    return '100';
                case 'contains':
                    return 'kata kunci';
                default:
                    return 'nilai pembanding';
            }
        },

        editRule(ruleId) {
            fetch(`{{ route('admin.survey.questions.branch-rules.show', ['survey' => $survey->id, 'question' => $question->id, 'rule' => ':id']) }}`.replace(':id', ruleId))
                .then(response => response.json())
                .then(rule => {
                    this.formData = {
                        rule_type: rule.answer_option_id ? 'option' : 'value',
                        answer_option_id: rule.answer_option_id || '',
                        operator: rule.operator || '',
                        value: Array.isArray(rule.value_json) ? rule.value_json.join(',') : (rule.value_json || ''),
                        target_block_id: rule.target_block_id,
                        priority: rule.priority
                    };
                    this.formAction = `{{ route('admin.survey.questions.branch-rules.update', ['survey' => $survey->id, 'question' => $question->id, 'rule' => ':id']) }}`.replace(':id', ruleId);
                    this.modalTitle = 'Edit Aturan Percabangan';
                    this.showEditModal = true;
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memuat data aturan');
                });
        },

        deleteRule(ruleId) {
            if (confirm('Apakah Anda yakin ingin menghapus aturan percabangan ini?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `{{ route('admin.survey.questions.branch-rules.destroy', ['survey' => $survey->id, 'question' => $question->id, 'rule' => ':id']) }}`.replace(':id', ruleId);
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                
                form.appendChild(csrfToken);
                form.appendChild(methodField);
                document.body.appendChild(form);
                form.submit();
            }
        },

        closeModal() {
            this.showCreateModal = false;
            this.showEditModal = false;
            this.formData = {
                rule_type: 'option',
                answer_option_id: '',
                operator: '',
                value: '',
                target_block_id: '',
                priority: {{ $rules->count() + 1 }}
            };
            this.formAction = `{{ route('admin.survey.questions.branch-rules.store', ['survey' => $survey->id, 'question' => $question->id]) }}`;
            this.modalTitle = 'Tambah Aturan Percabangan';
        },

        init() {
            this.formAction = `{{ route('admin.survey.questions.branch-rules.store', ['survey' => $survey->id, 'question' => $question->id]) }}`;
            this.modalTitle = 'Tambah Aturan Percabangan';
        }
    }
}
</script>

@if(session('success'))
<div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
    <div class="flex items-center">
        <i class="fas fa-check-circle mr-2"></i>
        {{ session('success') }}
    </div>
</div>
@endif

@if(session('error'))
<div class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
    <div class="flex items-center">
        <i class="fas fa-exclamation-circle mr-2"></i>
        {{ session('error') }}
    </div>
</div>
@endif

@if($errors->any())
<div class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 8000)">
    <div>
        <div class="flex items-center mb-2">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <span class="font-medium">Terdapat kesalahan:</span>
        </div>
        <ul class="list-disc list-inside text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif
@endsection
