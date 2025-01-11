@extends('admin.layouts.app')

@section('title', 'Monitoring')

@section('content')
<div class="flex flex-wrap mt-6 -mx-3">
    <div class="w-full max-w-full px-3 mt-0 lg:w-7/12 lg:flex-none">
    <div class="border-black/12.5 dark:bg-slate-850 dark:shadow-dark-xl shadow-xl relative z-20 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border">
        <div class="border-black/12.5 mb-0 rounded-t-2xl border-b-0 border-solid p-6 pt-4 pb-0">
        <h6 class="capitalize dark:text-white">Sales overview</h6>
        <p class="mb-0 text-sm leading-normal dark:text-white dark:opacity-60">
            <i class="fa fa-arrow-up text-emerald-500"></i>
            <span class="font-semibold">4% more</span> in 2021
        </p>
        </div>
        <div class="flex-auto p-4">
        <div>
            <canvas id="chart-line" height="300"></canvas>
        </div>
        </div>
    </div>
    </div>

    <div class="w-full max-w-full px-3 lg:w-5/12 lg:flex-none">
    <div slider class="relative w-full h-full overflow-hidden rounded-2xl">
        <!-- slide 1 -->
        <div slide class="absolute w-full h-full transition-all duration-500">
        <img class="object-cover h-full" src="../assets/img/carousel-1.jpg" alt="carousel image" />
        <div class="block text-start ml-12 left-0 bottom-0 absolute right-[15%] pt-5 pb-5 text-white">
            <div class="inline-block w-8 h-8 mb-4 text-center text-black bg-white bg-center rounded-lg fill-current stroke-none">
            <i class="top-0.75 text-xxs relative text-slate-700 ni ni-camera-compact"></i>
            </div>
            <h5 class="mb-1 text-white">Get started with Argon</h5>
            <p class="dark:opacity-80">There’s nothing I really wanted to do in life that I wasn’t able to get good at.</p>
        </div>
        </div>

        <!-- slide 2 -->
        <div slide class="absolute w-full h-full transition-all duration-500">
        <img class="object-cover h-full" src="../assets/img/carousel-2.jpg" alt="carousel image" />
        <div class="block text-start ml-12 left-0 bottom-0 absolute right-[15%] pt-5 pb-5 text-white">
            <div class="inline-block w-8 h-8 mb-4 text-center text-black bg-white bg-center rounded-lg fill-current stroke-none">
            <i class="top-0.75 text-xxs relative text-slate-700 ni ni-bulb-61"></i>
            </div>
            <h5 class="mb-1 text-white">Faster way to create web pages</h5>
            <p class="dark:opacity-80">That’s my skill. I’m not really specifically talented at anything except for the ability to learn.</p>
        </div>
        </div>

        <!-- slide 3 -->
        <div slide class="absolute w-full h-full transition-all duration-500">
        <img class="object-cover h-full" src="../assets/img/carousel-3.jpg" alt="carousel image" />
        <div class="block text-start ml-12 left-0 bottom-0 absolute right-[15%] pt-5 pb-5 text-white">
            <div class="inline-block w-8 h-8 mb-4 text-center text-black bg-white bg-center rounded-lg fill-current stroke-none">
            <i class="top-0.75 text-xxs relative text-slate-700 ni ni-trophy"></i>
            </div>
            <h5 class="mb-1 text-white">Share with us your design tips!</h5>
            <p class="dark:opacity-80">Don’t be afraid to be wrong because you can’t learn anything from a compliment.</p>
        </div>
        </div>

        <!-- Control buttons -->
        <button btn-next class="absolute z-10 w-10 h-10 p-2 text-lg text-white border-none opacity-50 cursor-pointer hover:opacity-100 far fa-chevron-right active:scale-110 top-6 right-4"></button>
        <button btn-prev class="absolute z-10 w-10 h-10 p-2 text-lg text-white border-none opacity-50 cursor-pointer hover:opacity-100 far fa-chevron-left active:scale-110 top-6 right-16"></button>
    </div>
    </div>
</div>
@endsection