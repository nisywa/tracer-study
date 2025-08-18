@extends('admin.layouts.app')
@section('title', 'Details Survey')

@section('content')

<!-- table 1 info survei -->
<div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
              <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex items-center justify-between">
                  <h6 class="dark:text-white">Informasi Survei</h6>
                  @if(!auth()->user()->hasRole('supervisor'))
                    <!-- <button type="button"
                        id="sendEmailBtn"
                        data-survey-id="{{ $survey->id }}"
                        class="inline-block px-8 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                        <i class="fas fa-envelope mr-2"></i> Kirim Email ke Semua
                    </button> -->
                    <div class="flex flex-wrap gap-2 my-4">
                                <button type="button"
                                id="sendInvitationBtn"
                                data-survey-id="{{ $survey->id }}"
                                class="inline-block px-8 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                            <i class="fas fa-paper-plane mr-2"></i>
                            <span class="hidden sm:inline">Kirim Email</span>
                            <span class="sm:hidden">Email</span>
                        </button>

                        <button type="button"
                                id="sendReminderBtn"
                                data-survey-id="{{ $survey->id }}"
                                class="inline-block px-8 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-orange-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                            <i class="fas fa-bell mr-2"></i>
                            <span class="hidden sm:inline">Reminder Pengerjaan</span>
                            <span class="sm:hidden">Reminder</span>
                        </button>

                        <button type="button"
                                id="sendThankYouBtn"
                                data-survey-id="{{ $survey->id }}"
                                class="inline-block px-8 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-green-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                            <i class="fas fa-handshake mr-2"></i>
                            <span class="hidden sm:inline">Ucapan Terima Kasih</span>
                            <span class="sm:hidden">Thanks</span>
                        </button>

                        <a href="{{route('admin.template_email.index')}}">
                            <button type="button" class="inline-block px-6 py-2 font-bold leading-normal text-center text-gray-700 align-middle transition-all ease-in bg-gray-200 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                                <i class="fas fa-cog mr-2"></i>
                                <span class="hidden sm:inline">Kelola Template Email</span>
                                <span class="sm:hidden">Template</span>
                            </button>
                        </a>
                    </div>
                  @endif
                </div>
                @if(session('error'))
                  <div class="bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 shadow-md alert alert-danger mb-6" role="alert">
                      <div class="flex">
                          <div class="py-1">
                              <svg class="fill-current h-6 w-6 text-red-500 mr-4" ...></svg>
                          </div>
                          <div>
                              <p class="font-bold">{{ session('error') }}</p>
                          </div>
                      </div>
                  </div>
              @endif

                <div class="flex-auto px-0 pt-0 pb-2">
                  <div class="p-0 overflow-x-auto">
                    <div class="flex-auto p-6">
                        <div class="flex flex-wrap -mx-3">

                          <div class="w-full max-w-full px-3 shrink-0 md:w-3/12 md:flex-0">
                            <div class="mb-4">
                              <label for="username" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Nama Survei</label>
                              <h6 class="text-slate-700 dark:text-white/80">{{ $survey->nama }}</h6>
                            </div>
                          </div>
                          <div class="w-full max-w-full px-3 shrink-0 md:w-3/12 md:flex-0">
                            <div class="mb-4">
                              <label for="email" class="block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Status</label>
                              <!-- <div class="bg-gradient-to-tl from-emerald-500 to-teal-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Aktif</div> -->
                              <div class="bg-gradient-to-tl {{$survey->status =='Aktif' ? 'from-emerald-500 to-teal-400' : 'from-slate-600 to-slate-300'}}  px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">{{ $survey->status }}</div>
                            </div>
                          </div>
                          <div class="w-full max-w-full px-3 shrink-0 md:w-3/12 md:flex-0">
                            <div class="mb-4">
                              <label for="email" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Tanggal Aktif</label>
                              <h6 class="text-slate-700 dark:text-white/80">{{ $survey->tanggal_mulai." -- ".$survey->tanggal_selesai }}</h6>
                            </div>
                          </div>
                          <div class="w-full max-w-full px-3 shrink-0 md:w-3/12 md:flex-0">
                            <div class="mb-4">
                              <label for="type_survei" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Tipe Survei</label>
                              <h6 class="text-slate-700 dark:text-white/80">{{ $survey->type_survei }}</h6>
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
                @if(!auth()->user()->hasRole('supervisor'))
                  <div class="flex gap-2">
                    <a href="{{ route('admin.survey.edit', $survey->id) }}" class="inline-block px-6 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85 hover:bg-blue-600">
                      <i class="fas fa-magic mr-2"></i>
                      <span class="hidden sm:inline">Edit</span>
                      <span class="sm:hidden">Builder</span>
                    </a>
                    <!-- <a href="{{ route('admin.survey.create') }}" class="inline-block px-6 py-2 font-bold leading-normal text-center text-gray-700 align-middle transition-all ease-in bg-gray-200 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85 hover:bg-gray-300">
                      <i class="fas fa-list mr-2"></i>
                      <span class="hidden sm:inline">Classic Editor</span>
                      <span class="sm:hidden">Classic</span>
                    </a> -->
                  </div>
                @endif
              </div>

                            <div class="flex-auto px-0 pt-0 pb-2">
                                <div class="p-0 overflow-x-auto">
                                    @foreach($surveyBlocks as $block)
                                        <div class="mb-6 border rounded-lg shadow-sm bg-gray-50">
                                            <div class="px-6 py-3 bg-blue-100 rounded-t-lg flex items-center justify-between">
                                                <h5 class="font-bold text-blue-700">Block: {{ $block->nama }}</h5>
                                                <span class="text-xs text-gray-500">{{ $block->deskripsi }}</span>
                                            </div>
                                            <table class="w-full text-sm">
                                                <thead>
                                                    <tr class="bg-gray-200">
                                                        <th class="px-4 py-2 text-left">Pertanyaan</th>
                                                        <th class="px-4 py-2 text-left">Tipe</th>
                                                        <th class="px-4 py-2 text-left">Pilihan Jawaban</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($block->questions as $question)
                                                        <tr class="border-b">
                                                            <td class="px-4 py-2">
                                                                <div class="font-semibold">{{ $question->pertanyaan }}</div>
                                                                <div class="text-xs text-gray-500">{{ $question->deskripsi_pertanyaan }}</div>
                                                            </td>
                                                            <td class="px-4 py-2">{{ $question->tipe }}</td>
                                                            <td class="px-4 py-2">
                                                                @if(in_array($question->tipe, ['radio','checkbox','select']) && $question->templateJawaban->count())
                                                                    <ul class="list-disc pl-4">
                                                                        @foreach($question->templateJawaban as $jawaban)
                                                                            <li>{{ $jawaban->pilihan_jawaban }} <span class="text-xs text-gray-400">{{ $jawaban->navigation_target ? '→ '.$jawaban->navigation_target : '' }}</span></li>
                                                                        @endforeach
                                                                    </ul>
                                                                @else
                                                                    <span class="italic text-gray-400">-</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endforeach
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
                  <div class="flex items-center gap-4">
                  @if(!auth()->user()->hasRole('supervisor'))
                    <div class="relative w-64">
                      <label for="graduationYearSelect" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Tambah Alumni by Tahun Lulus:</label>
                      <select id="graduationYearSelect" class="w-full form-select focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                        <option value="">Pilih Tahun Lulus...</option>
                      </select>
                    </div>
                    <div class="relative w-64">
                      <label for="userSelect" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Tambah Alumni Individual:</label>
                      <select id="userSelect" class="w-full form-select focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                            <option></option>
                        </select>
                    </div>
                  @endif
                  </div>
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
                            <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Aksi</th>
                        </tr>
                      </thead>
                      <tbody>

                        @foreach($survey_user as $responden)
                                <tr>
                                    <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <div class="flex flec-col px-2 py-1">
                                            <h6 class="mb-0 text-sm leading-normal dark:text-white">{{ $responden->nama }}</h6>
                                        </div>
                                    </td>
                                    <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">{{ $responden->nip }}</span>
                                    </td>
                                    <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">{{ $responden->email }}</span>
                                    </td>
                                    <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">{{ $responden->no_hp }}</span>
                                    </td>
                                    <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">{{ $responden->kepala_bps }}</span>
                                    </td>
                                    <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                    <div class="icon-container">
                                            <!-- Delete -->
                                            <a href="javascript:;" class="icon-link" data-tooltip="Delete" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $responden->survey_user_id }}').submit();">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            <form id="delete-form-{{ $responden->survey_user_id }}" action="{{ route('admin.survey_user.destroy', $responden->survey_user_id) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
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

