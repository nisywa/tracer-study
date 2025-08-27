<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>
        Tracer Study
    </title>
    <link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}" type="image/x-icon" />
    <link rel="stylesheet" href="{{ asset('assets/css/swiper-bundle.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/tailwind.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- ==== WOW JS ==== -->
    <script src="{{ asset('assets/js/wow.min.js') }}"></script>
    <script>
        new WOW().init();
    </script>
</head>

<body>

    <!-- Preloader -->
    <div class="preloader">
        <div class="preloader-inner">
            <div class="preloader-icon">
                <span></span>
                <span></span>
            </div>
        </div>
    </div>
    <!-- /End Preloader -->

    @include('user.layouts.navigation')

    @yield('content')

    <!-- ====== Banner Section Start -->
    <div class="relative z-10 overflow-hidden pt-[120px] pb-[60px] sm:pt-[140px] sm:pb-[80px] md:pt-[160px] lg:pt-[180px] dark:bg-dark">
        <div
            class="absolute bottom-0 left-0 w-full h-px bg-gradient-to-r from-stroke/0 via-stroke dark:via-dark-3 to-stroke/0">
        </div>
        <div class="container px-4">
            <div class="flex flex-wrap items-center -mx-4">
                <div class="w-full px-4">
                    <div class="text-center">
                        <h1
                            class="mb-4 text-2xl sm:text-3xl md:text-4xl lg:text-[40px] lg:leading-[1.2] font-bold text-dark dark:text-white">
                            Halo, <span class="block sm:inline">
                                @if($user->hasRole('alumni') && $user->alumni)
                                    {{ $user->alumni->nama }}
                                @elseif($user->hasRole('atasan') && $user->atasan)
                                    {{ $user->atasan->nama }}
                                @else
                                    {{ $user->name }}
                                @endif
                            </span></h1>
                        <p class="mb-5 text-sm sm:text-base text-body-color dark:text-dark-6 px-4 sm:px-0">
                            Yuk isi surveinya sekarang!
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ====== Banner Section End -->

    <!-- ====== Info Survei & User Start ====== -->
    <section id="contact" class="relative py-10 sm:py-16 md:py-20 lg:py-[120px]">
        <div class="absolute top-0 left-0 -z-[1] w-full dark:bg-dark h-full"></div>
        <div class="absolute top-0 left-0 -z-[1] h-full w-full bg-[#E9F9FF] dark:bg-dark-700"></div>
        <div class="container px-4">
            <div class="flex flex-wrap items-stretch -mx-4">

                <div class="w-full px-4 lg:w-7/12 xl:w-8/12 mb-8 lg:mb-0">
                    <div class="wow fadeInUp rounded-lg bg-white dark:bg-dark-2 py-6 px-4 sm:py-8 sm:px-6 md:py-10 md:px-8 lg:py-12 lg:px-10 xl:p-[60px] shadow-testimonial dark:shadow-none"
                        data-wow-delay=".2s">
                        <h3
                            class="mb-6 md:mb-8 text-xl sm:text-2xl md:text-[28px] md:leading-[1.42] font-semibold text-dark dark:text-white">
                            Informasi Survei
                        </h3>
                        
                        <!-- Mobile Card Layout (hidden on lg+) -->
                        <div class="block lg:hidden space-y-4">
                            @foreach ($survey as $srvy)
                                <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-4 bg-gray-50 dark:bg-dark-3 mb-5">
                                    <div class="mb-3">
                                        <h4 class="font-bold text-dark dark:text-white mb-2">{{ $srvy->nama }}</h4>
                                        <div class="flex flex-wrap items-center gap-2 mb-2">
                                            <span class="bg-gradient-to-tl {{$srvy->status_aktif =='Aktif' ? 'from-emerald-500 to-teal-400' : 'from-slate-600 to-slate-300'}} px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">{{ $srvy->status_aktif }}</span>
                                        </div>
                                        <p class="text-xs text-body-color dark:text-dark-6 mb-2">{{ $srvy->tanggal_mulai }} - {{ $srvy->tanggal_selesai }}</p>
                                        <p class="text-sm text-body-color dark:text-dark-6 mb-3">{{ $srvy->deskripsi }}</p>
                                    </div>
                                    <div class="text-center">
                                        @if ($srvy->status==0)
                                            @if ($srvy->status_aktif=='Aktif')
                                                <div class="bg-gradient-to-r from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-800/20 border border-emerald-200 dark:border-emerald-700 rounded-lg p-3">
                                                    <a href="{{ route('user.survey.survey', ['id' => $srvy->id]) }}" class="text-emerald-600 dark:text-emerald-400 font-bold text-sm flex items-center justify-center gap-2 hover:text-emerald-700 dark:hover:text-emerald-300 transition-colors duration-300">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        ISI SURVEI
                                                    </a>
                                                </div>
                                            @else
                                                <div class="bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 border border-red-200 dark:border-red-700 rounded-lg p-3">
                                                    <span class="text-red-600 dark:text-red-400 font-bold text-sm flex items-center justify-center gap-2">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        SURVEI KADALUWARSA
                                                    </span>
                                                </div>
                                            @endif
                                        @else
                                            @if ($srvy->status_aktif=='Aktif')
                                                <div class="bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-900/20 dark:to-slate-800/20 border border-slate-200 dark:border-slate-700 rounded-lg p-3">
                                                    <span class="text-slate-600 dark:text-slate-400 font-bold text-sm flex items-center justify-center gap-2">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        SELESAI
                                                    </span>
                                                </div>
                                            @else
                                                <div class="bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-900/20 dark:to-slate-800/20 border border-slate-200 dark:border-slate-700 rounded-lg p-3">
                                                    <span class="text-slate-600 dark:text-slate-400 font-bold text-sm flex items-center justify-center gap-2">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        DONE
                                                    </span>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                            @if (count($survey) == 0)
                                <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center">
                                    <p class="text-body-color dark:text-dark-6">Tidak ada survei yang aktif saat ini</p>
                                </div>
                            @endif
                        </div>

                        <!-- Desktop Table Layout (hidden on mobile) -->
                        <div class="hidden lg:block overflow-x-auto">
                            <table class="w-full min-w-full border border-gray-300 dark:border-gray-600 table-fixed">
                                <thead>
                                    <tr class="bg-gray-100 dark:bg-dark-3 text-dark dark:text-white">
                                        <th class="w-5/12 border border-gray-300 dark:border-gray-600 px-4 py-3 text-left font-semibold">Nama</th>
                                        <th class="w-auto border border-gray-300 dark:border-gray-600 px-4 py-3 text-left font-semibold">Status</th>
                                        <th class="w-4/12 border border-gray-300 dark:border-gray-600 px-4 py-3 text-left font-semibold">Deskripsi</th>
                                        <th class="w-1/12 border border-gray-300 dark:border-gray-600 px-4 py-3 text-left font-semibold">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="text-body-color dark:text-dark-6">
                                    @foreach ($survey as $srvy)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-dark-3 transition-colors">
                                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-3 font-bold">{{ $srvy->nama }}</td>
                                            <td class="border border-gray-300 dark:border-gray-600 px-2 py-3 text-center">
                                                <span class="bg-gradient-to-tl {{$srvy->status_aktif =='Aktif' ? 'from-emerald-500 to-teal-400' : 'from-slate-600 to-slate-300'}} px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">{{ $srvy->status_aktif }}</span>
                                                <br>
                                                <span class="text-sm"> {{ $srvy->tanggal_mulai }} - {{ $srvy->tanggal_selesai }}</span>
                                            </td>
                                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-3">{{ $srvy->deskripsi }}</td>
                                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-3 text-center relative group">
                                                @if ($srvy->status==0)
                                                    @if ($srvy->status_aktif=='Aktif')
                                                        <div class="bg-gradient-to-r from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-800/20 border border-emerald-200 dark:border-emerald-700 rounded-md px-2 py-1 inline-block">
                                                            <a href="{{ route('user.survey.survey', ['id' => $srvy->id]) }}" class="text-emerald-600 dark:text-emerald-400 font-bold text-xs flex items-center gap-1 hover:text-emerald-700 dark:hover:text-emerald-300 transition-colors duration-300">
                                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd"></path>
                                                                </svg>
                                                                ISI SURVEI
                                                            </a>
                                                        </div>
                                                    @else
                                                        <div class="bg-gradient-to-r from-red-50 to-rose-50 dark:from-red-900/10 dark:to-rose-900/10 border border-red-100 dark:border-red-800 rounded-md px-2 py-1 inline-block">
                                                            <span class="text-red-400 dark:text-red-300 font-bold text-xs flex items-center gap-1">
                                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                                </svg>
                                                                SURVEI KADALUWARSA
                                                            </span>
                                                        </div>
                                                    @endif
                                                @else
                                                    @if ($srvy->status_aktif=='Aktif')
                                                        <div class="bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-900/20 dark:to-slate-800/20 border border-slate-200 dark:border-slate-700 rounded-md px-2 py-1 inline-block">
                                                            <span class="text-slate-600 dark:text-slate-400 font-bold text-xs flex items-center gap-1">
                                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                                </svg>
                                                                SELESAI
                                                            </span>
                                                        </div>
                                                    @else
                                                        <div class="bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-900/20 dark:to-slate-800/20 border border-slate-200 dark:border-slate-700 rounded-md px-2 py-1 inline-block">
                                                            <span class="text-slate-600 dark:text-slate-400 font-bold text-xs flex items-center gap-1">
                                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                                </svg>
                                                                DONE
                                                            </span>
                                                        </div>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if (count($survey) == 0)
                                        <tr>
                                            <td colspan="4" class="border border-gray-300 dark:border-gray-600 px-4 py-6 text-center">
                                                Tidak ada survei yang aktif saat ini</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="w-full px-4 lg:w-5/12 xl:w-4/12">
                    <div class="wow fadeInUp rounded-lg bg-white dark:bg-dark-2 py-6 px-4 sm:py-8 sm:px-6 md:py-10 md:px-8 lg:py-12 lg:px-10 xl:p-[60px] shadow-testimonial dark:shadow-none"
                        data-wow-delay=".2s">
                        <div class="flex justify-between items-center mb-6 md:mb-8">
                            <h3 class="text-xl sm:text-2xl md:text-[28px] md:leading-[1.42] font-semibold text-dark dark:text-white">
                                Informasi User
                            </h3>
                            <button id="editToggle" onclick="toggleEdit()" 
                                class="px-0 py-2 text-sm font-semibold text-white transition-all ease-nav-brand cursor-pointer flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit
                            </button>
                        </div>

                        <!-- Display Success/Error Messages -->
                        @if(session('success'))
                            <div id="successMessage" class="mb-4 p-4 bg-green-50 border-l-4 border-green-400 text-green-800 dark:bg-green-900/20 dark:border-green-600 dark:text-green-300 rounded-r-md shadow-sm">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="font-medium">{{ session('success') }}</span>
                                </div>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-400 text-red-800 dark:bg-red-900/20 dark:border-red-600 dark:text-red-300 rounded-r-md shadow-sm">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="font-medium">{{ session('error') }}</span>
                                </div>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-400 text-red-800 dark:bg-red-900/20 dark:border-red-600 dark:text-red-300 rounded-r-md shadow-sm">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <p class="font-medium mb-1">Terdapat kesalahan:</p>
                                        <ul class="list-disc list-inside space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- User Information Form -->
                        <form id="userInfoForm" action="{{ route('user.profile.update') }}" method="POST">
                            @csrf
                            
                            <div class="space-y-4 md:space-y-6">
                                <div class="pb-3 border-b border-[#f1f1f1] dark:border-dark-3">
                                    <label class="block mb-2 text-sm font-medium text-body-color dark:text-dark-6">Nama</label>
                                    <p class="text-body-color/80 dark:text-dark-6 text-sm sm:text-base break-words">
                                        @if($user->hasRole('alumni') && $user->alumni)
                                            {{ $user->alumni->nama }}
                                        @elseif($user->hasRole('atasan') && $user->atasan)
                                            {{ $user->atasan->nama }}
                                        @else
                                            {{ $user->name }}
                                        @endif
                                    </p>
                                </div>

                                <div class="pb-3 border-b border-[#f1f1f1] dark:border-dark-3">
                                    <label class="block mb-2 text-sm font-medium text-body-color dark:text-dark-6">Email</label>
                                    <p class="text-body-color/80 dark:text-dark-6 text-sm sm:text-base break-all">{{ $user->email }}</p>
                                </div>

                                <div class="pb-3 border-b border-[#f1f1f1] dark:border-dark-3">
                                    <label class="block mb-2 text-sm font-medium text-body-color dark:text-dark-6">NIP</label>
                                    <p class="text-body-color/80 dark:text-dark-6 text-sm sm:text-base">
                                        @if($user->hasRole('alumni') && $user->alumni)
                                            {{ $user->alumni->nip }}
                                        @elseif($user->hasRole('atasan') && $user->atasan)
                                            {{ $user->atasan->nip }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>

                                <div class="pb-3 border-b border-[#f1f1f1] dark:border-dark-3">
                                    <label class="block mb-2 text-sm font-medium text-body-color dark:text-dark-6">Jabatan</label>
                                    <div class="view-mode">
                                        <p class="text-body-color/80 dark:text-dark-6 text-sm sm:text-base break-words">
                                            @if($user->hasRole('alumni') && $user->alumni)
                                                {{ $user->alumni->jabatan ?? '-' }}
                                            @elseif($user->hasRole('atasan') && $user->atasan)
                                                {{ $user->atasan->jabatan ?? '-' }}
                                            @else
                                                -
                                            @endif
                                        </p>
                                    </div>
                                    <div class="edit-mode hidden">
                                        <input type="text" name="jabatan" 
                                            value="@if($user->hasRole('alumni') && $user->alumni){{ $user->alumni->jabatan }}@elseif($user->hasRole('atasan') && $user->atasan){{ $user->atasan->jabatan }}@endif"
                                            class="w-full px-3 py-2 text-sm border-2 border-gray-300 dark:border-gray-500 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 transition-colors"
                                            placeholder="Masukkan jabatan">
                                    </div>
                                </div>

                                <div class="pb-3 border-b border-[#f1f1f1] dark:border-dark-3">
                                    <label class="block mb-2 text-sm font-medium text-body-color dark:text-dark-6">Satuan Kerja</label>
                                    <div class="view-mode">
                                        <p class="text-body-color/80 dark:text-dark-6 text-sm sm:text-base break-words">
                                            @if($user->hasRole('alumni') && $user->alumni)
                                                {{ $user->alumni->satuan_kerja ?? '-' }}
                                            @elseif($user->hasRole('atasan') && $user->atasan)
                                                {{ $user->atasan->satuan_kerja ?? '-' }}
                                            @else
                                                -
                                            @endif
                                        </p>
                                    </div>
                                    <div class="edit-mode hidden">
                                        <input type="text" name="satuan_kerja" 
                                            value="@if($user->hasRole('alumni') && $user->alumni){{ $user->alumni->satuan_kerja }}@elseif($user->hasRole('atasan') && $user->atasan){{ $user->atasan->satuan_kerja }}@endif"
                                            class="w-full px-3 py-2 text-sm border-2 border-gray-300 dark:border-gray-500 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 transition-colors"
                                            placeholder="Masukkan satuan kerja">
                                    </div>
                                </div>

                                <div class="pb-3 border-b border-[#f1f1f1] dark:border-dark-3">
                                    <label class="block mb-2 text-sm font-medium text-body-color dark:text-dark-6">Unit Kerja</label>
                                    <div class="view-mode">
                                        <p class="text-body-color/80 dark:text-dark-6 text-sm sm:text-base break-words">
                                            @if($user->hasRole('alumni') && $user->alumni)
                                                {{ $user->alumni->unit_kerja ?? '-' }}
                                            @elseif($user->hasRole('atasan') && $user->atasan)
                                                {{ $user->atasan->unit_kerja ?? '-' }}
                                            @else
                                                -
                                            @endif
                                        </p>
                                    </div>
                                    <div class="edit-mode hidden">
                                        <input type="text" name="unit_kerja" 
                                            value="@if($user->hasRole('alumni') && $user->alumni){{ $user->alumni->unit_kerja }}@elseif($user->hasRole('atasan') && $user->atasan){{ $user->atasan->unit_kerja }}@endif"
                                            class="w-full px-3 py-2 text-sm border-2 border-gray-300 dark:border-gray-500 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 transition-colors"
                                            placeholder="Masukkan unit kerja">
                                    </div>
                                </div>

                                <div class="pb-3">
                                    <label class="block mb-2 text-sm font-medium text-body-color dark:text-dark-6">No HP</label>
                                    <div class="view-mode">
                                        <p class="text-body-color/80 dark:text-dark-6 text-sm sm:text-base">
                                            @if($user->hasRole('alumni') && $user->alumni)
                                                {{ $user->alumni->no_hp ?? '-' }}
                                            @elseif($user->hasRole('atasan') && $user->atasan)
                                                {{ $user->atasan->no_hp ?? '-' }}
                                            @else
                                                -
                                            @endif
                                        </p>
                                    </div>
                                    <div class="edit-mode hidden">
                                        <input type="text" name="no_hp" 
                                            value="@if($user->hasRole('alumni') && $user->alumni){{ $user->alumni->no_hp }}@elseif($user->hasRole('atasan') && $user->atasan){{ $user->atasan->no_hp }}@endif"
                                            class="w-full px-3 py-2 text-sm border-2 border-gray-300 dark:border-gray-500 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 transition-colors"
                                            placeholder="Masukkan nomor HP">
                                    </div>
                                </div>

                                <!-- Save and Cancel buttons (hidden by default) -->
                                <div id="actionButtons" class="hidden pt-4">
                                    <div class="flex gap-3">
                                        <button type="submit" 
                                            class="px-6 py-3 text-sm font-bold text-white bg-green-700 hover:bg-green-800 dark:bg-blue-600 dark:hover:bg-blue-700 border-2 border-green-700 hover:border-green-800 dark:border-blue-600 dark:hover:border-blue-700 rounded-lg transition-all duration-300 flex items-center gap-2 shadow-lg hover:shadow-xl transform hover:scale-105">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Simpan
                                        </button>
                                        <button type="button" onclick="cancelEdit()" 
                                            class="px-6 py-3 text-sm font-bold text-gray-800 bg-gray-400 hover:bg-gray-500 dark:text-white dark:bg-gray-600 dark:hover:bg-gray-700 border-2 border-gray-400 hover:border-gray-500 dark:border-gray-600 dark:hover:border-gray-700 rounded-lg transition-all duration-300 flex items-center gap-2 shadow-lg hover:shadow-xl transform hover:scale-105">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Batal
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ====== Info Survei & User End ====== -->

    @include('user.layouts.footer')

    <!-- ====== Back To Top Start -->
    <a href="javascript:void(0)"
        class="back-to-top fixed bottom-8 left-auto right-8 z-[999] hidden h-10 w-10 items-center justify-center rounded-md bg-primary text-white shadow-md transition duration-300 ease-in-out hover:bg-dark">
        <span class="mt-[6px] h-3 w-3 rotate-45 border-l border-t border-white"></span>
    </a>
    <!-- ====== Back To Top End -->

    <!-- ====== All Scripts -->
    <script src="{{ asset('assets/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script>
        // ==== for menu scroll
        const pageLink = document.querySelectorAll(".ud-menu-scroll");

        pageLink.forEach((elem) => {
            elem.addEventListener("click", (e) => {
                e.preventDefault();
                document.querySelector(elem.getAttribute("href")).scrollIntoView({
                    behavior: "smooth",
                    offsetTop: 1 - 60,
                });
            });
        });

        // section menu active
        function onScroll(event) {
            const sections = document.querySelectorAll(".ud-menu-scroll");
            const scrollPos =
                window.pageYOffset ||
                document.documentElement.scrollTop ||
                document.body.scrollTop;

            for (let i = 0; i < sections.length; i++) {
                const currLink = sections[i];
                const val = currLink.getAttribute("href");
                const refElement = document.querySelector(val);
                const scrollTopMinus = scrollPos + 73;
                if (
                    refElement.offsetTop <= scrollTopMinus &&
                    refElement.offsetTop + refElement.offsetHeight > scrollTopMinus
                ) {
                    document
                        .querySelector(".ud-menu-scroll")
                        .classList.remove("active");
                    currLink.classList.add("active");
                } else {
                    currLink.classList.remove("active");
                }
            }
        }

        window.document.addEventListener("scroll", onScroll);

        // Auto-hide success message after 30 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const successMessage = document.getElementById('successMessage');
            if (successMessage) {
                setTimeout(function() {
                    successMessage.style.transition = 'opacity 0.5s ease-out';
                    successMessage.style.opacity = '0';
                    setTimeout(function() {
                        successMessage.remove();
                    }, 500);
                }, 30000); // 30 seconds
            }
        });

        // Toggle edit mode for user information
        function toggleEdit() {
            const editButton = document.getElementById('editToggle');
            const actionButtons = document.getElementById('actionButtons');
            const viewModeElements = document.querySelectorAll('.view-mode');
            const editModeElements = document.querySelectorAll('.edit-mode');

            // Toggle visibility
            viewModeElements.forEach(el => el.classList.toggle('hidden'));
            editModeElements.forEach(el => el.classList.toggle('hidden'));
            actionButtons.classList.toggle('hidden');

            // Update button text and style
            if (editButton.innerHTML.includes('Edit')) {
                editButton.innerHTML = `
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Batal
                `;
                editButton.className = 'px-0 py-2 text-sm font-semibold text-white transition-all ease-nav-brand cursor-pointer flex items-center gap-2';
            } else {
                editButton.innerHTML = `
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                `;
                editButton.className = 'px-0 py-2 text-sm font-semibold text-white transition-all ease-nav-brand cursor-pointer flex items-center gap-2';
            }
        }

        function cancelEdit() {
            // Reset form to original values
            const form = document.getElementById('userInfoForm');
            form.reset();
            
            // Return to view mode
            toggleEdit();
        }

        // Testimonial
        const testimonialSwiper = new Swiper(".testimonial-carousel", {
            slidesPerView: 1,
            spaceBetween: 30,

            // Navigation arrows
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },

            breakpoints: {
                640: {
                    slidesPerView: 2,
                    spaceBetween: 30,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                },
                1280: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                },
            },
        });
    </script>
</body>

</html>
