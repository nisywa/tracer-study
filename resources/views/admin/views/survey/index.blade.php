@extends('admin.layouts.app')
@section('title', 'Manajemen Survei')

@section('content')
        <!-- table 1 -->

        <div class="flex flex-wrap -mx-3">
          <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
              <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex items-center justify-between">
                <h6 class="dark:text-white">Daftar Survei</h6>
                <a href="{{route('admin.survey.create')}}">
                <button type="button" class="inline-block px-8 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                  <i class="fas fa-plus mr-2"></i> Tambah Survei
                </button>
                </a>
              </div>
              <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-0 overflow-x-auto">
                  <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                    <thead class="align-bottom">
                      <tr>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Nama Survei</th>

                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Tanggal Aktif</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Tipe Survei</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Deskripsi</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">aksi</th>

                      </tr>
                    </thead>
                    <tbody>
                    @foreach($survey as $srvy)
                      <tr>
                        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <div class="flex  flec-col px-2 py-1">
                              <h6 class="mb-0 text-sm leading-normal dark:text-white">{{ $srvy->nama }}</h6>
                          </div>
                        </td>

                        <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <span class="bg-gradient-to-tl {{$srvy->status =='Aktif' ? 'from-emerald-500 to-teal-400' : 'from-slate-600 to-slate-300'}}  px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">{{ $srvy->status }}</span>
                        </td>
                        <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">{{ $srvy->tanggal_mulai." "."--"." ".$srvy->tanggal_selesai }}</span>
                        </td>
                        <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          
                          <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">{{$srvy->type_survei =='alumni' ? 'lulusan' : 'pengguna lulusan'}}</span>
                        </td>
                        <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">{{ $srvy->deskripsi }}</span>
                        </td>
                        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <div class="icon-container">
                            <!-- Add question -->
                            <a href="{{ route('admin.survey.add_question', $srvy) }}" class="icon-link" data-tooltip="Tambah Pertanyaan">
                                <i class="fas fa-file-alt"></i>
                            </a>
                            <!-- Add user -->

                            <button type="button"
                            id="openModal" class="icon-link" data-tooltip="Tambah User">
                            <i class="fas fa-user-plus"></i>
                            </button>

                            <a href="javascript:;" class="icon-link" data-tooltip="Duplicate Survei" onclick="event.preventDefault(); document.getElementById('duplicate-form-{{ $srvy->id }}').submit();">
                                <i class="fas fa-copy"></i>
                            </a>
                            <form id="duplicate-form-{{ $srvy->id }}" action="{{ route('admin.survey.duplicate', $srvy->id) }}" method="POST" style="display: none;">
                              @csrf
                              @method('POST')
                            </form>


                            <!-- pop up modal import  -->
                          <form action="{{ route('admin.survey.import') }}" method="POST"  enctype="multipart/form-data">
                          <!-- @csrf -->
                          <div id="uploadModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-gray-800 bg-opacity-50">
                              <div class="bg-white rounded-lg shadow-lg w-96">
                                  <div class="flex items-center justify-between p-4 border-b">
                                      <h3 class="text-lg font-bold">Tambah User Survei ...</h3>
                                      <button id="closeModal" class="text-gray-500 hover:text-gray-700">&times;</button>
                                  </div>
                                  <div class="p-4">
                                      <form id="uploadForm">
                                          <label for="fileInput" class="block text-sm font-medium text-gray-700 mb-2">Choose Excel File</label>
                                          <input type="file" id="fileInput" name="file" accept=".xls,.xlsx" class="block w-full text-sm text-gray-700 border rounded-lg cursor-pointer focus:ring-blue-500 focus:border-blue-500">
                                          <input type="hidden" name="survey_id" value="{{ $srvy->id }}">
                                          <p class="mt-2 text-sm text-gray-500">Only .xls, .xlsx, .csv files are supported.</p>
                                          <div class="mt-4 flex justify-end">
                                              <button type="button" id="cancelUpload" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 mr-2">Cancel</button>
                                              <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-lg hover:bg-blue-600">Upload</button>
                                          </div>
                                      </form>
                                  </div>
                              </div>
                          </div>
                          </form>





                            <!-- Edit -->
                            <a href="{{ route('admin.survey.edit', $srvy) }}" class="icon-link" data-tooltip="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <!-- Delete -->
                            <a href="javascript:;" class="icon-link" data-tooltip="Delete" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $srvy->id }}').submit();">
                                <i class="fas fa-trash"></i>
                            </a>
                            <form id="delete-form-{{ $srvy->id }}" action="{{ route('admin.survey.destroy', $srvy->id) }}" method="POST" style="display: none;">
                              @csrf
                              @method('DELETE')
                            </form>
                            <!-- Details -->
                            <a href="{{ route('admin.survey.details', $srvy) }}" class="icon-link" data-tooltip="Details">
                                <i class="fas fa-info-circle"></i>
                            </a>
                          </div>
                        </td>
                      </tr>
                      @endforeach


                    </tbody>
                  </table>
                  <div class="p-4">
                    {{ $survey->links() }}
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

@endsection
