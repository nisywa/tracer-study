@extends('admin.layouts.app')
@section('title', 'Managemen Alumni')

@section('content')

    <!-- table 1 -->
    <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
        <div class="font-bold">
            @if(session('success'))
            <div class="bg-green-100 border-t-4 border-green-500 rounded-b text-green-900 px-4 py-3 shadow-md alert alert-success mb-6" role="alert">
                <div class="flex">
                    <div class="py-1">
                        <svg class="fill-current h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M10 0C4.48 0 0 4.48 0 10s4.48 10 10 10 10-4.48 10-10S15.52 0 10 0zm5 7.5l-6.25 6.25-3.75-3.75 1.41-1.41 2.34 2.34 4.84-4.84L15 7.5z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold">{{ session('success') }}</p>
                    </div>
                </div>
            </div>


            @elseif(session('error'))
                <div class="bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 shadow-md alert alert-danger mb-6" role="alert">
                    <div class="flex">
                        <div class="py-1">
                            <svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif
            </div>



            <div
                class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                <!-- daftar user dan searchbar -->
                <div
                    class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex items-center justify-between">
                    <h6 class="dark:text-white">Daftar User Alumni</h6>
                    <div class="relative flex items-center w-auto">
                        <div class="relative flex items-stretch">
                            <form action="{{ route('admin.alumni.index') }}" method="GET" class="flex items-center">
                                <span class="text-sm ease leading-5.6 absolute z-50 flex h-full items-center whitespace-nowrap rounded-lg rounded-tr-none rounded-br-none border border-r-0 border-transparent bg-transparent py-2 px-2.5 text-center font-normal text-slate-500 transition-all">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    class="pl-9 text-sm focus:shadow-primary-outline ease w-1/4 leading-5.6 relative block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow"
                                    placeholder="Search by name or NIP..." />
                                @if(request('search'))
                                    <a href="{{ route('admin.alumni.index') }}" class="ml-2 text-gray-500 hover:text-gray-700">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>

                <!-- button import export email dropdown -->
                @if(!auth()->user()->hasRole('supervisor'))
                <div
                    class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex items-center justify-between">
                   
                    <div class="flex items-center gap-4">
                    
                        <button type="button"
                            id="openModal" class="inline-block px-8 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                            <i class="fas fa-file-upload mr-2"></i> Import Excel
                        </button>

                        <!-- pop up modal import  -->
                        <form action="{{ route('admin.alumni.import') }}" method="POST"  enctype="multipart/form-data">
                        @csrf
                        <div id="uploadModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-gray-800 bg-opacity-50">
                            <div class="bg-white rounded-lg shadow-lg w-96">
                                <div class="flex items-center justify-between p-4 border-b">
                                    <h3 class="text-lg font-bold">Upload File</h3>
                                    <button id="closeModal" class="text-gray-500 hover:text-gray-700">&times;</button>
                                </div>
                                <div class="p-4">
                                    <form id="uploadForm">
                                        <label for="fileInput" class="block text-sm font-medium text-gray-700 mb-2">Choose Excel File</label>
                                        <input type="file" id="fileInput" name="file" accept=".xls,.xlsx" class="block w-full text-sm text-gray-700 border rounded-lg cursor-pointer focus:ring-blue-500 focus:border-blue-500">
                                        <p class="mt-2 text-sm text-gray-500">Only .xls and .xlsx files are supported.</p>
                                        <div class="mt-4 flex justify-end">
                                            <button type="button" id="cancelUpload" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 mr-2">Cancel</button>
                                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-lg hover:bg-blue-600">Upload</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        </form>
                    </div>

                        <a href="{{ route('admin.alumni.export') }}">
                            <button type="button"
                                class="inline-block px-8 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                                <i class="fas fa-file-excel mr-2"></i> Export Excel
                            </button>
                        </a>
                    
                        <a href="{{ route('admin.alumni.create') }}">
                            <button type="button" class="inline-block px-8 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                                <i class="fas fa-plus mr-2"></i> Tambah Alumni
                            </button>
                        </a>
                     
                </div>
                @endif
                <div class="flex-auto px-0 pt-0 pb-2">
                    <div class="p-0 overflow-x-auto">
                        <table
                            class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                            <thead class="align-bottom">
                                <tr>
                                    <th
                                        class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Nama/NIP</th>
                                    <!-- <th
                                        class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        NIP</th> -->
                                    <th
                                        class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Email</th>
                                    
                                    <th
                                        class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Satuan Kerja</th>
                                    <th
                                        class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Unit Kerja</th>
                                    <th
                                        class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        No HP</th>
                                    <th
                                        class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        NIP Kepala BPS</th>
                                    <th
                                        class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Tahun Lulus</th>    

                                    <th
                                        class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Aksi</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($daftarAlumni as $alumnus)
                                <tr>
                                <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 shadow-transparent">
                                    <div class="flex flex-col px-2 py-1 break-words">
                                        <h6 class="mb-0 text-sm leading-normal dark:text-white break-words">{{ $alumnus->clean_name }}</h6>
                                        <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400 break-words">
                                            {{ $alumnus->nip }}
                                        </span>
                                    </div>
                                </td>

                                    <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 break-words shadow-transparent">
                                        <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">{{ $alumnus->user->email }}</span>
                                    </td>
                                    
                                    <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 shadow-transparent break-words">
                                        <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">{{ $alumnus->satuan_kerja }}</span>
                                    </td>
                                    <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 shadow-transparent break-words">
                                        <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400 break-words">{{ $alumnus->unit_kerja }}</span>
                                    </td>
                                    <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">{{ $alumnus->no_hp }}</span>
                                    </td>
                                    <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 shadow-transparent">
                                        <div class="flex flex-col px-2 py-1 break-words">

                                            <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400 break-words">
                                            {{ $alumnus->nip_kepala_bps }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">{{ $alumnus->tahun_lulus }}</span>
                                    </td>



                                    <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <div class="icon-container">
                                            <!-- edit -->
                                             @if(!auth()->user()->hasRole('supervisor'))
                                            <a href="{{ route('admin.alumni.edit', $alumnus) }}" class="icon-link" data-tooltip="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <!-- Delete -->
                                            <a href="javascript:;" class="icon-link" data-tooltip="Delete" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $alumnus->id }}').submit();">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            <form id="delete-form-{{ $alumnus->id }}" action="{{ route('admin.alumni.destroy', $alumnus) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="p-4">
                            {{ $daftarAlumni->links() }}
                        </div>

                    </div>







                </div>
            </div>
        </div>
    </div>

    <!-- js pop up modal import -->
    <script>
      const openModal = document.getElementById('openModal');
      const closeModal = document.getElementById('closeModal');
      const cancelUpload = document.getElementById('cancelUpload');
      const uploadModal = document.getElementById('uploadModal');

      openModal.addEventListener('click', () => {
        uploadModal.classList.remove('hidden');
      });

      closeModal.addEventListener('click', () => {
        uploadModal.classList.add('hidden');
      });

      cancelUpload.addEventListener('click', () => {
        uploadModal.classList.add('hidden');
      });

      document.getElementById('uploadForm').addEventListener('submit', (e) => {
        e.preventDefault();
        alert('File uploaded successfully!');
        uploadModal.classList.add('hidden');
      });
    </script>
<script>
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(el => {
        el.classList.add('opacity-0', 'transition-opacity', 'duration-500'); // Tambahkan efek fade-out
        setTimeout(() => el.remove(), 500); // Hapus setelah animasi selesai
    });
}, 3000);
</script>
<script>
    document.addEventListener('input', function (e) {
        if (e.target.classList.contains('auto-resize')) {
            e.target.style.height = 'auto';
            e.target.style.height = (e.target.scrollHeight) + 'px';
        }
    });
</script>




@endsection