@push('scripts')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    $('#userSelect').select2({
        theme: 'bootstrap-5',
        placeholder: 'Search and select users...',
        allowClear: true,
        dropdownParent: $('#userSelect').parent(),
        templateResult: formatUser,
        templateSelection: formatUser,
        width: '100%',
        ajax: {
            url: '{{ route("admin.search_user") }}',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    search: params.term,
                    type: '{{ $survey->type_survei }}',
                    survey_id: '{{ $survey->id }}'
                };
            },
            processResults: function(data) {
                return {
                    results: data.map(function(item) {
                        return {
                            id: item.id,
                            text: item.name + ' (' + item.email + ')',
                            user: item
                        };
                    })
                };
            },
            cache: true
        },
        minimumInputLength: 2
    });

    function formatUser(user) {
        if (!user.id) return user.text;
        return $(`
            <div class="flex items-center py-1">
                <div class="flex-1">
                    <div class="text-sm font-medium text-gray-900 dark:text-white">${user.text}</div>
                </div>
            </div>
        `);
    }

    $('#userSelect').on('select2:select', function(e) {
        const selectedUser = e.params.data.user;

        $.ajax({
            url: '{{ route("admin.survey.add_user") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                user_id: selectedUser.id,
                survey_id: '{{ $survey->id }}'
            },
            success: function(response) {
                const alertDiv = $(`
                    <div class="fixed top-4 right-4 bg-green-100 border-t-4 border-green-500 rounded-b text-green-900 px-4 py-3 shadow-md" role="alert">
                        <div class="flex">
                            <div class="py-1">
                                <svg class="fill-current h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M10 0C4.48 0 0 4.48 0 10s4.48 10 10 10 10-4.48 10-10S15.52 0 10 0zm5 7.5l-6.25 6.25-3.75-3.75 1.41-1.41 2.34 2.34 4.84-4.84L15 7.5z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold">${response.message || 'User added successfully!'}</p>
                            </div>
                        </div>
                    </div>
                `);
                $('body').append(alertDiv);
                setTimeout(() => alertDiv.remove(), 3000);

                $('#userSelect').val(null).trigger('change');
                location.reload();
            },
            error: function(xhr) {
                console.error('Add user error:', xhr);
                const message = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan saat menambahkan user.';
                const alertDiv = $(`
                    <div class="fixed top-4 right-4 bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 shadow-md" role="alert">
                        <div class="flex">
                            <div class="py-1">
                                <svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold">${message}</p>
                            </div>
                        </div>
                    </div>
                `);
                $('body').append(alertDiv);
                setTimeout(() => alertDiv.remove(), 3000);
            }
        });
    });

    // Load graduation years on page load
    $.ajax({
        url: '{{ route("admin.get_graduation_years") }}',
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const graduationYearSelect = $('#graduationYearSelect');
                response.years.forEach(function(year) {
                    graduationYearSelect.append(`<option value="${year}">${year}</option>`);
                });
            }
        },
        error: function(xhr) {
            console.error('Error loading graduation years:', xhr.responseJSON);
        }
    });

    // Handle graduation year selection
    $('#graduationYearSelect').on('change', function() {
        const selectedYear = $(this).val();

        if (selectedYear) {
            if (confirm(`Apakah Anda yakin ingin menambahkan semua alumni yang lulus pada tahun ${selectedYear} ke dalam survey ini?`)) {
                $.ajax({
                    url: '{{ route("admin.survey.add_alumni_by_graduation_year") }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        tahun_lulus: selectedYear,
                        survey_id: '{{ $survey->id }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            const alertDiv = $(`
                                <div class="fixed top-4 right-4 bg-green-100 border-t-4 border-green-500 rounded-b text-green-900 px-4 py-3 shadow-md" role="alert">
                                    <div class="flex">
                                        <div class="py-1">
                                            <svg class="fill-current h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path d="M10 0C4.48 0 0 4.48 0 10s4.48 10 10 10 10-4.48 10-10S15.52 0 10 0zm5 7.5l-6.25 6.25-3.75-3.75 1.41-1.41 2.34 2.34 4.84-4.84L15 7.5z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-bold">${response.message}</p>
                                        </div>
                                    </div>
                                </div>
                            `);
                            $('body').append(alertDiv);
                            setTimeout(() => alertDiv.remove(), 5000);

                            // Reset the select
                            $('#graduationYearSelect').val('');

                            // Reload the page to show updated user list
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            const alertDiv = $(`
                                <div class="fixed top-4 right-4 bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 shadow-md" role="alert">
                                    <div class="flex">
                                        <div class="py-1">
                                            <svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-bold">${response.message}</p>
                                        </div>
                                    </div>
                                </div>
                            `);
                            $('body').append(alertDiv);
                            setTimeout(() => alertDiv.remove(), 5000);
                        }
                    },
                    error: function(xhr) {
                        const message = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan saat menambahkan alumni.';
                        const alertDiv = $(`
                            <div class="fixed top-4 right-4 bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 shadow-md" role="alert">
                                <div class="flex">
                                    <div class="py-1">
                                        <svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-bold">Error: ${message}</p>
                                    </div>
                                </div>
                            </div>
                        `);
                        $('body').append(alertDiv);
                        setTimeout(() => alertDiv.remove(), 5000);
                    }
                });
            } else {
                // Reset selection if user cancels
                $('#graduationYearSelect').val('');
            }
        }
    });
});

$(document).ready(function() {
    // Function to show success message
    function showSuccessMessage(message) {
        const alertDiv = $(
            `<div class="fixed top-4 right-4 bg-green-100 border-t-4 border-green-500 rounded-b text-green-900 px-4 py-3 shadow-md z-50" role="alert">
                <div class="flex">
                    <div class="py-1">
                        <svg class="fill-current h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M10 0C4.48 0 0 4.48 0 10s4.48 10 10 10 10-4.48 10-10S15.52 0 10 0zm5 7.5l-6.25 6.25-3.75-3.75 1.41-1.41 2.34 2.34 4.84-4.84L15 7.5z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold">${message}</p>
                    </div>
                </div>
            </div>`
        );
        $('body').append(alertDiv);
        setTimeout(() => alertDiv.remove(), 5000);
    }

    // Function to show error message
    function showErrorMessage(message) {
        const alertDiv = $(
            `<div class="fixed top-4 right-4 bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 shadow-md z-50" role="alert">
                <div class="flex">
                    <div class="py-1">
                        <svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold">${message}</p>
                    </div>
                </div>
            </div>`
        );
        $('body').append(alertDiv);
        setTimeout(() => alertDiv.remove(), 5000);
    }

    // Function to show loading state
    function setButtonLoading(btn, loading) {
        if (loading) {
            btn.prop('disabled', true);
            btn.find('i').removeClass().addClass('fas fa-spinner fa-spin mr-2');
            btn.append(' <span class="loading-text">Mengirim...</span>');
        } else {
            btn.prop('disabled', false);
            btn.find('.loading-text').remove();
        }
    }

    // Send invitation emails to all users
    $('#sendInvitationBtn').on('click', function() {
        const btn = $(this);
        const surveyId = btn.data('survey-id');

        if (confirm('Apakah Anda yakin ingin mengirim email undangan ke semua pengguna survei ini?')) {
            setButtonLoading(btn, true);

            $.ajax({
                url: '{{ route("admin.send_email", ":id") }}'.replace(':id', surveyId),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    setButtonLoading(btn, false);
                    btn.find('i').removeClass().addClass('fas fa-paper-plane mr-2');
                    showSuccessMessage(response.message || 'Email undangan berhasil dikirim ke semua pengguna!');
                },
                error: function(xhr) {
                    setButtonLoading(btn, false);
                    btn.find('i').removeClass().addClass('fas fa-paper-plane mr-2');
                    const msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan saat mengirim email undangan.';
                    showErrorMessage(msg);
                }
            });
        }
    });

    // Send reminder emails to all incomplete users
    $('#sendReminderBtn').on('click', function() {
        const btn = $(this);
        const surveyId = btn.data('survey-id');

        if (confirm('Apakah Anda yakin ingin mengirim email reminder ke semua pengguna yang belum mengisi survei?')) {
            setButtonLoading(btn, true);

            $.ajax({
                url: '{{ route("admin.send_reminders", ":id") }}'.replace(':id', surveyId),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    setButtonLoading(btn, false);
                    btn.find('i').removeClass().addClass('fas fa-bell mr-2');
                    showSuccessMessage(response.message || 'Email reminder berhasil dikirim!');
                },
                error: function(xhr) {
                    setButtonLoading(btn, false);
                    btn.find('i').removeClass().addClass('fas fa-bell mr-2');
                    const msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan saat mengirim email reminder.';
                    showErrorMessage(msg);
                }
            });
        }
    });

    // Send thank you emails to all completed users
    $('#sendThankYouBtn').on('click', function() {
        const btn = $(this);
        const surveyId = btn.data('survey-id');

        if (confirm('Apakah Anda yakin ingin mengirim email terima kasih ke semua pengguna yang sudah mengisi survei?')) {
            setButtonLoading(btn, true);

            $.ajax({
                url: '{{ route("admin.send_bulk_thank_you", ":id") }}'.replace(':id', surveyId),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    setButtonLoading(btn, false);
                    btn.find('i').removeClass().addClass('fas fa-handshake mr-2');
                    showSuccessMessage(response.message || 'Email terima kasih berhasil dikirim!');
                },
                error: function(xhr) {
                    setButtonLoading(btn, false);
                    btn.find('i').removeClass().addClass('fas fa-handshake mr-2');
                    const msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan saat mengirim email terima kasih.';
                    showErrorMessage(msg);
                }
            });
        }
    });

    // Existing sendEmailBtn handler (keep for backward compatibility)
    $('#sendEmailBtn').on('click', function() {
        var surveyId = $(this).data('survey-id');
        $.ajax({
            url: '{{ route("admin.send_email", ":id") }}'.replace(':id', surveyId),
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                showSuccessMessage(response.message);
            },
            error: function(xhr) {
                const msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan.';
                showErrorMessage(msg);
            }
        });
    });

    // Auto-hide existing alert messages
    setTimeout(() => {
        document.querySelectorAll('.alert-danger').forEach(el => {
            el.classList.add('opacity-0', 'transition-opacity', 'duration-500');
            setTimeout(() => el.remove(), 500);
        });
    }, 3000);
});
</script>
@endpush