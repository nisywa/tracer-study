@extends('admin.layouts.app')
@section('title', 'Dashboard')

@section('content')
        <!-- row 1 -->
        <div class="flex flex-wrap -mx-3">
          <!-- card1 -->
          <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/3">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
              <div class="flex-auto p-4">
                <div class="flex flex-row -mx-3">
                  <div class="flex-none w-2/3 max-w-full px-3">
                    <div>
                      <p class="mb-0 font-sans text-sm font-semibold leading-tight text-ellipsis overflow-hidden whitespace-nowrap uppercase dark:text-white dark:opacity-60">
                        Survei
                      </p>
                      <h5 class="mb-2 font-bold dark:text-white">{{ $totalSurvey }}</h5>
                    </div>
                  </div>
                  <div class="px-3 text-right basis-1/3">
                    <div class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-blue-500 to-violet-500">
                      <i class="ni leading-none ni-chart-bar-32 text-lg relative top-3.5 text-white"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- card2 -->
          <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/3">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
              <div class="flex-auto p-4">
                <div class="flex flex-row -mx-3">
                  <div class="flex-none w-2/3 max-w-full px-3">
                    <div>
                      <p class="mb-0 font-sans text-sm font-semibold leading-tight text-ellipsis overflow-hidden whitespace-nowrap uppercase dark:text-white dark:opacity-60">
                        pengguna lulusan
                      </p>
                      <h5 class="mb-2 font-bold dark:text-white">{{$totalAtasan}}</h5>
                    </div>
                  </div>
                  <div class="px-3 text-right basis-1/3">
                    <div class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-red-600 to-orange-600">
                      <i class="ni leading-none ni-briefcase-24 text-lg relative top-3.5 text-white"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- card3 -->
          <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/3">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
              <div class="flex-auto p-4">
                <div class="flex flex-row -mx-3">
                  <div class="flex-none w-2/3 max-w-full px-3">
                    <div>
                      <p class="mb-0 font-sans text-sm font-semibold leading-tight text-ellipsis overflow-hidden whitespace-nowrap uppercase dark:text-white dark:opacity-60">
                        lulusan
                      </p>
                      <h5 class="mb-2 font-bold dark:text-white">{{$totalAlumni}}</h5>
                    </div>
                  </div>
                  <div class="px-3 text-right basis-1/3">
                    <div class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-emerald-500 to-teal-400">
                      <i class="ni leading-none ni-paper-diploma text-lg relative top-3.5 text-white"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>


        <div class="flex flex-wrap mt-6 -mx-3">
          <div class="w-full max-w-full px-3 mt-0 mb-6 lg:mb-0 lg:w-full lg:flex-none">
            <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl dark:bg-gray-950 border-black-125 rounded-2xl bg-clip-border">
              <div class="p-4 pb-0 mb-0 rounded-t-4">
                <div class="flex justify-between">
                  <h6 class="mb-2 dark:text-white">Persentase Pengerjaan Survei Aktif</h6>
                </div>
              </div>
              <div class="overflow-x-auto">
                <table class="items-center w-full mb-4 align-top border-collapse border-gray-200 dark:border-white/40">
                  <thead class="align-bottom">
                    <tr>
                      <th
                        class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                        Nama</th>
                      <th
                        class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                        Persentase progress</th>                  
                                    
                    </tr>
                  </thead>  
                  <tbody>
                  @foreach ($surveyAktif as $survey)
                      <tr>
                        <td class="p-2 align-middle bg-transparent border-b w-3/10 whitespace-nowrap dark:border-white/40">
                          <div class="flex items-center px-2 py-1">
                            <div class="ml-6">
                              <p class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-60">{{  $survey->nama }}</p>
                            </div>
                          </div>
                        </td>
                        
                        <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap dark:border-white/40">
                          <div class="text-center">
                              <div class="flex flex-col items-center">
                                  <!-- Progress value -->
                                  <h6 class="mb-1 text-sm leading-normal dark:text-white">
                                      {{ $survey->completionRate }}%
                                  </h6>
                                  <!-- Progress bar container -->
                                  <div class="w-full h-1.5 bg-gray-200 rounded-full dark:bg-gray-700">
                                      <!-- Progress bar -->
                                      <div class="h-1.5 rounded-full  bg-blue-500" 
                                          style="width: {{ $survey->completionRate }}%;">
                                      </div>
                                  </div>
                                  <!-- Sales value -->
                                  <span class="mt-1 text-xs text-gray-600 dark:text-gray-400">
                                      {{ $survey->completedResponses }} responden telah mengisi dari {{ $survey->totalResponses }} responden
                                  </span>
                              </div>
                          </div>
                        </td>
                      </tr>
                  @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          
        </div>



      <!-- end cards -->
@endsection