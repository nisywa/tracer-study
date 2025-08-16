@extends('admin.layouts.main')

@section('title', 'Manajemen Blok - ' . $survey->nama)

@section('content')
<div x-data="blockManager()" class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Blok</h1>
            <p class="text-gray-600">{{ $survey->nama }}</p>
        </div>
        <div class="flex space-x-3">
            <button @click="showCreateModal = true" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Tambah Blok
            </button>
            <a href="{{ route('admin.survey.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="mb-4">
        <form method="GET" class="flex space-x-2">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Cari berdasarkan kode atau nama blok..." 
                   class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-search"></i>
            </button>
            @if(request('search'))
                <a href="{{ route('admin.survey.blocks.index', $survey->id) }}" 
                   class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-colors">
                    <i class="fas fa-times"></i>
                </a>
            @endif
        </form>
    </div>

    <!-- Info Panel -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Tentang Manajemen Blok</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li>Blok adalah seksi/bagian dalam survei yang berisi kumpulan pertanyaan terkait</li>
                        <li>Pertanyaan akan dieksekusi berurutan berdasarkan urutan blok dan urutan dalam blok</li>
                        <li>Anda dapat mengatur aturan percabangan untuk melompat ke blok tertentu berdasarkan jawaban</li>
                        <li>Blok dengan status "Terminal" akan mengakhiri survei setelah pertanyaan terakhir di blok tersebut</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Blocks Table -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Urutan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kode
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nama Blok
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Deskripsi
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jumlah Pertanyaan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="blocks-tbody">
                    @forelse($blocks as $block)
                        <tr data-block-id="{{ $block->id }}" class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-900">{{ $block->urutan }}</span>
                                    <div class="ml-2 cursor-move text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-grip-vertical"></i>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                    {{ $block->kode }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $block->nama }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-500 max-w-xs truncate">
                                    {{ $block->deskripsi ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">{{ $block->questions_count ?? $block->questions->count() }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($block->is_terminal)
                                    <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                        Terminal
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                        Normal
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button @click="editBlock({{ $block->id }})" 
                                            class="text-blue-600 hover:text-blue-900 transition-colors">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button @click="deleteBlock({{ $block->id }})" 
                                            class="text-red-600 hover:text-red-900 transition-colors">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <i class="fas fa-layer-group text-4xl mb-4"></i>
                                    <p class="text-lg">Belum ada blok</p>
                                    <p class="text-sm">Tambahkan blok pertama untuk mengatur struktur survei</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($blocks->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $blocks->links() }}
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
                                    <!-- Kode Blok -->
                                    <div>
                                        <label for="kode" class="block text-sm font-medium text-gray-700 mb-1">
                                            Kode Blok *
                                        </label>
                                        <input type="text" 
                                               name="kode" 
                                               id="kode"
                                               x-model="formData.kode"
                                               required 
                                               maxlength="20"
                                               pattern="[A-Z0-9]+"
                                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent uppercase"
                                               placeholder="contoh: A, B, C, D1, E2">
                                        <p class="mt-1 text-xs text-gray-500">Gunakan huruf besar dan/atau angka (A-Z, 0-9)</p>
                                    </div>

                                    <!-- Nama Blok -->
                                    <div>
                                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">
                                            Nama Blok *
                                        </label>
                                        <input type="text" 
                                               name="nama" 
                                               id="nama"
                                               x-model="formData.nama"
                                               required 
                                               maxlength="255"
                                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                               placeholder="contoh: Informasi Demografis">
                                    </div>

                                    <!-- Deskripsi -->
                                    <div>
                                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">
                                            Deskripsi
                                        </label>
                                        <textarea name="deskripsi" 
                                                  id="deskripsi"
                                                  x-model="formData.deskripsi"
                                                  rows="3"
                                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                  placeholder="Deskripsi singkat tentang blok ini (opsional)"></textarea>
                                    </div>

                                    <!-- Urutan -->
                                    <div>
                                        <label for="urutan" class="block text-sm font-medium text-gray-700 mb-1">
                                            Urutan *
                                        </label>
                                        <input type="number" 
                                               name="urutan" 
                                               id="urutan"
                                               x-model="formData.urutan"
                                               required 
                                               min="1"
                                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <p class="mt-1 text-xs text-gray-500">Urutan eksekusi blok dalam survei</p>
                                    </div>

                                    <!-- Is Terminal -->
                                    <div class="flex items-center">
                                        <input type="checkbox" 
                                               name="is_terminal" 
                                               id="is_terminal"
                                               x-model="formData.is_terminal"
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="is_terminal" class="ml-2 block text-sm text-gray-700">
                                            Blok Terminal (mengakhiri survei)
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" 
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            <span x-show="showCreateModal">Simpan Blok</span>
                            <span x-show="showEditModal" style="display: none;">Update Blok</span>
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
function blockManager() {
    return {
        showCreateModal: false,
        showEditModal: false,
        formAction: '',
        modalTitle: '',
        formData: {
            kode: '',
            nama: '',
            deskripsi: '',
            urutan: {{ ($blocks->count() > 0 ? $blocks->max('urutan') + 1 : 1) }},
            is_terminal: false
        },

        editBlock(blockId) {
            fetch(`{{ route('admin.survey.blocks.show', ['survey' => $survey->id, 'block' => ':id']) }}`.replace(':id', blockId))
                .then(response => response.json())
                .then(block => {
                    this.formData = {
                        kode: block.kode,
                        nama: block.nama,
                        deskripsi: block.deskripsi || '',
                        urutan: block.urutan,
                        is_terminal: block.is_terminal
                    };
                    this.formAction = `{{ route('admin.survey.blocks.update', ['survey' => $survey->id, 'block' => ':id']) }}`.replace(':id', blockId);
                    this.modalTitle = 'Edit Blok';
                    this.showEditModal = true;
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memuat data blok');
                });
        },

        deleteBlock(blockId) {
            if (confirm('Apakah Anda yakin ingin menghapus blok ini?\n\nPerhatian: Blok yang masih memiliki pertanyaan atau menjadi target aturan percabangan tidak dapat dihapus.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `{{ route('admin.survey.blocks.destroy', ['survey' => $survey->id, 'block' => ':id']) }}`.replace(':id', blockId);
                
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
                kode: '',
                nama: '',
                deskripsi: '',
                urutan: {{ ($blocks->count() > 0 ? $blocks->max('urutan') + 1 : 1) }},
                is_terminal: false
            };
            this.formAction = `{{ route('admin.survey.blocks.store', $survey->id) }}`;
            this.modalTitle = 'Tambah Blok Baru';
        },

        init() {
            this.formAction = `{{ route('admin.survey.blocks.store', $survey->id) }}`;
            this.modalTitle = 'Tambah Blok Baru';
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
