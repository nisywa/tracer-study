@extends('admin.layout.app')

@section('content')
<!-- table 1 -->


<div class="flex flex-wrap -mx-3">
          <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
              <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex items-center justify-between">
                <h6 class="dark:text-white">Daftar Pertanyaan</h6>
                <a href="tambah_pertanyaan.html">
                <button type="button" class="inline-block px-8 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                  <i class="fas fa-plus mr-2"></i> Tambah Pertanyaan
                </button>
                </a>
              </div>

             
              <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-0 overflow-x-auto">
                  <table  id="dynamicTable" class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                    <thead class="align-bottom">
                      <tr>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Pertanyaan</th>
                        
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Deskripsi</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Blok</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Tipe Jawaban</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Keterangan Jawaban</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Aksi</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <div class="flex flex-col px-2 py-1">
                                <input type="text" class="text-sm leading-normal dark:text-white bg-transparent outline-none" placeholder="Tulis Pertanyaan disini">
                            </div>
                        </td>
                        
                        
                        
                        <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <div class="flex flex-col px-2 py-1">
                            <input type="text" class="text-sm leading-normal dark:text-white bg-transparent outline-none" placeholder="Tulis Deskripsi disini">
                        </div>
                        </td>
                        <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">Pribadi</span>
                        </td>
                        
                        <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                            <select id="inputType" class="text-xs font-semibold leading-tight border border-gray-400 rounded px-2 py-1" onchange="changeInputType()">
                                <option value="" disabled selected hidden>Pilih Tipe</option>
                                <option value="option1">Short Answer</option>
                                <option value="option2">Paragraph</option>
                                <option value="option3">Rating</option>
                                <option value="option5">Checkboxes</option>
                                <option value="option6">Multiple Choices</option>
                            </select>
                        </td>
                        
                        <td id="personalColumn" class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                            <span class="text-xs font-semibold leading-tight text-slate-400">Tipe Jawaban</span>
                        </td>

                        

                        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <div class="icon-container">
                            <!-- Add -->
                            <a class="icon-link" data-tooltip="Tambah">
                                <i class="fas fa-plus"></i>
                            </a>
                            <!-- Copy -->
                            <a href="javascript:;" class="icon-link" data-tooltip="Duplicate">
                                <i class="fas fa-copy"></i>
                            </a>
                            <!-- Delete -->
                            <a href="javascript:;" class="icon-link" data-tooltip="Delete">
                                <i class="fas fa-trash"></i>
                            </a>
                            
                          </div>
                        </td>
                      </tr>

                      

                      
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

<script src="{{asset('assets/js/tipejawaban.js')}}"></script>
@endsection