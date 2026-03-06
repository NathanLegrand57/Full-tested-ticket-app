<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Mon panier
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Success message -->
            @if (session('success'))
                <div
                    class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-xl flex items-start gap-3 shadow-sm">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>{{ session('success') }}</span>
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
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if ($cartItems->isEmpty())
                <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-200">
                    <div class="p-12 text-center">
                        <div class="text-gray-300 dark:text-gray-600 mb-6">
                            <svg class="mx-auto h-16 w-16" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-fnac-dark mb-2">Votre panier est vide</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6 text-lg">Commencez à ajouter des billets de
                            spectacles</p>
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
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Main cart content -->
                    <div class="lg:col-span-2">
                        <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-200">
                            <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                                <h3 class="text-lg font-bold text-fnac-dark">
                                    🎫 {{ $cartItems->count() }} article{{ $cartItems->count() > 1 ? 's' : '' }}
                                </h3>
                            </div>
                            <div class="p-6 space-y-6">
                                @foreach ($cartItems as $item)
                                    <div class="flex gap-4 pb-6 border-b border-gray-100 last:pb-0 last:border-b-0">
                                        <!-- Show image -->
                                        <div
                                            class="flex-shrink-0 w-24 h-24 bg-gray-200 rounded-lg overflow-hidden shadow-md">
                                            @if ($item->show->image)
                                                <img src="{{ asset('storage/' . $item->show->image) }}"
                                                    alt="{{ $item->show->title }}" class="w-full h-full object-cover">
                                            @else
                                                <div
                                                    class="w-full h-full flex items-center justify-center text-gray-400">
                                                    <svg class="h-10 w-10" stroke="currentColor" fill="none"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Show details -->
                                        <div class="flex-1">
                                            <h4 class="text-lg font-bold text-fnac-dark">
                                                <a href="{{ route('shows.show', $item->show->id) }}"
                                                    class="hover:text-fnac-red transition">
                                                    {{ $item->show->title }}
                                                </a>
                                            </h4>
                                            <p class="text-sm text-gray-600 mt-1 flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v2a1 1 0 001 1h1v5H4a2 2 0 00-2 2v2a1 1 0 001 1h1v1a1 1 0 102 0v-1h8v1a1 1 0 102 0v-1h1a1 1 0 001-1v-2a2 2 0 00-2-2h-1V9h1a2 2 0 002-2V5a1 1 0 001-1V4a2 2 0 00-2-2h-1V3a1 1 0 00-1-1H6zm0 5v5h8V7H6z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                {{ \Carbon\Carbon::parse($item->show->show_date)->format('d/m/Y à H:i') }}
                                            </p>
                                            <div class="mt-4 flex items-center justify-between">
                                                <div class="flex items-center gap-3 bg-gray-50 rounded-lg p-2">
                                                    <span
                                                        class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Qté</span>
                                                    <form action="{{ route('cart.update', $item) }}" method="POST"
                                                        class="flex items-center gap-2">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="number" name="quantity"
                                                            value="{{ $item->quantity }}" min="1"
                                                            class="w-12 px-2 py-1 border border-gray-300 rounded text-center font-semibold focus:outline-none focus:ring-1 focus:ring-fnac-red focus:border-fnac-red">
                                                        <button type="submit"
                                                            class="px-3 py-1 text-xs font-semibold rounded-full border border-fnac-red text-fnac-red hover:bg-fnac-red hover:text-white transition-colors">MAJ</button>
                                                    </form>
                                                </div>

                                                <form action="{{ route('cart.destroy', $item) }}" method="POST"
                                                    class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="flex items-center gap-2 px-4 py-2 text-fnac-red hover:bg-red-50 rounded-lg font-semibold text-sm transition-colors"
                                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        Supprimer
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                        <!-- Prix -->
                                        <div class="flex-shrink-0 text-right">
                                            <div class="text-2xl font-extrabold text-fnac-red">
                                                {{ number_format($item->show->price * $item->quantity, 2, ',', ' ') }}
                                                €
                                            </div>
                                            <div class="text-sm text-gray-500 mt-2">
                                                <span
                                                    class="text-gray-400">{{ number_format($item->show->price, 2, ',', ' ') }}
                                                    € × {{ $item->quantity }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Continue shopping -->
                        <div class="mt-4">
                            <a href="{{ route('shows.index') }}"
                                class="flex items-center text-fnac-red hover:text-red-700 font-semibold transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                                Continuer vos achats
                            </a>
                        </div>
                    </div>

                    <!-- Order summary -->
                    <div class="lg:col-span-1">
                        <div
                            class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-200 sticky top-6">
                            <div class="p-6">
                                <h3 class="text-lg font-bold text-fnac-dark mb-6">📋 Résumé</h3>

                                <div class="space-y-4 border-b border-gray-200 pb-4 mb-6">
                                    <div class="flex justify-between text-gray-700">
                                        <span>Sous-total:</span>
                                        <span class="font-semibold">{{ number_format($total, 2, ',', ' ') }} €</span>
                                    </div>
                                    <div class="flex justify-between text-gray-700">
                                        <span>Frais de service:</span>
                                        <span class="font-semibold">{{ number_format(0, 2, ',', ' ') }} €</span>
                                    </div>
                                </div>

                                <div
                                    class="flex justify-between text-xl font-bold text-fnac-dark mb-6 p-4 bg-gray-50 rounded-lg border border-fnac-red">
                                    <span>Total:</span>
                                    <span class="text-fnac-red">{{ number_format($total, 2, ',', ' ') }} €</span>
                                </div>

                                <a href="{{ route('cart.checkout') }}"
                                    class="btn-fnac-primary w-full justify-center mb-3">
                                    Procéder au paiement
                                </a>

                                <p class="text-xs text-gray-500 text-center">
                                    🔒 Paiement sécurisé
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
