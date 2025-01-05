@section('content')

<!-- table 1 -->

<div class="flex flex-wrap -mx-3">
          <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
              <!-- daftar user dan searchbar -->
              <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex items-center justify-between">
                <h6 class="dark:text-white">Daftar User Alumni</h6>
                <div class="relative flex items-center w-auto">
                  <div class="relative flex items-stretch">
                    <span class="text-sm ease leading-5.6 absolute z-50 flex h-full items-center whitespace-nowrap rounded-lg rounded-tr-none rounded-br-none border border-r-0 border-transparent bg-transparent py-2 px-2.5 text-center font-normal text-slate-500 transition-all">
                      <i class="fas fa-search"></i>
                    </span>
                    <input 
                      type="text" 
                      class="pl-9 text-sm focus:shadow-primary-outline ease w-1/4 leading-5.6 relative block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow" 
                      placeholder="Type here..." />
                  </div>
                </div>
              </div>
              
              <!-- button import export email dropdown -->
              <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex items-center justify-between">
                <a href="tambah_survei.html">
                <button type="button" class="inline-block px-8 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                  <i class="fas fa-file-excel mr-2"></i> Export Excel
                </button>
                </a>

                <a href="tambah_survei.html">
                    <button type="button" class="inline-block px-8 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                      <i class="fas fa-file-upload mr-2"></i> Import Excel
                    </button>
                </a>

                <a href="tambah_survei.html">
                    <button type="button" class="inline-block px-8 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                      <i class="fas fa-envelope mr-2"></i> Kirim Email ke Semua
                    </button>
                </a>

                <a href="tambah_survei.html">
                    <button type="button" class="inline-block px-8 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                      <i class="ni ni-single-02 mr-2"></i> Alumni
                    </button>
                </a>

                <a href="tambah_survei.html">
                    <button type="button" class="inline-block px-8 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                      <i class="ni ni-single-02 mr-2"></i> Atasan
                    </button>
                </a>

                <a href="tambah_user_manual.html">
                    <button type="button" class="inline-block px-8 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                      <i class="fas fa-plus mr-2"></i> Tambah User
                    </button>
                </a>


                
              </div>
              
              <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-0 overflow-x-auto">
                  <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                    <thead class="align-bottom">
                      <tr>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Nama Atasan</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Satuan Kerja</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Jabatan</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <div class="flex  flec-col px-2 py-1">
                              <h6 class="mb-0 text-sm leading-normal dark:text-white">Nisywa Zahra</h6> 
                          </div>
                        </td>
                        <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">BPS Kota Bekasi</span>
                        </td>
                        <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">Statistisi Ahli Pertama</span>
                        </td>
                        
                        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <div class="icon-container">
                            <!-- edit -->
                            <a href="tambah_user_manual.html" class="icon-link" data-tooltip="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <!-- Delete -->
                            <a href="javascript:;" class="icon-link" data-tooltip="Delete">
                                <i class="fas fa-trash"></i>
                            </a>
                            <!-- Details -->
                            <a href="javascript:;" class="icon-link" data-tooltip="Details">
                                <i class="fas fa-info-circle"></i>
                            </a>
                          </div>
                        </td>
                      </tr>

                      <tr>
                        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <div class="flex  flec-col px-2 py-1">
                              <h6 class="mb-0 text-sm leading-normal dark:text-white">Nisywa Zahra</h6> 
                          </div>
                        </td>
                        <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">BPS Kota Bekasi</span>
                        </td>
                        <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">Statistisi Ahli Pertama</span>
                        </td>
                        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <div class="icon-container">
                            <!-- edit -->
                            <a href="#" class="icon-link" data-tooltip="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <!-- Delete -->
                            <a href="javascript:;" class="icon-link" data-tooltip="Delete">
                                <i class="fas fa-trash"></i>
                            </a>
                            <!-- Details -->
                            <a href="javascript:;" class="icon-link" data-tooltip="Details">
                                <i class="fas fa-info-circle"></i>
                            </a>
                          </div>
                        </td>
                      </tr>
                      
                      <tr>
                        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <div class="flex  flec-col px-2 py-1">
                              <h6 class="mb-0 text-sm leading-normal dark:text-white">Nisywa Zahra</h6> 
                          </div>
                        </td>
                        <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">BPS Kota Bekasi</span>
                        </td>
                        <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">Statistisi Ahli Pertama</span>
                        </td>
                        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <div class="icon-container">
                            <!-- edit -->
                            <a href="#" class="icon-link" data-tooltip="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <!-- Delete -->
                            <a href="javascript:;" class="icon-link" data-tooltip="Delete">
                                <i class="fas fa-trash"></i>
                            </a>
                            <!-- Details -->
                            <a href="javascript:;" class="icon-link" data-tooltip="Details">
                                <i class="fas fa-info-circle"></i>
                            </a>
                          </div>
                        </td>
                      </tr>

                      <tr>
                        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <div class="flex  flec-col px-2 py-1">
                              <h6 class="mb-0 text-sm leading-normal dark:text-white">Nisywa Zahra</h6> 
                          </div>
                        </td>
                        <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">BPS Kota Bekasi</span>
                        </td>
                        <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">Statistisi Ahli Pertama</span>
                        </td>
                        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <div class="icon-container">
                            <!-- edit -->
                            <a href="#" class="icon-link" data-tooltip="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <!-- Delete -->
                            <a href="javascript:;" class="icon-link" data-tooltip="Delete">
                                <i class="fas fa-trash"></i>
                            </a>
                            <!-- Details -->
                            <a href="javascript:;" class="icon-link" data-tooltip="Details">
                                <i class="fas fa-info-circle"></i>
                            </a>
                          </div>
                        </td>
                      </tr>

                      <tr>
                        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <div class="flex  flec-col px-2 py-1">
                              <h6 class="mb-0 text-sm leading-normal dark:text-white">Nisywa Zahra</h6> 
                          </div>
                        </td>
                        <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">BPS Kota Bekasi</span>
                        </td>
                        <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">Statistisi Ahli Pertama</span>
                        </td>
                        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <div class="icon-container">
                            <!-- edit -->
                            <a href="#" class="icon-link" data-tooltip="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <!-- Delete -->
                            <a href="javascript:;" class="icon-link" data-tooltip="Delete">
                                <i class="fas fa-trash"></i>
                            </a>
                            <!-- Details -->
                            <a href="javascript:;" class="icon-link" data-tooltip="Details">
                                <i class="fas fa-info-circle"></i>
                            </a>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                

                <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex justify-end items-center">
                  <!-- Tombol Batal -->
                  <a href="manajemen_user.html">
                    <button
                      type="button"
                      class="inline-block px-8 py-2 w-36 h-10 font-bold text-center align-middle transition-all ease-in border border-gray-300 rounded-lg text-gray-700 bg-transparent hover:bg-gray-100 text-xs tracking-tight-rem cursor-pointer mr-4"
                    >
                      Kembali
                    </button>
                  </a>
                
                  <!-- Tombol Simpan -->
                  <a href="manajemen_user.html">
                    <button
                      type="button"
                      class="inline-block px-8 py-2 w-36 h-10 font-bold text-center text-white align-middle transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85"
                    >
                      Simpan
                    </button>
                  </a>
                </div>
                


              </div>
            </div>
          </div>
        </div>

@endsection