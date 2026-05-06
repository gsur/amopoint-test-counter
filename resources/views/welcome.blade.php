<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ __('Аналитика посещений') }} - {{ config('app.name', 'Laravel') }}</title>
        @vite(['resources/css/app.css'])
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body class="min-h-screen bg-zinc-50 text-zinc-900">
        <main class="mx-auto flex min-h-screen w-full max-w-5xl flex-col items-center justify-center gap-10 px-4 py-8">
            <section class="w-full rounded-xl bg-white p-6 shadow-md">
                <h2 class="mb-4 text-lg font-semibold">Уникальные посещения по часам (за последние 24 часа)</h2>
                <div class="h-80">
                    <canvas id="hourlyVisitsChart"></canvas>
                </div>
            </section>

            <section class="w-full rounded-xl bg-white p-6 shadow-md">
                <h2 class="mb-4 text-lg font-semibold">Доля уникальных посещений по городам</h2>
                <div class="mx-auto h-80 max-w-xl">
                    <canvas id="cityShareChart"></canvas>
                </div>
            </section>
        </main>

        <script>
            const hourlyLabels = @json($hourlyVisitsChart['labels']);
            const hourlyValues = @json($hourlyVisitsChart['values']);
            const cityLabels = @json($cityShareChart['labels']);
            const cityValues = @json($cityShareChart['values']);

            new Chart(document.getElementById('hourlyVisitsChart'), {
                type: 'line',
                data: {
                    labels: hourlyLabels,
                    datasets: [{
                        label: 'Уникальные посещения',
                        data: hourlyValues,
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.2)',
                        tension: 0.25,
                        fill: true,
                        pointRadius: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Часы'
                            }
                        },
                        y: {
                            min: 10,
                            title: {
                                display: true,
                                text: 'Уникальные посещения'
                            },
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });

            new Chart(document.getElementById('cityShareChart'), {
                type: 'pie',
                data: {
                    labels: cityLabels.length ? cityLabels : ['Нет данных'],
                    datasets: [{
                        data: cityValues.length ? cityValues : [1],
                        backgroundColor: [
                            '#3b82f6',
                            '#10b981',
                            '#f59e0b',
                            '#ef4444',
                            '#8b5cf6',
                            '#06b6d4',
                            '#84cc16',
                            '#f97316',
                            '#14b8a6',
                            '#eab308'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        </script>
    </body>
</html>
