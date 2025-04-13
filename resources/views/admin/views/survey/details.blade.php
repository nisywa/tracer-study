@extends('admin.layouts.app')
@section('title', 'Details Survey')

@section('content')

<!-- table 1 info survei -->
<div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
              <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex items-center justify-between">
                  <h6 class="dark:text-white">Informasi Survei</h6>
                </div>
  
                <div class="flex-auto px-0 pt-0 pb-2">
                  <div class="p-0 overflow-x-auto">
                    <div class="flex-auto p-6">
                        <div class="flex flex-wrap -mx-3">
                        
                          <div class="w-full max-w-full px-3 shrink-0 md:w-4/12 md:flex-0">
                            <div class="mb-4">
                              <label for="username" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Nama Survei</label>
                              <h6 class="text-slate-700 dark:text-white/80">{{ $survey->nama }}</h6>
                            </div>
                          </div>
                          <div class="w-full max-w-full px-3 shrink-0 md:w-4/12 md:flex-0">
                            <div class="mb-4">
                              <label for="email" class="block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Status</label>
                              <!-- <div class="bg-gradient-to-tl from-emerald-500 to-teal-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Aktif</div> -->
                              <div class="bg-gradient-to-tl {{$survey->status =='Aktif' ? 'from-emerald-500 to-teal-400' : 'from-slate-600 to-slate-300'}}  px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">{{ $survey->status }}</div>
                            </div>
                          </div>
                          <div class="w-full max-w-full px-3 shrink-0 md:w-4/12 md:flex-0">
                            <div class="mb-4">
                              <label for="email" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Tanggal Aktif</label>
                              <h6 class="text-slate-700 dark:text-white/80">{{ $survey->tanggal_mulai." -- ".$survey->tanggal_selesai }}</h6>
                            </div>
                          </div>
                        
                        </div>
                        
                        
                        
                      </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        
        

        <!-- table 2 -->

        <div class="flex flex-wrap -mx-3">
          <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
              <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex items-center justify-between">
                <h6 class="dark:text-white">Daftar Pertanyaan</h6>
              </div>

              <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-0 overflow-x-auto">
                  <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                    <thead class="align-bottom">
                      <tr>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Pertanyaan</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Blok</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Tipe Jawaban</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Keterangan Jawaban</th> 
                      </tr>
                    </thead>
                    <tbody>
                    @foreach($template_pertanyaan as $tanya)  
                    <tr>
                      <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 shadow-transparent">
                        <div class="flex flex-col px-2 py-1">
                          <h6 class="mb-0 text-sm leading-normal dark:text-white break-words whitespace-normal">
                            {{ $tanya->pertanyaan }}
                          </h6> 
                        </div>
                      </td>

                      <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 shadow-transparent">
                        <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400 break-words whitespace-normal">
                          {{ $tanya->blok }}
                        </span>
                      </td>

                      <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 shadow-transparent">
                        <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400 break-words whitespace-normal">
                          {{ $tanya->tipe }}
                        </span>
                      </td>

                      <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 shadow-transparent">
                        <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400 break-words whitespace-normal">
                          {{ $tanya->template_jawaban }}
                        </span>
                      </td>
                    </tr>

                    @endforeach
                    </tbody>
                  </table>
                  <div class="p-4">
                    {{ $template_pertanyaan->links() }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- table 3 -->

        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
              <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex items-center justify-between">
                  <h6 class="dark:text-white">Daftar User</h6>
                </div>
  
                
               
                
  
               
                
                <div class="flex-auto px-0 pt-0 pb-2">
                  <div class="p-0 overflow-x-auto">
                    <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                      <thead class="align-bottom">
                        <tr>
                            <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Nama</th>
                            <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">NIP</th>
                            <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Email</th>
                            <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">No HP</th>
                            <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Kepala BPS</th>
                        </tr>
                      </thead>
                      <tbody>
                        
                        @foreach($survey_user as $alumnus)
                                <tr>
                                    <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <div class="flex flec-col px-2 py-1">
                                            <h6 class="mb-0 text-sm leading-normal dark:text-white">{{ $alumnus->nama }}</h6>
                                        </div>
                                    </td>
                                    <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">{{ $alumnus->nip }}</span>
                                    </td>
                                    <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">{{ $alumnus->email }}</span>
                                    </td>
                                    <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">{{ $alumnus->no_hp }}</span>
                                    </td>
                                    <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">{{ $alumnus->kepala_bps }}</span>
                                    </td>
                                </tr>
                                @endforeach
                      </tbody>
                    </table>
                    <div class="p-4">
                      {{ $survey_user->links() }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>

        <!-- biar otomatis ke bawah -->
        <script>
        window.addEventListener('DOMContentLoaded', () => {
          const cells = document.querySelectorAll('td span, td h6');

          cells.forEach(cell => {
            cell.style.wordWrap = 'break-word';
            cell.style.whiteSpace = 'normal';
          });
        });
      </script>

@endsection