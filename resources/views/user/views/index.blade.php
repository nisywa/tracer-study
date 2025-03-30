<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>
      Tracer Study
    </title>
    <link
      rel="shortcut icon"
      href="{{asset('assets/images/logo.png')}}"
      type="image/x-icon"
    />
    <link rel="stylesheet" href="{{asset('assets/css/swiper-bundle.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/animate.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/tailwind.css')}}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- ==== WOW JS ==== -->
    <script src="{{asset('assets/js/wow.min.js')}}"></script>
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
  <div class="relative z-10 overflow-hidden pt-[120px] pb-[60px] md:pt-[130px] lg:pt-[160px] dark:bg-dark">
    <div
      class="absolute bottom-0 left-0 w-full h-px bg-gradient-to-r from-stroke/0 via-stroke dark:via-dark-3 to-stroke/0">
    </div>
    <div class="container">
      <div class="flex flex-wrap items-center -mx-4">
        <div class="w-full px-4">
          <div class="text-center">
            <h1 class="mb-4 text-3xl font-bold text-dark dark:text-white sm:text-4xl md:text-[40px] md:leading-[1.2]">
              Halo, {{  $alumni->nama }}</h1>
            <p class="mb-5 text-base text-body-color dark:text-dark-6">
              Yuk isi surveinya sekarang!
            </p>

            
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- ====== Banner Section End -->

  <!-- ====== Info Survei & User Start ====== -->
  <section id="contact" class="relative py-20 md:py-[120px]">
    <div class="absolute top-0 left-0 -z-[1] w-full dark:bg-dark h-full"></div>
    <div class="absolute top-0 left-0 -z-[1] h-1/2 w-full bg-[#E9F9FF] dark:bg-dark-700 lg:h-[45%] xl:h-1/2"></div>
    <div class="container px-4">
      <div class="flex flex-wrap items-stretch -mx-4">

        <!-- <div class="w-full px-4 lg:w-7/12 xl:w-8/12">
          <div class="ud-contact-content-wrapper">
            <div class="ud-contact-title mb-12 lg:mb-[150px]">
              <span class="block mb-6 text-base font-medium text-dark dark:text-white">
                CONTACT US
              </span>
              <h2 class="max-w-[260px] text-[35px] leading-[1.14] font-semibold text-dark dark:text-white">
                Let's talk about your problem.
              </h2>
            </div>
            <div class="flex flex-wrap justify-between mb-12 lg:mb-0">
              <div class="mb-8 flex w-[330px] max-w-full">
                <div class="mr-6 text-[32px] text-primary">
                  <svg width="29" height="35" viewBox="0 0 29 35" class="fill-current">
                    <path
                      d="M14.5 0.710938C6.89844 0.710938 0.664062 6.72656 0.664062 14.0547C0.664062 19.9062 9.03125 29.5859 12.6406 33.5234C13.1328 34.0703 13.7891 34.3437 14.5 34.3437C15.2109 34.3437 15.8672 34.0703 16.3594 33.5234C19.9688 29.6406 28.3359 19.9062 28.3359 14.0547C28.3359 6.67188 22.1016 0.710938 14.5 0.710938ZM14.9375 32.2109C14.6641 32.4844 14.2812 32.4844 14.0625 32.2109C11.3828 29.3125 2.57812 19.3594 2.57812 14.0547C2.57812 7.71094 7.9375 2.625 14.5 2.625C21.0625 2.625 26.4219 7.76562 26.4219 14.0547C26.4219 19.3594 17.6172 29.2578 14.9375 32.2109Z" />
                    <path
                      d="M14.5 8.58594C11.2734 8.58594 8.59375 11.2109 8.59375 14.4922C8.59375 17.7188 11.2187 20.3984 14.5 20.3984C17.7812 20.3984 20.4062 17.7734 20.4062 14.4922C20.4062 11.2109 17.7266 8.58594 14.5 8.58594ZM14.5 18.4297C12.3125 18.4297 10.5078 16.625 10.5078 14.4375C10.5078 12.25 12.3125 10.4453 14.5 10.4453C16.6875 10.4453 18.4922 12.25 18.4922 14.4375C18.4922 16.625 16.6875 18.4297 14.5 18.4297Z" />
                  </svg>
                </div>
                <div>
                  <h5 class="mb-[18px] text-lg font-semibold text-dark dark:text-white">Our Location</h5>
                  <p class="text-base text-body-color dark:text-dark-6">
                    401 Broadway, 24th Floor, Orchard Cloud View, London
                  </p>
                </div>
              </div>
              <div class="mb-8 flex w-[330px] max-w-full">
                <div class="mr-6 text-[32px] text-primary">
                  <svg width="34" height="25" viewBox="0 0 34 25" class="fill-current">
                    <path
                      d="M30.5156 0.960938H3.17188C1.42188 0.960938 0 2.38281 0 4.13281V20.9219C0 22.6719 1.42188 24.0938 3.17188 24.0938H30.5156C32.2656 24.0938 33.6875 22.6719 33.6875 20.9219V4.13281C33.6875 2.38281 32.2656 0.960938 30.5156 0.960938ZM30.5156 2.875C30.7891 2.875 31.0078 2.92969 31.2266 3.09375L17.6094 11.3516C17.1172 11.625 16.5703 11.625 16.0781 11.3516L2.46094 3.09375C2.67969 2.98438 2.89844 2.875 3.17188 2.875H30.5156ZM30.5156 22.125H3.17188C2.51562 22.125 1.91406 21.5781 1.91406 20.8672V5.00781L15.0391 12.9922C15.5859 13.3203 16.1875 13.4844 16.7891 13.4844C17.3906 13.4844 17.9922 13.3203 18.5391 12.9922L31.6641 5.00781V20.8672C31.7734 21.5781 31.1719 22.125 30.5156 22.125Z" />
                  </svg>
                </div>
                <div>
                  <h5 class="mb-[18px] text-lg font-semibold text-dark dark:text-white">How Can We Help?</h5>
                  <p class="text-base text-body-color dark:text-dark-6">info@yourdomain.com</p>
                  <p class="mt-1 text-base text-body-color dark:text-dark-6">
                    contact@yourdomain.com
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div> -->
        
        <div class="w-full px-4 lg:w-7/12 xl:w-8/12">
          <div class="wow fadeInUp rounded-lg bg-white dark:bg-dark-2 py-10 px-8 shadow-testimonial dark:shadow-none sm:py-12 sm:px-10 md:p-[60px] lg:p-10 lg:py-12 lg:px-10 2xl:p-[60px]" data-wow-delay=".2s">
            <h3 class="mb-8 text-2xl font-semibold md:text-[28px] md:leading-[1.42] text-dark dark:text-white">
              Informasi Survei
            </h3>
            <div class="overflow-x-auto">
              <table class="w-full min-w-full border border-gray-300 dark:border-gray-600 table-fixed">
                <thead>
                  <tr class="bg-gray-100 text-dark dark:text-white">
                    <th class="w-5/12 border border-gray-300 px-4 py-2 text-left">Nama</th>
                    <th class="w-auto border border-gray-300 px-4 py-2 text-left">Status</th>
                    <th class="w-4/12 border border-gray-300 px-4 py-2 text-left">Deskripsi</th>
                    <th class="w-1/12 border border-gray-300 px-4 py-2 text-left">Aksi</th>
                  </tr>
                </thead>
                <tbody class="text-body-color dark:text-dark-6">
                @foreach($survey as $srvy)
                  <tr>
                    <td class="border border-gray-300 px-4 py-2 font-bold">{{ $srvy->nama }}</td>
                    <td class="border border-gray-300 px-2 py-2 text-center">
                      <span class="bg-gradient-to-tl {{$srvy->status_aktif =='Aktif' ? 'from-emerald-500 to-teal-400' : 'from-slate-600 to-slate-300'}}  px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">{{ $srvy->status_aktif }}</span>
                      <br> <span> {{ $srvy->tanggal_mulai }} - {{ $srvy->tanggal_selesai }}</span>
                    </td>
                    <td class="border border-gray-300 px-4 py-2">{{ $srvy->deskripsi }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center relative group">
                      <!-- <a href="{{ route('user.survey.survey', $srvy) }}" class="icon-link" data-tooltip="Mengerjakan Pertanyaan">
                        <i class="fas fa-pencil-alt"></i> 
                      </a> -->
                      @if ($srvy->status==0)
                        <a href="{{ route('user.survey.survey', $srvy) }}" class="bg-gradient-to-tl from-emerald-500 to-teal-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Isi Survei</a>
                      @else
                        selesai
                      @endif
                    </td>
                    
                  </tr>
                  @endforeach
                </tbody>
              </table>
              
            </div>
          </div>
        </div>
        
        

        
        <div class="w-full px-4 lg:w-5/12 xl:w-4/12">
          <div
            class="wow fadeInUp rounded-lg bg-white dark:bg-dark-2 py-10 px-8 shadow-testimonial dark:shadow-none sm:py-12 sm:px-10 md:p-[60px] lg:p-10 lg:py-12 lg:px-10 2xl:p-[60px]"
            data-wow-delay=".2s
                ">
            <h3 class="mb-8 text-2xl font-semibold md:text-[28px] md:leading-[1.42] text-dark dark:text-white">
              Informasi User
            </h3>

            
              
              <!-- <div class="mb-[22px]">
                <label for="fullName" class="block mb-4 text-sm text-body-color dark:text-dark-6">Nama</label>
                <p class="text-body-color/60 dark:text-dark-6">hai</p>
                <div class="border-b border-gray-300 dark:border-gray-200 mt-2"></div>
              </div> -->

              <div class="mb-[22px]">
                <label for="nama" class="block mb-4 text-sm text-body-color dark:text-dark-6">Nama</label>
                <p class="text-body-color/60 dark:text-dark-6 pb-3">{{  $alumni->nama }}</p>
                <div class="bg-transparent border-b border-[#f1f1f1] dark:border-dark-3 mt-2"></div>
              </div>

              <div class="mb-[22px]">
                <label for="email" class="block mb-4 text-sm text-body-color dark:text-dark-6">Email</label>
                <p class="text-body-color/60 dark:text-dark-6 pb-3">{{  $alumni->email }}</p>
                <div class="bg-transparent border-b border-[#f1f1f1] dark:border-dark-3 mt-2"></div>
              </div>

              <div class="mb-[22px]">
                <label for="email" class="block mb-4 text-sm text-body-color dark:text-dark-6">NIP</label>
                <p class="text-body-color/60 dark:text-dark-6 pb-3">{{  $alumni->nip }}</p>
                <div class="bg-transparent border-b border-[#f1f1f1] dark:border-dark-3 mt-2"></div>
              </div>

              <div class="mb-[22px]">
                <label for="email" class="block mb-4 text-sm text-body-color dark:text-dark-6">Jabatan</label>
                <p class="text-body-color/60 dark:text-dark-6 pb-3">{{  $alumni->jabatan }}</p>
                <div class="bg-transparent border-b border-[#f1f1f1] dark:border-dark-3 mt-2"></div>
              </div>

              <div class="mb-[22px]">
                <label for="email" class="block mb-4 text-sm text-body-color dark:text-dark-6">Satuan Kerja</label>
                <p class="text-body-color/60 dark:text-dark-6 pb-3">{{  $alumni->satuan_kerja }}</p>
                <div class="bg-transparent border-b border-[#f1f1f1] dark:border-dark-3 mt-2"></div>
              </div>

              <div class="mb-[22px]">
                <label for="email" class="block mb-4 text-sm text-body-color dark:text-dark-6">Unit Kerja</label>
                <p class="text-body-color/60 dark:text-dark-6 pb-3">{{  $alumni->unit_kerja }}</p>
                <div class="bg-transparent border-b border-[#f1f1f1] dark:border-dark-3 mt-2"></div>
              </div>

              <div class="mb-[22px]">
                <label for="email" class="block mb-4 text-sm text-body-color dark:text-dark-6">No HP</label>
                <p class="text-body-color/60 dark:text-dark-6 pb-3">{{  $alumni->no_hp }}</p>
                <div class="bg-transparent border-b border-[#f1f1f1] dark:border-dark-3 mt-2"></div>
              </div>

              <div class="mb-[22px]">
                <label for="email" class="block mb-4 text-sm text-body-color dark:text-dark-6">Nama Kepala BPS Tempat Bekerja</label>
                <p class="text-body-color/60 dark:text-dark-6 pb-3">{{  $alumni->kepala_bps }}</p>
                <div class="bg-transparent border-b border-[#f1f1f1] dark:border-dark-3 mt-2"></div>
              </div>

          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- ====== Info Survei & User End ====== -->

    @include('user.layouts.footer')

    <!-- ====== Back To Top Start -->
    <a
      href="javascript:void(0)"
      class="back-to-top fixed bottom-8 left-auto right-8 z-[999] hidden h-10 w-10 items-center justify-center rounded-md bg-primary text-white shadow-md transition duration-300 ease-in-out hover:bg-dark"
    >
      <span
        class="mt-[6px] h-3 w-3 rotate-45 border-l border-t border-white"
      ></span>
    </a>
    <!-- ====== Back To Top End -->

    

    <!-- ====== All Scripts -->

    <script src="assets/js/swiper-bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
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
