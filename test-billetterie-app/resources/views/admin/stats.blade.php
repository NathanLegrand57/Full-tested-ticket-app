<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-white leading-tight">
                Statistiques des ventes
            </h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 bg-white shadow sm:rounded-2xl p-6 border border-gray-200">
                <form method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Du</label>
                        <input type="date" name="from" value="{{ $from }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-fnac-red focus:ring-fnac-red">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Au</label>
                        <input type="date" name="to" value="{{ $to }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-fnac-red focus:ring-fnac-red">
                    </div>
                    <div class="sm:col-span-2 flex gap-2">
                        <button type="submit"
                                class="btn-fnac-primary px-5 py-2.5">
                            Filtrer
                        </button>
                        <a href="{{ route('admin.stats') }}"
                           class="btn-fnac-secondary px-5 py-2.5">
                            Réinitialiser
                        </a>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white shadow sm:rounded-2xl p-4 border border-gray-200">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-[0.2em]">Billets vendus</h3>
                    <p class="mt-2 text-3xl font-extrabold text-fnac-dark">
                        {{ $totalTicketsSold }}
                    </p>
                </div>
                <div class="bg-white shadow sm:rounded-2xl p-4 border border-gray-200">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-[0.2em]">Chiffre d'affaires</h3>
                    <p class="mt-2 text-3xl font-extrabold text-fnac-red">
                        {{ number_format($totalRevenue, 2, ',', ' ') }} €
                    </p>
                </div>
                <div class="bg-white shadow sm:rounded-2xl p-4 border border-gray-200">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-[0.2em]">Spectacles suivis</h3>
                    <p class="mt-2 text-3xl font-extrabold text-fnac-dark">
                        {{ $shows->count() }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <div class="lg:col-span-2 bg-white shadow sm:rounded-2xl p-4 border border-gray-200">
                    <h3 class="text-sm font-semibold text-fnac-dark mb-2">Réservations par jour</h3>
                    <canvas id="reservationsChart" height="120"></canvas>
                </div>
                <div class="bg-white shadow sm:rounded-2xl p-4 border border-gray-200">
                    <h3 class="text-sm font-semibold text-fnac-dark mb-2">Top 3 spectacles par CA</h3>
                    <table class="min-w-full text-sm">
                        <thead>
                        <tr class="border-b border-gray-200">
                            <th class="py-2 text-left">Spectacle</th>
                            <th class="py-2 text-right">CA</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($topShows as $item)
                            <tr class="border-b border-gray-100">
                                <td class="py-2 pr-2">
                                    {{ $item['show']?->title ?? 'Inconnu' }}
                                    <div class="text-xs text-gray-500">
                                        {{ $item['tickets'] }} billets
                                    </div>
                                </td>
                                <td class="py-2 text-right text-fnac-red font-semibold">
                                    {{ number_format($item['revenue'], 2, ',', ' ') }} €
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="py-2 text-center text-gray-500">
                                    Aucune donnée disponible.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white shadow sm:rounded-2xl p-4 border border-gray-200">
                <h3 class="text-sm font-semibold text-fnac-dark mb-2">Places restantes par spectacle</h3>
                <table class="min-w-full text-sm">
                    <thead>
                    <tr class="border-b border-gray-200">
                        <th class="py-2 text-left">Spectacle</th>
                        <th class="py-2 text-right">Places restantes</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($shows as $show)
                        <tr class="border-b border-gray-100">
                            <td class="py-2 pr-2">{{ $show->title }}</td>
                            <td class="py-2 text-right">
                                <span class="badge-fnac-round text-[0.7rem]">
                                    {{ $show->places_disponibles }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="py-2 text-center text-gray-500">
                                Aucun spectacle.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('reservationsChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartDates),
                datasets: [
                    {
                        label: 'Réservations',
                        data: @json($chartReservations),
                        borderColor: '#DC2626',
                        backgroundColor: 'rgba(220, 38, 38, 0.15)',
                        tension: 0.2
                    },
                    {
                        label: 'Chiffre d\'affaires (€)',
                        data: @json($chartRevenue),
                        borderColor: '#111827',
                        backgroundColor: 'rgba(17, 24, 39, 0.08)',
                        tension: 0.2,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                stacked: false,
                scales: {
                    y: {
                        type: 'linear',
                        position: 'left',
                        beginAtZero: true
                    },
                    y1: {
                        type: 'linear',
                        position: 'right',
                        beginAtZero: true,
                        grid: {
                            drawOnChartArea: false,
                        },
                    },
                }
            }
        });
    </script>
</x-app-layout>

