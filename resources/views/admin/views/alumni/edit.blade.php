@extends('admin.layouts.app')
@section('title', 'Edit Alumni')

@section('content')

<!-- table 1 -->

<form action="{{ route('admin.alumni.update',$alumnus) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                <div class="flex flex-wrap -mx-3">
                    <!-- form start -->
                    <div class="flex-auto p-6">
                        <p class="leading-normal uppercase dark:text-white dark:opacity-60 text-sm text-center">Informasi Alumni</p>
                        <hr class="h-px mx-0 my-4 bg-transparent border-0 opacity-25 bg-gradient-to-r from-transparent via-black/40 to-transparent dark:bg-gradient-to-r dark:from-transparent dark:via-white dark:to-transparent " />
                        <div class="flex flex-wrap -mx-3">
                            <div class="w-full px-3 mb-4">
                                <label for="nama" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Nama</label>
                                <input type="text" name="nama" value="{{ $alumnus->nama }}" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                                @error('nama')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="w-full px-3 mb-4">
                                <label for="email" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Email</label>
                                <input type="email" name="email" value="{{ $alumnus->user->email }}" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                                @error('email')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="w-full px-3 mb-4">
                                <label for="nim" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">NIM</label>
                                <input type="text" name="nim" value="{{ $alumnus->nim }}" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                                @error('nim')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="w-full px-3 mb-4">
                                <label for="no_hp" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">No HP</label>
                                <input type="text" name="no_hp" value="{{ $alumnus->no_hp }}" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                                @error('no_hp')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="w-full px-3 mb-4">
                                <label for="alamat" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Alamat</label>
                                <input type="text" name="alamat" value="{{ $alumnus->alamat }}" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                                @error('alamat')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="w-full px-3 mb-4">
                                <label for="jenis_kelamin" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                                    <option value="L" {{ $alumnus->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ $alumnus->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="w-full px-3 mb-4">
                                <label for="prodi" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Prodi</label>
                                <select name="prodi" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                                    <option value="D3" {{ $alumnus->prodi == 'D3' ? 'selected' : '' }}>D3 Statistika</option>
                                    <option value="D4" {{ $alumnus->prodi == 'D4' ? 'selected' : '' }}>D4 Statistika</option>
                                    <option value="D4K" {{ $alumnus->prodi == 'D4K' ? 'selected' : '' }}>D4 Komputasi Statistik</option>
                                </select>
                                @error('prodi')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="w-full px-3 mb-4">
                                <label for="tahun_lulus" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Tahun Lulus</label>
                                <input type="number" name="tahun_lulus" value="{{ $alumnus->tahun_lulus }}" min="2000" max="{{ now()->year }}" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                                @error('tahun_lulus')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <button type="submit" class="inline-block px-8 py-2 w-36 h-10 font-bold text-center text-white align-middle transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">Simpan</button>
                        <a href="{{ route('admin.alumni.index') }}" class="inline-block px-8 py-2 w-36 h-10 font-bold text-center align-middle transition-all ease-in border border-gray-300 rounded-lg text-gray-700 bg-transparent hover:bg-gray-100 text-xs tracking-tight-rem cursor-pointer mr-4">Batal</a>
                    </div>
                    <!-- form end -->
                </div>
            </div>
        </div>
    </div>
</form>

@endsection