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
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">aksi</th>

                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <div class="flex  flec-col px-2 py-1">
                              <h6 class="mb-0 text-sm leading-normal dark:text-white">Survei Tracer Study 2022 [ALUMNI]</h6>
                          </div>
                        </td>

                        <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <span class="bg-gradient-to-tl from-emerald-500 to-teal-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Aktif</span>
                        </td>
                        <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">23/04/18</span>
                        </td>
                        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <div class="icon-container">
                            <!-- Add document -->
                            <a href="list_pertanyaan.html" class="icon-link" data-tooltip="Tambah Pertanyaan">
                                <i class="fas fa-file-alt"></i>
                            </a>
                            <!-- Add user -->
                            <a href="tambah_user.html" class="icon-link" data-tooltip="Tambah User">
                                <i class="fas fa-user-plus"></i>
                            </a>
                            <!-- Edit -->
                            <a href="tambah_survei.html" class="icon-link" data-tooltip="Edit">
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
                              <h6 class="mb-0 text-sm leading-normal dark:text-white">Survei Tracer Study 2023 [ALUMNI]</h6>
                          </div>
                        </td>

                        <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <span class="bg-gradient-to-tl from-emerald-500 to-teal-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Aktif</span>
                        </td>
                        <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">23/04/18</span>
                        </td>
                        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <div class="icon-container">
                            <!-- Add document -->
                            <a href="javascript:;" class="icon-link" data-tooltip="Tambah Pertanyaan">
                                <i class="fas fa-file-alt"></i>
                            </a>
                            <!-- Add user -->
                            <a href="javascript:;" class="icon-link" data-tooltip="Tambah User">
                                <i class="fas fa-user-plus"></i>
                            </a>
                            <!-- Edit -->
                            <a href="javascript:;" class="icon-link" data-tooltip="Edit">
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
                                <h6 class="mb-0 text-sm leading-normal dark:text-white">Survei Tracer Study 2022 [ATASAN]</h6>
                            </div>
                          </td>

                        <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <span class="bg-gradient-to-tl from-slate-600 to-slate-300 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Selesai</span>
                        </td>
                        <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">11/01/19 - 11/01/19 </span>
                        </td>
                        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <div class="icon-container">
                                <!-- Add document -->
                                <a href="javascript:;" class="icon-link" data-tooltip="Tambah Pertanyaan">
                                    <i class="fas fa-file-alt"></i>
                                </a>
                                <!-- Add user -->
                                <a href="javascript:;" class="icon-link" data-tooltip="Tambah User">
                                    <i class="fas fa-user-plus"></i>
                                </a>
                                <!-- Edit -->
                                <a href="javascript:;" class="icon-link" data-tooltip="Edit">
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
                              <h6 class="mb-0 text-sm leading-normal dark:text-white">Survei Tracer Study 2022 [ALUMNI]</h6>
                          </div>
                        </td>

                        <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <span class="bg-gradient-to-tl from-emerald-500 to-teal-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Aktif</span>
                        </td>
                        <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">23/04/18</span>
                        </td>
                        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <div class="icon-container">
                            <!-- Add document -->
                            <a href="javascript:;" class="icon-link" data-tooltip="Tambah Pertanyaan">
                                <i class="fas fa-file-alt"></i>
                            </a>
                            <!-- Add user -->
                            <a href="javascript:;" class="icon-link" data-tooltip="Tambah User">
                                <i class="fas fa-user-plus"></i>
                            </a>
                            <!-- Edit -->
                            <a href="javascript:;" class="icon-link" data-tooltip="Edit">
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
                                <h6 class="mb-0 text-sm leading-normal dark:text-white">Survei Tracer Study 2022 [ATASAN]</h6>
                            </div>
                          </td>

                        <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <span class="bg-gradient-to-tl from-slate-600 to-slate-300 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Selesai</span>
                        </td>
                        <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">11/01/19 - 11/01/19 </span>
                        </td>
                        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <div class="icon-container">
                                <!-- Add document -->
                                <a href="javascript:;" class="icon-link" data-tooltip="Tambah Pertanyaan">
                                    <i class="fas fa-file-alt"></i>
                                </a>
                                <!-- Add user -->
                                <a href="javascript:;" class="icon-link" data-tooltip="Tambah User">
                                    <i class="fas fa-user-plus"></i>
                                </a>
                                <!-- Edit -->
                                <a href="javascript:;" class="icon-link" data-tooltip="Edit">
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
                                <h6 class="mb-0 text-sm leading-normal dark:text-white">Survei Tracer Study 2022 [ATASAN]</h6>
                            </div>
                          </td>

                        <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <span class="bg-gradient-to-tl from-slate-600 to-slate-300 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Selesai</span>
                        </td>
                        <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                          <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">11/01/19 - 11/01/19 </span>
                        </td>
                        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                            <div class="icon-container">
                                <!-- Add document -->
                                <a href="javascript:;" class="icon-link" data-tooltip="Tambah Pertanyaan">
                                    <i class="fas fa-file-alt"></i>
                                </a>
                                <!-- Add user -->
                                <a href="javascript:;" class="icon-link" data-tooltip="Tambah User">
                                    <i class="fas fa-user-plus"></i>
                                </a>
                                <!-- Edit -->
                                <a href="javascript:;" class="icon-link" data-tooltip="Edit">
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
              </div>
            </div>
          </div>
        </div>

@endsection
