@extends('admin.layouts.app')
@section('title', 'Edit Template Email')

@section('content')
<div class="flex flex-wrap -mx-3">
    <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <h6 class="dark:text-white">Template Email Survei</h6>
                <p class="text-sm leading-normal text-slate-400">Kelola template email untuk undangan, pengingat, dan ucapan terima kasih survei</p>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mx-6 mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mx-6 mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="flex-auto p-6">
                <!-- Tab Navigation -->
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <button class="tab-button active border-blue-500 text-blue-600 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm" data-tab="invitation">
                            Template Undangan
                        </button>
                        <button class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm" data-tab="reminder">
                            Template Pengingat
                        </button>
                        <button class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm" data-tab="appreciation">
                            Template Terima Kasih
                        </button>
                    </nav>
                </div>

                <!-- Template Forms -->
                <!-- Invitation Template -->
                <div id="invitation-tab" class="tab-content">
                    <form action="{{ route('admin.template_email.update') }}" method="POST" class="mt-6">
                        @csrf
                        <input type="hidden" name="type" value="survey_invitation">

                        <div class="mb-4">
                            <label for="invitation_subject" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Subject</label>
                            <input type="text" name="subject" id="invitation_subject" value="{{ $templates['invitation']->subject ?? '' }}"
                                class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                        </div>

                        <div class="mb-4">
                            <label for="invitation_body" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Content</label>
                            <textarea name="body" id="invitation_body" rows="10"
                                class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">{{ $templates['invitation']->body ?? '' }}</textarea>
                        </div>

                        <div class="flex justify-between items-center">
                            <button type="button" class="preview-btn px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200" data-type="survey_invitation">
                                Preview
                            </button>
                            <button type="submit" class="inline-block px-8 py-2 font-bold text-center text-white align-middle transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Reminder Template -->
                <div id="reminder-tab" class="tab-content hidden">
                    <form action="{{ route('admin.template_email.update') }}" method="POST" class="mt-6">
                        @csrf
                        <input type="hidden" name="type" value="survey_reminder">

                        <div class="mb-4">
                            <label for="reminder_subject" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Subject</label>
                            <input type="text" name="subject" id="reminder_subject" value="{{ $templates['reminder']->subject ?? '' }}"
                                class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                        </div>

                        <div class="mb-4">
                            <label for="reminder_body" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Content</label>
                            <textarea name="body" id="reminder_body" rows="10"
                                class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">{{ $templates['reminder']->body ?? '' }}</textarea>
                        </div>

                        <div class="flex justify-between items-center">
                            <button type="button" class="preview-btn px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200" data-type="survey_reminder">
                                Preview
                            </button>
                            <button type="submit" class="inline-block px-8 py-2 font-bold text-center text-white align-middle transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Appreciation Template -->
                <div id="appreciation-tab" class="tab-content hidden">
                    <form action="{{ route('admin.template_email.update') }}" method="POST" class="mt-6">
                        @csrf
                        <input type="hidden" name="type" value="survey_appreciation">

                        <div class="mb-4">
                            <label for="appreciation_subject" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Subject</label>
                            <input type="text" name="subject" id="appreciation_subject" value="{{ $templates['appreciation']->subject ?? '' }}"
                                class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                        </div>

                        <div class="mb-4">
                            <label for="appreciation_body" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Content</label>
                            <textarea name="body" id="appreciation_body" rows="10"
                                class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">{{ $templates['appreciation']->body ?? '' }}</textarea>
                        </div>

                        <div class="flex justify-between items-center">
                            <button type="button" class="preview-btn px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200" data-type="survey_appreciation">
                                Preview
                            </button>
                            <button type="submit" class="inline-block px-8 py-2 font-bold text-center text-white align-middle transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Placeholder Information -->
                <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                    <h6 class="font-bold text-blue-800 dark:text-blue-200 mb-2">Placeholder yang Tersedia:</h6>
                    <div class="text-sm text-blue-700 dark:text-blue-300 grid grid-cols-2 gap-2">
                        <div><code>@{{name}}</code> - Nama pengguna</div>
                        <div><code>@{{email}}</code> - Email pengguna</div>
                        <div><code>@{{password}}</code> - Password pengguna</div>
                        <div><code>@{{survey_name}}</code> - Nama survei</div>
                        <div><code>@{{start_date}}</code> - Tanggal mulai survei</div>
                        <div><code>@{{end_date}}</code> - Tanggal selesai survei</div>
                        <div><code>@{{login_url}}</code> - Link login</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div id="previewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" style="z-index: 1000;">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-slate-800">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Preview Email</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closePreview()">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Subject:</label>
                <div id="previewSubject" class="p-3 bg-gray-100 dark:bg-gray-700 rounded border text-sm"></div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Body:</label>
                <div id="previewBody" class="p-3 bg-gray-100 dark:bg-gray-700 rounded border text-sm whitespace-pre-wrap"></div>
            </div>
        </div>
    </div>
</div>

<script>
// Tab functionality
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabName = this.getAttribute('data-tab');

            // Remove active class from all buttons
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'border-blue-500', 'text-blue-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });

            // Add active class to clicked button
            this.classList.add('active', 'border-blue-500', 'text-blue-600');
            this.classList.remove('border-transparent', 'text-gray-500');

            // Hide all tab contents
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });

            // Show selected tab content
            document.getElementById(tabName + '-tab').classList.remove('hidden');
        });
    });

    // Preview functionality
    const previewButtons = document.querySelectorAll('.preview-btn');
    previewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const type = this.getAttribute('data-type');
            fetch(`{{ route('admin.template_email.preview') }}?type=${type}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }
                    document.getElementById('previewSubject').textContent = data.subject;
                    document.getElementById('previewBody').textContent = data.body;
                    document.getElementById('previewModal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengambil preview');
                });
        });
    });
});

function closePreview() {
    document.getElementById('previewModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('previewModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePreview();
    }
});
</script>
@endsection


            </div>
          </div>
        </div>
</form>
@endsection