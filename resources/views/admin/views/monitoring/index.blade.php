@extends('admin.layouts.app')
@section('title', 'Monitoring Survei')

@section('content')
    <!-- table 1 -->

    <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
            <div
                class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                <div
                    class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex items-center justify-between">
                    <h6 class="dark:text-white">Daftar Survei</h6>
                    <div class="relative flex items-center w-auto">
                        <div class="relative flex items-stretch">
                            <span
                                class="text-sm ease leading-5.6 absolute z-50 flex h-full items-center whitespace-nowrap rounded-lg rounded-tr-none rounded-br-none border border-r-0 border-transparent bg-transparent py-2 px-2.5 text-center font-normal text-slate-500 transition-all">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text"
                                class="pl-9 text-sm focus:shadow-primary-outline ease w-1/4 leading-5.6 relative block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow"
                                placeholder="Type here..." />
                        </div>
                    </div>
                </div>
                <div class="flex-auto px-0 pt-0 pb-2">

                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-0 overflow-x-auto">
                            <table
                                class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                                <thead class="align-bottom">
                                    <tr>
                                        <th
                                            class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            Nama Survei</th>

                                        <th
                                            class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            Status</th>
                                        <th
                                            class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            Tanggal Aktif</th>
                                        <th
                                            class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            Tipe Survei</th>
                                        <th
                                            class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            Deskripsi</th>
                                        <th
                                            class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            aksi</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($survey as $srvy)
                                        <tr>
                                            <td
                                                class="p-2 align-middle bg-transparent border-b dark:border-white/40 shadow-transparent break-words">
                                                <div class="flex  flec-col px-2 py-1">
                                                    <h6 class="mb-0 text-sm leading-normal dark:text-white">
                                                        {{ $srvy->nama }}
                                                    </h6>
                                                </div>
                                            </td>

                                            <td
                                                class="p-2 text-sm leading-normal text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <span
                                                    class="bg-gradient-to-tl {{ $srvy->status == 'Aktif' ? 'from-emerald-500 to-teal-400' : 'from-slate-600 to-slate-300' }}  px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">{{ $srvy->status }}</span>
                                            </td>
                                            <td
                                                class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <span
                                                    class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">{{ $srvy->tanggal_mulai . ' ' . '--' . ' ' . $srvy->tanggal_selesai }}</span>
                                            </td>
                                            <td
                                                class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 shadow-transparent break-words">

                                                <span
                                                    class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">{{ $srvy->type_survei == 'alumni' ? 'lulusan' : 'pengguna lulusan' }}</span>
                                            </td>
                                            <td
                                                class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 shadow-transparent break-words">
                                                <span
                                                    class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400 break-words">{{ $srvy->deskripsi }}</span>
                                            </td>

                                            <td
                                                class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <div class="icon-container">
                                                    
                                                    <a href="{{ route('admin.monitoring.export', $srvy->id) }}" class="icon-link" data-tooltip="Export Hasil Survei">
                                                        <i class="fas fa-file-excel"></i>
                                                    </a>
                                                    <!-- Visualisasi -->
                                                    <a href="{{ route('admin.monitoring.grafik', $srvy->id) }}"
                                                        class="icon-link" data-tooltip="Visualisasi Data">
                                                        <i class="fas fa-chart-bar"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach


                                </tbody>
                            </table>
                            <div class="p-4">
                                {{ $survey->links() }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- js pop up modal import -->
        <script>
            const openModal = document.getElementById('openModal');
            const closeModal = document.getElementById('closeModal');
            const cancelUpload = document.getElementById('cancelUpload');
            const uploadModal = document.getElementById('uploadModal');

            openModal.addEventListener('click', () => {
                uploadModal.classList.remove('hidden');
            });

            closeModal.addEventListener('click', () => {
                uploadModal.classList.add('hidden');
            });

            cancelUpload.addEventListener('click', () => {
                uploadModal.classList.add('hidden');
            });

            document.getElementById('uploadForm').addEventListener('submit', (e) => {
                e.preventDefault();
                alert('File uploaded successfully!');
                uploadModal.classList.add('hidden');
            });
        </script>
        <script>
    document.addEventListener('input', function (e) {
        if (e.target.classList.contains('auto-resize')) {
            e.target.style.height = 'auto';
            e.target.style.height = (e.target.scrollHeight) + 'px';
        }
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.querySelector('input[type="text"]');
        const tableRows = document.querySelectorAll("tbody tr");

        searchInput.addEventListener("input", function () {
            const searchTerm = this.value.toLowerCase();

            tableRows.forEach(row => {
                const namaSurvei = row.children[0].innerText.toLowerCase();
                const tipeSurvei = row.children[3].innerText.toLowerCase();

                if (namaSurvei.includes(searchTerm) || tipeSurvei.includes(searchTerm)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });
    });
</script>



@endsection
