<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Historique de mes réservations
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Messages -->
            @if (session('success'))
                <div
                    class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-xl flex items-start gap-3 shadow-sm">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div
                    class="mb-4 bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-xl flex items-start gap-3 shadow-sm">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="font-semibold">{{ session('error') }}</span>
                </div>
            @endif

            @if ($reservations->isEmpty())
                <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-200">
                    <div class="p-12 text-center">
                        <div class="text-gray-300 dark:text-gray-600 mb-6">
                            <svg class="mx-auto h-16 w-16" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-fnac-dark mb-2">Aucune réservation</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6 text-lg">Vous n'avez pas encore effectué de
                            réservation</p>
                        <a href="{{ route('shows.index') }}" class="btn-fnac-primary px-8 py-3">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            Découvrir les spectacles
                        </a>
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-fnac-dark"> Mes réservations
                            ({{ $reservations->total() }})</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                                <tr>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Spectacle
                                    </th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Date
                                    </th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Billets
                                    </th>
                                    <th
                                        class="px-6 py-4 text-right text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Montant
                                    </th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Réservation
                                    </th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Statut
                                    </th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($reservations as $reservation)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div>
                                                @if ($reservation->show)
                                                    <a href="{{ route('shows.show', $reservation->show->id) }}"
                                                        class="text-fnac-red hover:text-red-700 font-bold transition">
                                                        {{ $reservation->show->title }}
                                                    </a>
                                                @else
                                                    <span class="text-gray-500 font-bold">Spectacle
                                                        supprimé</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <div class="text-fnac-dark font-semibold">
                                                @if ($reservation->show)
                                                    {{ \Carbon\Carbon::parse($reservation->show->show_date)->format('d/m/Y') }}
                                                @else
                                                    -
                                                @endif
                                            </div>
                                            <span class="text-xs text-gray-500">
                                                @if ($reservation->show)
                                                    {{ \Carbon\Carbon::parse($reservation->show->show_date)->format('H:i') }}
                                                @else
                                                    -
                                                @endif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span
                                                class="inline-flex items-center justify-center w-8 h-8 bg-red-50 text-fnac-red rounded-full font-bold">
                                                {{ $reservation->quantity }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <span class="text-lg font-bold text-fnac-red">
                                                {{ number_format($reservation->amount, 2, ',', ' ') }} €
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                            <div class="text-gray-600">
                                                {{ $reservation->created_at->format('d/m/Y') }}
                                            </div>
                                            <span
                                                class="text-xs text-gray-500">{{ $reservation->created_at->format('H:i') }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if ($reservation->status === 'paid')
                                                <span
                                                    class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold uppercase">Payé</span>
                                            @else
                                                <span
                                                    class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold uppercase">En
                                                    attente</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('reservations.show', $reservation->id) }}"
                                                    class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 text-fnac-dark hover:bg-gray-200 transition"
                                                    title="Voir les détails">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                        <path fill-rule="evenodd"
                                                            d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </a>

                                                @if ($reservation->show ? \Carbon\Carbon::parse($reservation->show->show_date)->isFuture() : true)
                                                    <form
                                                        action="{{ route('reservations.destroy', $reservation->id) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="flex items-center justify-center w-10 h-10 rounded-full bg-red-50 text-fnac-red hover:bg-red-100 transition"
                                                            title="Annuler"
                                                            onclick="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')">
                                                            <svg class="w-5 h-5" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @else
                                                    <span
                                                        class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 text-gray-400 cursor-not-allowed"
                                                        title="Spectacle passé - Non annulable">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($reservations->hasPages())
                        <div class="bg-white border-t border-gray-100 px-6 py-4">
                            {{ $reservations->links() }}
                        </div>
                    @endif
                </div>

                <!-- Back link -->
                <div class="mt-6">
                    <a href="{{ route('shows.index') }}"
                        class="flex items-center text-fnac-red hover:text-red-700 font-semibold transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 19l-7-7 7-7" />
                        </svg>
                        Découvrir d'autres spectacles
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
