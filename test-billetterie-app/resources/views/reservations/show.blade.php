<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Détails de la réservation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Reservation number -->
            <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg border border-gray-100 dark:border-gray-700">
                    <p class="text-sm text-gray-600 dark:text-gray-400 font-semibold uppercase">Numéro de réservation</p>
                    <p class="text-4xl font-bold text-blue-600 dark:text-blue-400 mt-2">#{{ $reservation->id }}</p>
                </div>
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg border border-gray-100 dark:border-gray-700">
                    <p class="text-sm text-gray-600 dark:text-gray-400 font-semibold uppercase">Date de réservation</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-2">
                        {{ $reservation->created_at->format('d/m/Y') }}
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">à
                        {{ $reservation->created_at->format('H:i') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Show information -->
                    <div
                        class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-xl border border-gray-100 dark:border-gray-700">
                        <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">🎬 Spectacle</h3>
                        </div>
                        <div class="p-6">
                            <div class="flex gap-6">
                                <!-- Image -->
                                <div
                                    class="flex-shrink-0 w-32 h-40 bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden shadow-md">
                                    @if ($reservation->show->image)
                                        <img src="{{ asset('storage/' . $reservation->show->image) }}"
                                            alt="{{ $reservation->show->title }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <svg class="h-16 w-16" stroke="currentColor" fill="none"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Information -->
                                <div class="flex-1">
                                    <h4 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-3">
                                        {{ $reservation->show->title }}
                                    </h4>
                                    <p class="text-gray-600 dark:text-gray-400 mb-4 leading-relaxed">
                                        {{ $reservation->show->description }}
                                    </p>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3">
                                            <p class="text-xs text-gray-600 dark:text-gray-400 font-semibold uppercase">
                                                Date et heure</p>
                                            <p class="font-bold text-gray-900 dark:text-gray-100 mt-1">
                                                {{ \Carbon\Carbon::parse($reservation->show->show_date)->format('d/m/Y à H:i') }}
                                            </p>
                                        </div>
                                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3">
                                            <p class="text-xs text-gray-600 dark:text-gray-400 font-semibold uppercase">
                                                Durée</p>
                                            <p class="font-bold text-gray-900 dark:text-gray-100 mt-1">
                                                {{ $reservation->show->duration }} minutes
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reservation details -->
                    <div
                        class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-xl border border-gray-100 dark:border-gray-700">
                        <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">📊 Détails de la réservation
                            </h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div
                                class="flex justify-between items-center pb-4 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-gray-700 dark:text-gray-300 font-semibold">Nombre de billets:</span>
                                <span
                                    class="inline-flex items-center justify-center w-10 h-10 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full font-bold text-lg">{{ $reservation->quantity }}</span>
                            </div>

                            <div
                                class="flex justify-between items-center pb-4 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-gray-700 dark:text-gray-300 font-semibold">Prix unitaire:</span>
                                <span
                                    class="font-bold text-gray-900 dark:text-gray-100">{{ number_format($reservation->show->price, 2, ',', ' ') }}
                                    €</span>
                            </div>

                            <div
                                class="flex justify-between items-center bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-lg p-4 border-2 border-green-600 dark:border-green-500">
                                <span class="text-lg font-bold text-gray-900 dark:text-gray-100">Montant total:</span>
                                <span
                                    class="text-2xl font-bold text-green-600 dark:text-green-400">{{ number_format($reservation->amount, 2, ',', ' ') }}
                                    €</span>
                            </div>
                        </div>
                    </div>

                    <!-- Client information -->
                    <div
                        class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-xl border border-gray-100 dark:border-gray-700">
                        <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">👤 Informations du client
                            </h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                <p class="text-sm text-gray-600 dark:text-gray-400 font-semibold uppercase">Nom complet
                                </p>
                                <p class="font-bold text-gray-900 dark:text-gray-100 mt-1">
                                    {{ $reservation->user->firstname }} {{ $reservation->user->lastname }}</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                <p class="text-sm text-gray-600 dark:text-gray-400 font-semibold uppercase">Email</p>
                                <p class="font-semibold text-gray-900 dark:text-gray-100 mt-1">
                                    {{ $reservation->user->email }}</p>
                            </div>
                            @if ($reservation->user->phone_number)
                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 font-semibold uppercase">
                                        Téléphone</p>
                                    <p class="font-semibold text-gray-900 dark:text-gray-100 mt-1">
                                        {{ $reservation->user->phone_number }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Side panel -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Status -->
                    <div
                        class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-xl border border-gray-100 dark:border-gray-700">
                        <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Statut</h3>
                        </div>
                        <div class="p-6">
                            @if (\Carbon\Carbon::parse($reservation->show->show_date)->isFuture())
                                <div
                                    class="bg-emerald-100 dark:bg-emerald-900/20 border-2 border-emerald-400 dark:border-emerald-600 rounded-lg p-5">
                                    <p class="flex items-center text-emerald-800 dark:text-emerald-200 font-semibold">
                                        <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Confirmée
                                    </p>
                                </div>
                            @else
                                <div
                                    class="bg-gray-100 dark:bg-gray-700 border-2 border-gray-400 dark:border-gray-600 rounded-lg p-5">
                                    <p class="flex items-center text-gray-800 dark:text-gray-200 font-semibold">
                                        <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Spectacle passé
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div
                        class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-xl border border-gray-100 dark:border-gray-700">
                        <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Actions</h3>
                        </div>
                        <div class="p-6 space-y-3">
                            <a href="{{ route('shows.show', $reservation->show->id) }}"
                                class="w-full text-center px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-bold rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-lg hover:shadow-xl block">
                                Voir le spectacle
                            </a>

                            @if (\Carbon\Carbon::parse($reservation->show->show_date)->isFuture())
                                <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-full px-4 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white font-bold rounded-lg hover:from-red-700 hover:to-red-800 transition-all duration-200 shadow-lg hover:shadow-xl"
                                        onclick="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')">
                                        Annuler
                                    </button>
                                </form>
                            @endif

                            <a href="{{ route('reservations.history') }}"
                                class="w-full text-center px-4 py-3 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-bold rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-200 block">
                                Retour
                            </a>
                        </div>
                    </div>

                    <!-- Security tip -->
                    <div
                        class="bg-blue-50 dark:bg-blue-900/20 border-2 border-blue-200 dark:border-blue-800 rounded-lg p-5">
                        <p class="text-sm text-blue-800 dark:text-blue-200 flex items-start gap-3">
                            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span><strong>Info :</strong> Vos billets ont été envoyés par email. Conservez-les en lieu
                                sûr.</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
