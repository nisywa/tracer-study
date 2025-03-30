@extends('admin.layouts.app')
@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full px-3">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <h6 class="dark:text-white mb-4">Visualisasi Survey: {{ $survey->nama }}</h6>

                        @php
                            $totalResponses = $survey->surveyUsers()->count();
                            $completedResponses = $survey->surveyUsers()->where('status', true)->count();
                            $completionRate =
                                $totalResponses > 0 ? round(($completedResponses / $totalResponses) * 100, 1) : 0;
                        @endphp

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div class="bg-blue-50 dark:bg-blue-900 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-blue-600 dark:text-blue-300">Total Responden</h4>
                                <p class="text-2xl font-bold text-blue-800 dark:text-blue-100">{{ $totalResponses }}</p>
                            </div>
                            <div class="bg-green-50 dark:bg-green-900 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-green-600 dark:text-green-300">Responden Selesai</h4>
                                <p class="text-2xl font-bold text-green-800 dark:text-green-100">{{ $completedResponses }}
                                </p>
                            </div>
                            <div class="bg-purple-50 dark:bg-purple-900 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-purple-600 dark:text-purple-300">Tingkat Penyelesaian
                                </h4>
                                <p class="text-2xl font-bold text-purple-800 dark:text-purple-100">{{ $completionRate }}%
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach ($questions as $question)
                                    <div class="bg-white dark:bg-slate-800 p-4 rounded-lg shadow">
                                        <div class="mb-4">
                                            <h3 class="text-lg font-semibold dark:text-white">{{ $question->pertanyaan }}
                                            </h3>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $question->blok }}</p>
                                        </div>
                                        <div class="chart-container bg-white dark:bg-slate-800 rounded-lg"
                                            style="position: relative; height:300px;">
                                            <canvas id="chart-{{ $question->id }}" class="p-2"></canvas>
                                            <div id="error-{{ $question->id }}"
                                                class="text-red-500 dark:text-red-400 text-center hidden">
                                                <i class="fas fa-exclamation-circle mr-2"></i>Error loading chart data
                                            </div>
                                            <div id="loading-{{ $question->id }}"
                                                class="text-center py-4 text-gray-600 dark:text-gray-400">
                                                <div class="animate-spin inline-block w-6 h-6 border-[3px] border-current border-t-transparent text-blue-600 dark:text-blue-500 rounded-full"
                                                    role="status" aria-label="loading">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                                <p class="mt-2">Loading chart data...</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <!-- Load Chart.js from CDN -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
        <script>
            console.log('Available questions:', @json($questions->pluck('pertanyaan', 'id')));

            function hideLoading(questionId) {
                document.getElementById(`loading-${questionId}`).style.display = 'none';
            }

            function showError(questionId) {
                hideLoading(questionId);
                document.getElementById(`error-${questionId}`).classList.remove('hidden');
            }

            // Function to get chart colors based on current theme
            function getChartThemeColors() {
                const isDark = document.documentElement.classList.contains('dark');
                return {
                    gridColor: isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)',
                    textColor: isDark ? '#fff' : '#000'
                };
            }

            // Create a map to store chart instances
            const chartInstances = new Map();

            // Function to update chart themes
            function updateChartThemes() {
                const {
                    gridColor,
                    textColor
                } = getChartThemeColors();

                chartInstances.forEach((chart, id) => {
                    if (chart.config.type === 'bar') {
                        chart.options.scales.y.grid.color = gridColor;
                        chart.options.scales.y.ticks.color = textColor;
                        chart.options.scales.x.grid.color = gridColor;
                        chart.options.scales.x.ticks.color = textColor;
                    }
                    chart.options.plugins.legend.labels.color = textColor;
                    chart.update();
                });
            }

            // Watch for theme changes
            const observer = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    if (mutation.target.classList.contains('dark')) {
                        updateChartThemes();
                    }
                });
            });

            observer.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class']
            });

            document.addEventListener('DOMContentLoaded', () => {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const fetchOptions = {
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin'
                };

                console.log('DOM loaded, initializing charts with CSRF protection...');
                const chartColors = [
                    '#4B0082', // Indigo
                    '#0096FF', // Blue
                    '#00FF7F', // Spring Green
                    '#FFD700', // Gold
                    '#FF69B4', // Hot Pink
                    '#8B4513', // Saddle Brown
                    '#4682B4', // Steel Blue
                    '#D2691E', // Chocolate
                    '#9370DB', // Medium Purple
                    '#3CB371' // Medium Sea Green
                ];

                // Store colors for consistent checkbox answer colors
                const answerColorMap = new Map();
                let colorIndex = 0;

                function getColorForAnswer(answer) {
                    if (!answerColorMap.has(answer)) {
                        answerColorMap.set(answer, chartColors[colorIndex % chartColors.length]);
                        colorIndex++;
                    }
                    return answerColorMap.get(answer);
                }

                @foreach ($questions as $question)
                    fetch("{{ route('admin.monitoring.chartData', ['surveyId' => $survey->id, 'questionId' => $question->id]) }}",
                            fetchOptions)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.error) {
                                throw new Error(data.error);
                            }

                            hideLoading('{{ $question->id }}');
                            const ctx = document.getElementById('chart-{{ $question->id }}').getContext('2d');

                            // Set colors for datasets, using consistent colors for repeated answers
                            if (data.data.datasets && data.data.datasets.length > 0) {
                                data.data.datasets[0].backgroundColor = data.data.labels.map(answer =>
                                    getColorForAnswer(answer)
                                );

                                // Add hover effects
                                data.data.datasets[0].hoverBackgroundColor = data.data.labels.map(answer => {
                                    const color = getColorForAnswer(answer);
                                    return color.replace('1)',
                                        '0.8)'); // Make hover color slightly transparent
                                });
                            }

                            // Set chart background in dark mode
                            if (document.documentElement.classList.contains('dark')) {
                                ctx.canvas.style.backgroundColor = 'rgb(30, 41, 59)'; // slate-800
                            }

                            const chart = new Chart(ctx, {
                                type: data.type, // 'pie' or 'bar' from backend
                                data: data.data,
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    animation: {
                                        duration: 1000,
                                        easing: 'easeInOutQuart'
                                    },
                                    plugins: {
                                        legend: {
                                            position: 'bottom',
                                            labels: {
                                                padding: 20,
                                                usePointStyle: true,
                                                font: {
                                                    size: 12
                                                },
                                                color: document.documentElement.classList.contains(
                                                        'dark') ?
                                                    '#fff' : '#000'
                                            }
                                        },
                                        tooltip: {
                                            callbacks: {
                                                label: function(context) {
                                                    const value = context.raw;
                                                    const total = context.dataset.data.reduce((a,
                                                            b) =>
                                                        a + b, 0);
                                                    const percentage = ((value / total) * 100)
                                                        .toFixed(
                                                            1);
                                                    return `${context.label}: ${value} responden (${percentage}%)`;
                                                }
                                            },
                                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                            titleColor: '#fff',
                                            bodyColor: '#fff',
                                            padding: 12,
                                            boxPadding: 6
                                        }
                                    },
                                    scales: data.type === 'bar' ? {
                                        y: {
                                            beginAtZero: true,
                                            grid: {
                                                color: document.documentElement.classList.contains(
                                                        'dark') ? 'rgba(255, 255, 255, 0.1)' :
                                                    'rgba(0, 0, 0, 0.1)'
                                            },
                                            ticks: {
                                                precision: 0,
                                                color: document.documentElement.classList.contains(
                                                    'dark') ? '#fff' : '#000',
                                                callback: function(value) {
                                                    return value + ' responden';
                                                }
                                            }
                                        },
                                        x: {
                                            grid: {
                                                color: document.documentElement.classList.contains(
                                                        'dark') ? 'rgba(255, 255, 255, 0.1)' :
                                                    'rgba(0, 0, 0, 0.1)'
                                            },
                                            ticks: {
                                                color: document.documentElement.classList.contains(
                                                    'dark') ? '#fff' : '#000'
                                            }
                                        }
                                    } : undefined
                                }
                            });

                            // Store chart instance for theme updates
                            chartInstances.set('{{ $question->id }}', chart);
                        })
                        .catch(error => {
                            console.error('Error loading chart data:', error);
                            const debugInfo = {
                                question: '{{ $question->pertanyaan }}',
                                url: "{{ route('admin.monitoring.chartData', ['surveyId' => $survey->id, 'questionId' => $question->id]) }}",
                                error: error.toString()
                            };
                            console.log('Debug info:', debugInfo);
                            showError('{{ $question->id }}');

                            // Display error details in dev mode
                            if ({{ config('app.debug') ? 'true' : 'false' }}) {
                                document.getElementById(`error-{{ $question->id }}`).innerHTML +=
                                    `<br><small class="text-gray-500">${error.toString()}</small>`;
                            }
                        });
                @endforeach
            });
        </script>
    @endpush
@endsection
