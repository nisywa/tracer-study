@extends('admin.layouts.app')

@section('content')
    <!-- table 1 -->
    <form id="surveyForm" action="{{ route('admin.survey.create_question') }}" method="POST">
        @csrf
        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div
                    class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                    <div
                        class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex items-center justify-between">
                        <h6 class="dark:text-white">Daftar Pertanyaan</h6>
                        <button type="button"
                            class="add-row inline-block px-8 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                            <i class="fas fa-plus mr-2"></i> Tambah Pertanyaan
                        </button>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <input type="hidden" name="survey_id" value="{{ $survey->id }}">
                        <div class="p-0 overflow-x-auto">
                            <table id="dynamicTable"
                                class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                                <thead class="align-bottom">
                                    <tr>
                                        <th
                                            class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            Pertanyaan</th>
                                        <th
                                            class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            Deskripsi</th>
                                        <th
                                            class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            Blok</th>
                                        <th
                                            class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            Tipe Jawaban</th>
                                        <th
                                            class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            Keterangan Jawaban</th>
                                        <th
                                            class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($template_questions as $question)
                                        <tr>
                                            <td
                                                class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <div class="flex flex-col px-2 py-1">
                                                    <input type="text" name="pertanyaan[]"
                                                        value="{{ $question->pertanyaan }}"
                                                        class="text-sm leading-normal dark:text-white bg-transparent outline-none question-input"
                                                        placeholder="Tulis Pertanyaan disini">
                                                </div>
                                            </td>
                                            <td
                                                class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <div class="flex flex-col px-2 py-1">
                                                    <input type="text" name="deskripsi[]"
                                                        value="{{ $question->deskripsi_pertanyaan }}"
                                                        class="text-sm leading-normal dark:text-white bg-transparent outline-none description-input"
                                                        placeholder="Tulis Deskripsi disini">
                                                </div>
                                            </td>
                                            <td
                                                class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <div class="flex flex-col px-2 py-1">
                                                    <input type="text" name="blok[]" value="{{ $question->blok }}"
                                                        class="text-sm leading-normal dark:text-white bg-transparent outline-none question-input"
                                                        placeholder="Blok Pertanyaan">
                                                </div>
                                            </td>
                                            <td
                                                class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <select name="tipe[]"
                                                    class="text-xs font-semibold leading-tight border border-gray-400 rounded px-2 py-1 input-type"
                                                    onchange="changeInputType(this)">
                                                    <option value="">Pilih Tipe</option>
                                                    <option value="text"
                                                        {{ $question->tipe == 'text' ? 'selected' : '' }}>Short Answer
                                                    </option>
                                                    <option value="textarea"
                                                        {{ $question->tipe == 'textarea' ? 'selected' : '' }}>Paragraph
                                                    </option>
                                                    <option value="checkbox"
                                                        {{ $question->tipe == 'checkbox' ? 'selected' : '' }}>Checkboxes
                                                    </option>
                                                    <option value="radio"
                                                        {{ $question->tipe == 'radio' ? 'selected' : '' }}>Radio</option>
                                                    <option value="select"
                                                        {{ $question->tipe == 'select' ? 'selected' : '' }}>Dropdown
                                                    </option>
                                                    <option value="file"
                                                        {{ $question->tipe == 'file' ? 'selected' : '' }}>File</option>
                                                    <option value="date"
                                                        {{ $question->tipe == 'date' ? 'selected' : '' }}>Datepicker
                                                    </option>
                                                </select>
                                            </td>
                                            <td
                                                class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent personal-column">
                                                @if (in_array($question->tipe, ['radio', 'checkbox', 'select']))
                                                    <div id="optionContainer" class="option-container">
                                                        @foreach ($question->template_jawaban as $option)
                                                            <div class="option-item flex items-center space-x-2 mb-2">
                                                                <input type="text" name="options[{{ $question->id }}][]"
                                                                    value="{{ $option->pilihan_jawaban }}"
                                                                    class="text-xs border border-gray-400 rounded px-2 py-1 flex-grow" />
                                                                <div class="flex space-x-1">
                                                                    <button type="button"
                                                                        class="move-option-up text-xs px-2 py-1 border rounded">
                                                                        <i class="fas fa-arrow-up"></i>
                                                                    </button>
                                                                    <button type="button"
                                                                        class="move-option-down text-xs px-2 py-1 border rounded">
                                                                        <i class="fas fa-arrow-down"></i>
                                                                    </button>
                                                                    <button type="button"
                                                                        class="delete-option text-xs px-2 py-1 border rounded text-red-500">
                                                                        <i class="fas fa-times"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-xs font-semibold leading-tight text-slate-400">{{ ucfirst($question->tipe) }}</span>

                                                @endif
                                            </td>
                                            <td
                                                class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <div class="icon-container">
                                                    <a class="icon-link duplicate-row" data-tooltip="Duplicate">
                                                        <i class="fas fa-copy"></i>
                                                    </a>
                                                    <a class="icon-link delete-row" data-tooltip="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                    <a class="icon-link move-up" data-tooltip="Move Up">
                                                        <i class="fas fa-arrow-up"></i>
                                                    </a>
                                                    <a class="icon-link move-down" data-tooltip="Move Down">
                                                        <i class="fas fa-arrow-down"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <div class="flex justify-end p-4">
            <button type="submit"
                class="px-8 py-2 font-bold text-white bg-green-500 rounded-lg shadow-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">
                <i class="fas fa-save mr-2"></i>Simpan
            </button>
        </div>
    </form>
    <table style="display: none;">
        <tbody>
            <tr id="templateRow">
                <td
                    class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                    <div class="flex flex-col px-2 py-1">
                        <input type="text" name="pertanyaan[]"
                            class="text-sm leading-normal dark:text-white bg-transparent outline-none question-input"
                            placeholder="Tulis Pertanyaan disini">
                    </div>
                </td>
                <td
                    class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                    <div class="flex flex-col px-2 py-1">
                        <input type="text" name="deskripsi[]"
                            class="text-sm leading-normal dark:text-white bg-transparent outline-none description-input"
                            placeholder="Tulis Deskripsi disini">
                    </div>
                </td>
                <td
                    class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                    <div class="flex flex-col px-2 py-1">
                        <input type="text" name="blok[]"
                            class="text-sm leading-normal dark:text-white bg-transparent outline-none question-input"
                            placeholder="Blok Pertanyaan">
                    </div>
                </td>
                <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                    <select name="tipe[]"
                        class="text-xs font-semibold leading-tight border border-gray-400 rounded px-2 py-1 input-type"
                        onchange="changeInputType(this)">
                        <option value="">Pilih Tipe</option>
                        <option value="text">Short Answer</option>
                        <option value="textarea">Paragraph</option>
                        <option value="checkbox">Checkboxes</option>
                        <option value="radio">Radio</option>
                        <option value="select">Dropdown</option>
                        <option value="file">File</option>
                        <option value="date">Datepicker</option>
                    </select>
                </td>
                <td
                    class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent personal-column">
                    <span class="text-xs font-semibold leading-tight text-slate-400">Tipe Jawaban</span>
                </td>
                <td
                    class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                    <div class="icon-container">
                        <a class="icon-link duplicate-row" data-tooltip="Duplicate">
                            <i class="fas fa-copy"></i>
                        </a>
                        <a class="icon-link delete-row" data-tooltip="Delete">
                            <i class="fas fa-trash"></i>
                        </a>
                        <a class="icon-link move-up" data-tooltip="Move Up">
                            <i class="fas fa-arrow-up"></i>
                        </a>
                        <a class="icon-link move-down" data-tooltip="Move Down">
                            <i class="fas fa-arrow-down"></i>
                        </a>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    <script src="{{ asset('assets/js/tipejawaban.js') }}"></script>
@endsection
