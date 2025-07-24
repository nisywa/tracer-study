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

    <!-- ==== WOW JS ==== -->
    <script src="{{ asset('assets/js/wow.min.js') }}"></script>
    <script>
        new WOW().init();
    </script>
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
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


    <!-- ====== form question Start (Full Page) ====== -->
    <section id="contact" class="relative min-h-screen pt-[80px] dark:bg-dark">
        <div class="absolute top-0 left-0 -z-[1] w-full dark:bg-dark h-full bg-white"></div>
        <div class="w-full h-full">
            <div class="flex justify-center items-start min-h-screen">
                <!-- Survey Header -->
                <div class="w-full max-w-4xl mx-auto px-4 py-8">
                    <div class="text-center mb-8">
                        <h1 class="mb-4 text-3xl font-bold text-dark dark:text-white sm:text-4xl md:text-[40px] md:leading-[1.2]">
                            {{ $survey->nama }}
                        </h1>
                        <p class="mb-3 text-base text-body-color dark:text-dark-6">
                            {{ $survey->tanggal_mulai . '--' . $survey->tanggal_selesai }}
                        </p>
                        <p class="mb-6 text-base text-body-color dark:text-dark-6">
                            Silakan isi formulir di bawah ini dengan informasi yang sesuai.
                        </p>
                    </div>
                                    <!-- Survey Form -->
                    <form action="{{ route('user.survey.save', ['id' => $survey->id]) }}" method="POST" class="w-full">
                        @csrf
                        <div class="bg-white dark:bg-dark-2 rounded-lg shadow-lg p-6 md:p-8 lg:p-10">
                            @foreach ($surveyPertanyaan as $blok => $pertanyaans)
                                <div class="mb-8">
                                    <h3 class="mb-6 text-xl font-semibold md:text-2xl text-dark dark:text-white border-b border-gray-200 dark:border-dark-3 pb-3">
                                        {{ $blok }}
                                    </h3>
                                </div>
                                @foreach ($pertanyaans as $pertanyaan)
                                    <div class="mb-8 p-4 bg-gray-50 dark:bg-dark-3 rounded-lg">
                                        <label for="{{ $pertanyaan->id }}"
                                            class="font-semibold block mb-3 text-base text-dark dark:text-white">{{ $pertanyaan->pertanyaan }}</label>
                                        <label for="{{ $pertanyaan->id }}"
                                            class="block mb-4 text-sm text-body-color dark:text-dark-6">{{ $pertanyaan->deskripsi_pertanyaan }}</label>
                                        @if ($pertanyaan->tipe == 'text')
                                            <input type="text" name="{{ $pertanyaan->id }}"
                                                class="w-full p-3 text-body-color dark:text-dark-6 bg-white dark:bg-dark-2 border border-gray-200 dark:border-dark-3 rounded-md focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" />
                                        @elseif($pertanyaan->tipe == 'textarea')
                                            <textarea name="{{ $pertanyaan->id }}" rows="4"
                                                class="w-full p-3 text-body-color dark:text-dark-6 bg-white dark:bg-dark-2 border border-gray-200 dark:border-dark-3 rounded-md focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"></textarea>
                                        @elseif($pertanyaan->tipe == 'radio')
                                            <div class="flex flex-col gap-y-3 mb-2 text-body-color dark:text-dark-6">
                                                @foreach ($pertanyaan->template_jawaban as $option)
                                                    <label class="flex items-center gap-x-3 p-2 hover:bg-white dark:hover:bg-dark-2 rounded cursor-pointer">
                                                        <input type="radio" name="{{ $pertanyaan->id }}"
                                                            value="{{ $option->pilihan_jawaban }}"
                                                            class="text-primary focus:ring-primary">
                                                        <span>{{ $option->pilihan_jawaban }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        @elseif($pertanyaan->tipe == 'file')
                                            <input type="file" name="{{ $pertanyaan->id }}"
                                                class="w-full p-3 text-body-color dark:text-dark-6 bg-white dark:bg-dark-2 border border-gray-200 dark:border-dark-3 rounded-md focus:border-primary focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-primary file:text-white" />
                                        @elseif($pertanyaan->tipe == 'select')
                                            <select name="{{ $pertanyaan->id }}"
                                                class="w-full p-3 text-body-color dark:text-dark-6 bg-white dark:bg-dark-2 border border-gray-200 dark:border-dark-3 rounded-md focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                                                <option value="" disabled selected hidden>Choose</option>
                                                @foreach ($pertanyaan->template_jawaban as $option)
                                                    <option value="{{ $option->pilihan_jawaban }}">
                                                        {{ $option->pilihan_jawaban }}</option>
                                                @endforeach
                                            </select>
                                        @elseif($pertanyaan->tipe == 'date')
                                            <input type="text" id="{{ $pertanyaan->id }}"
                                                name="{{ $pertanyaan->id }}" placeholder="Pilih tanggal"
                                                class="w-full p-3 text-body-color dark:text-dark-6 bg-white dark:bg-dark-2 border border-gray-200 dark:border-dark-3 rounded-md focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 datepicker" />
                                            <script>
                                                document.addEventListener("DOMContentLoaded", function() {
                                                    flatpickr(".datepicker", {
                                                        dateFormat: "Y-m-d",
                                                        allowInput: true,
                                                        altInput: true,
                                                        altFormat: "j F Y",
                                                        locale: "id",
                                                        disableMobile: "true"
                                                    });
                                                });
                                            </script>
                                        @elseif($pertanyaan->tipe == 'checkbox')
                                            <div class="space-y-2">
                                                @foreach ($pertanyaan->template_jawaban as $option)
                                                    <label for="option_{{ $pertanyaan->id }}_{{ $loop->index }}"
                                                        class="flex items-center text-sm text-body-color dark:text-dark-6 p-2 hover:bg-white dark:hover:bg-dark-2 rounded cursor-pointer">
                                                        <input type="checkbox" id="option_{{ $pertanyaan->id }}_{{ $loop->index }}"
                                                            name="{{ $pertanyaan->id }}[]"
                                                            value="{{ $option->pilihan_jawaban }}"
                                                            class="w-4 h-4 text-primary border border-gray-300 dark:border-dark-3 focus:ring-primary rounded" />
                                                        <span class="ml-3">{{ $option->pilihan_jawaban }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            @endforeach
                            
                            <!-- Form Actions -->
                            <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-gray-200 dark:border-dark-3">
                                <a href="/" class="inline-flex items-center justify-center px-8 py-3 text-base font-medium text-gray-600 dark:text-dark-6 bg-gray-100 dark:bg-dark-3 hover:bg-gray-200 dark:hover:bg-dark-2 transition duration-300 ease-in-out rounded-md border border-gray-200 dark:border-dark-3">
                                    Batal
                                </a>
                                <button type="submit" class="inline-flex items-center justify-center px-8 py-3 text-base font-medium text-white transition duration-300 ease-in-out rounded-md bg-primary hover:bg-blue-dark focus:ring-4 focus:ring-primary/20">
                                    Kirim
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- ====== Contact End ====== -->


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
