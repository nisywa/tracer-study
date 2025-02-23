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

    <!-- ==== WOW JS ==== -->
    <script src="{{asset('assets/js/wow.min.js')}}"></script>
    <script>
      new WOW().init();
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
  
  
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
              Survey Page</h1>
            <p class="mb-5 text-base text-body-color dark:text-dark-6">
                Silakan isi formulir di bawah ini dengan informasi yang sesuai.
            </p>

            <ul class="flex items-center justify-center gap-[10px]">
              <li>
                <a href="index.html"
                  class="flex items-center gap-[10px] text-base font-medium text-dark dark:text-white">
                  Home
                </a>
              </li>
              <li>
                <a href="javascript:void(0)" class="flex items-center gap-[10px] text-base font-medium text-body-color">
                  <span class="text-body-color dark:text-dark-6"> / </span>
                  Contact us
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- ====== Banner Section End -->

  <!-- ====== Contact Start ====== -->
  <section id="contact" class="relative py-20 md:py-[120px]">
    <div class="absolute top-0 left-0 -z-[1] w-full dark:bg-dark h-full"></div>
    <div class="absolute top-0 left-0 -z-[1] h-1/2 w-full bg-[#E9F9FF] dark:bg-dark-700 lg:h-[45%] xl:h-1/2"></div>
    <div class="container px-4">
      <div class="flex flex-wrap items-center -mx-4">
        
        <div class="w-full px-4 lg:w-full xl:w-full">
          <div
            class="wow fadeInUp rounded-lg bg-white dark:bg-dark-2 py-10 px-8 shadow-testimonial dark:shadow-none sm:py-12 sm:px-10 md:p-[60px] lg:p-10 lg:py-12 lg:px-10 2xl:p-[60px]"
            data-wow-delay=".2s
                ">
            <h3 class="mb-8 text-2xl font-semibold md:text-[28px] md:leading-[1.42] text-dark dark:text-white">
              Blok A. Pribadi
            </h3>
            <form>
              <div class="mb-[22px]">
                <label for="fullName" class="block mb-4 text-sm text-body-color dark:text-dark-6">Full Name*</label>
                <input type="text" name="fullName" placeholder="Adam Gelius"
                  class="bg-transparent w-full text-body-color dark:text-dark-6 placeholder:text-body-color/60 border-0 border-b border-[#f1f1f1] dark:border-dark-3 pb-3 focus:border-primary focus:outline-none" />
              </div>
              <div class="mb-[22px]">
                <label for="email" class="block mb-4 text-sm text-body-color dark:text-dark-6">Email*</label>
                <input type="email" name="email" placeholder="example@yourmail.com"
                  class="bg-transparent w-full text-body-color dark:text-dark-6 placeholder:text-body-color/60 border-0 border-b border-[#f1f1f1] dark:border-dark-3 pb-3 focus:border-primary focus:outline-none" />
              </div>
              <div class="mb-[22px]">
                <label for="phone" class="block mb-4 text-sm text-body-color dark:text-dark-6">Phone*</label>
                <input type="text" name="phone" placeholder="+885 1254 5211 552"
                  class="bg-transparent w-full text-body-color dark:text-dark-6 placeholder:text-body-color/60 border-0 border-b border-[#f1f1f1] dark:border-dark-3 pb-3 focus:border-primary focus:outline-none" />
              </div>
              <div class="mb-[22px]">
                <label for="file" class="block mb-4 text-sm text-body-color dark:text-dark-6">File*</label>
                <input type="file" name="file"
                  class="bg-transparent w-full text-body-color dark:text-dark-6 placeholder:text-body-color/60 border-0 border-b border-[#f1f1f1] dark:border-dark-3 pb-3 focus:border-primary focus:outline-none" />
              </div>
              <div class="mb-[22px]">
                <label for="date" class="block mb-4 text-sm text-body-color dark:text-dark-6">Tanggal*</label>
                <input type="text" id="date" name="date" placeholder="Pilih tanggal" 
                  class="bg-transparent w-full text-body-color dark:text-dark-6 placeholder:text-body-color/60 border-0 border-b border-[#f1f1f1] dark:border-dark-3 pb-3 focus:border-primary focus:outline-none datepicker" />
              </div>
              
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

              <div class="mb-[22px]">
                <label for="phone" class="block mb-4 text-sm text-body-color dark:text-dark-6">Pertanyaan Dropdown*</label>
                <select name="phone" class="bg-transparent w-full text-body-color dark:text-dark-6 
                    border-0 border-b border-[#f1f1f1] dark:border-dark-3 pb-3 focus:border-primary focus:outline-none">
                    <option value="" disabled selected hidden class="border-[#f1f1f1] dark:border-dark-3 pb-3">Choose</option>
                    <option value="option1">Option 1</option>
                    <option value="option2">Option 2</option>
                    <option value="option3">Option 3</option>
                </select>
               </div>

               <div class="mb-[22px]">
                <label for="phone" class="block mb-4 text-sm text-body-color dark:text-dark-6">
                    Pertanyaan Option*
                </label>
                <div class="flex flex-col gap-y-3 text-body-color dark:text-dark-6">
                    <label class="flex items-center gap-x-3">
                        <input type="radio" name="options" value="option1" class="text-primary">
                        <span>Option 1</span>
                    </label>
                    <label class="flex items-center gap-x-3">
                        <input type="radio" name="options" value="option2" class="text-primary">
                        <span>Option 2</span>
                    </label>
                    <label class="flex items-center gap-x-3">
                        <input type="radio" name="options" value="option3" class="text-primary">
                        <span>Option 3</span>
                    </label>
                </div>
                </div>
            
            
            
              
              
              


                <div class="mb-0 flex justify-end gap-4">
                  <button type="button"
                    class="inline-flex items-center justify-center px-10 py-3 text-base font-medium text-white transition duration-300 ease-in-out rounded-md bg-primary hover:bg-blue-dark">
                    Batal
                  </button>
                  <button type="submit"
                    class="inline-flex items-center justify-center px-10 py-3 text-base font-medium text-white transition duration-300 ease-in-out rounded-md bg-primary hover:bg-blue-dark">
                    Kirim
                  </button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- ====== Contact End ====== -->
    

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
