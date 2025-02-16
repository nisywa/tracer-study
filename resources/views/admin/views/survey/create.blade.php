@extends('admin.layouts.app')
@section('title', 'Create Alumni')

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
                              <label for="nama" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Nama Survei</label>
                              <input type="text" name="nama" value="{{ old('nama') }}" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                              @error('nama')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                          <div class="w-full max-w-full px-3 shrink-0 md:w-4/12 md:flex-0">
                            <div class="mb-4">
                              <label for="tanggal_mulai" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Tanggal Mulai</label>
                              <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                              @error('tanggal_mulai')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                          <div class="w-full max-w-full px-3 shrink-0 md:w-4/12 md:flex-0">
                            <div class="mb-4">
                              <label for="tanggal_selesai" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Tanggal Selesai</label>
                              <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                              @error('tanggal_selesai')
                                  <span class="text-red-500 text-xs">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                          <div class="w-full max-w-full px-3 shrink-0 md:w-4/12 md:flex-0">
                                <label for="type_survei" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Tipe</label>
                                <select name="type_survei" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
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

                        <div class="flex justify-end items-center mt-4">
                            
                            <button type="submit" class="inline-block px-8 py-2 w-36 h-10 font-bold text-center text-white align-middle transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">Simpan</button> </a>
                            <a href="{{ route('admin.survey.index') }}" class="inline-block px-8 py-2 w-36 h-10 font-bold text-center align-middle transition-all ease-in border border-gray-300 rounded-lg text-gray-700 bg-transparent hover:bg-gray-100 text-xs tracking-tight-rem cursor-pointer mr-4">Batal</a>
                        </div>
                        
                    </div>   
                    <!-- form end -->
                    
                    
                </div>
              
              
            </div>
          </div>
        </div>
 </form>
@endsection