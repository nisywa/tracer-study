@extends('admin.layouts.app')

@section('title', 'Monitoring')

@section('content')
<div>
  <h1 class="mb-0 text-xl leading-normal text-white dark:text-white dark:opacity-60">
    <span class="font-bold">{{ $survey->nama }}</span>
  </h1>
</div>

<div class="flex flex-wrap mt-6 -mx-3">
  
  <!-- Line chart -->
  <div class="w-full max-w-full px-3 mt-0 lg:w-6/12 mb-6 lg:flex-none">
    <div class="border-black/12.5 dark:bg-slate-850 dark:shadow-dark-xl shadow-xl relative z-20 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border h-full">
      <div class="border-black/12.5 mb-3 rounded-t-2xl border-b-0 border-solid p-6 pt-4 pb-0">
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

  <!-- Pie chart -->
  <div class="w-full max-w-full px-3 mt-0 lg:w-6/12 mb-6 lg:flex-none">
    <div class="border-black/12.5 dark:bg-slate-850 dark:shadow-dark-xl shadow-xl relative z-20 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border h-full">
      <div class="border-black/12.5 mb-0 rounded-t-2xl border-b-0 border-solid p-6 pt-4 pb-0">
        <h6 class="capitalize dark:text-white">Category Distribution</h6>
        <p class="mb-0 text-sm leading-normal dark:text-white dark:opacity-60">
          <i class="fa fa-chart-pie text-blue-500"></i>
          <span class="font-semibold">Top Categories</span>
        </p>
      </div>
      <div class="flex-auto p-4">
        <div id="chart" height="300"></div>

        <script>
          var options = {
            series: [44, 55, 13, 43, 22],
            chart: {
              width: '100%',
              type: 'pie',
            },
            labels: ['Team A', 'Team B', 'Team C', 'Team D', 'Team E'],
            responsive: [{
              breakpoint: 480,
              options: {
                chart: {
                  width: 200
                },
                legend: {
                  position: 'bottom'
                }
              }
            }]
          };

          var chart = new ApexCharts(document.querySelector("#chart"), options);
          chart.render();
        </script>
      </div>
    </div>
  </div>

  <!-- Bar chart -->
  <div class="w-full max-w-full px-3 mt-0 lg:w-6/12 mb-6 lg:flex-none">
    <div class="border-black/12.5 dark:bg-slate-850 dark:shadow-dark-xl shadow-xl relative z-20 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border h-full">
      <div class="border-black/12.5 mb-0 rounded-t-2xl border-b-0 border-solid p-6 pt-4 pb-0">
        <h6 class="capitalize dark:text-white">Category Distribution</h6>
        <p class="mb-0 text-sm leading-normal dark:text-white dark:opacity-60">
          <i class="fa fa-chart-pie text-blue-500"></i>
          <span class="font-semibold">Top Categories</span>
        </p>
      </div>
      <div class="flex-auto p-4">
        <div>
          <canvas id="chart-bar" height="300"></canvas>
        </div>
      </div>
    </div>
  </div>
   
</div>



        <!-- bar chart -->
        <!-- <div class="w-full max-w-full px-3 mt-0 lg:w-7/12 lg:flex-none">
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
                  <canvas id="chart-bar" height="300"></canvas>
                </div>
              </div>
            </div>
        </div> -->
        

    
@endsection