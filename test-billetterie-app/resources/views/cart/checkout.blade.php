<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Paiement
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Formulaire de paiement -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-200">
                        <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Informations de paiement</h3>
                        </div>

                        <form action="{{ route('cart.process-payment') }}" method="POST" class="space-y-6 p-6">
                            @csrf

                            <!-- Données utilisateur -->
                            <div class="border-b border-gray-100 pb-6">
                                <h4 class="text-sm font-bold text-fnac-dark mb-4 uppercase tracking-[0.2em]"> Vos
                                    informations</h4>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Prénom
                                        </label>
                                        <input type="text" value="{{ $user->firstname }}" disabled
                                            class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 font-semibold">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Nom
                                        </label>
                                        <input type="text" value="{{ $user->lastname }}" disabled
                                            class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 font-semibold">
                                    </div>

                                    <div class="col-span-2">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Email
                                        </label>
                                        <input type="email" value="{{ $user->email }}" disabled
                                            class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 font-semibold">
                                    </div>

                                    <div class="col-span-2">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Téléphone
                                        </label>
                                        <input type="tel" value="{{ $user->phone_number ?? 'Non renseigné' }}"
                                            disabled
                                            class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 font-semibold">
                                    </div>
                                </div>
                            </div>

                            <!-- Méthode de paiement -->
                            <div>
                                <h4 class="text-sm font-bold text-fnac-dark mb-4 uppercase tracking-[0.2em]"> Méthode de
                                    paiement</h4>

                                <div class="space-y-3">
                                    <!-- Carte bancaire -->
                                    <div class="relative">
                                        <input type="radio" name="payment_method" value="card" id="card"
                                            class="peer sr-only" checked required>
                                        <label for="card"
                                            class="flex items-start p-5 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-fnac-red peer-checked:bg-red-50 transition-all duration-200 hover:border-fnac-red">
                                            <div class="mr-4 flex-shrink-0 pt-1">
                                                <svg class="w-6 h-6 text-fnac-red" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path
                                                        d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" />
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <p class="font-bold text-fnac-dark">Carte bancaire</p>
                                                <p class="text-sm text-gray-600">Visa, Mastercard, American Express</p>
                                            </div>
                                            <svg class="w-5 h-5 text-fnac-red peer-checked:block hidden"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </label>
                                    </div>

                                    <!-- Virement bancaire -->
                                    <div class="relative">
                                        <input type="radio" name="payment_method" value="bank_transfer"
                                            id="bank_transfer" class="peer sr-only">
                                        <label for="bank_transfer"
                                            class="flex items-start p-5 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-fnac-red peer-checked:bg-red-50 transition-all duration-200 hover:border-fnac-red">
                                            <div class="mr-4 flex-shrink-0 pt-1">
                                                <svg class="w-6 h-6 text-fnac-red" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path
                                                        d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.414l4 4V14a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H7a1 1 0 01-1-1v-6z" />
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <p class="font-bold text-fnac-dark">Virement bancaire</p>
                                                <p class="text-sm text-gray-600">Virement SEPA en 1-2 jours ouvrables
                                                </p>
                                            </div>
                                            <svg class="w-5 h-5 text-fnac-red peer-checked:block hidden"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </label>
                                    </div>
                                </div>

                                @error('payment_method')
                                    <p class="text-red-600 text-sm mt-2 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Conditions d'utilisation -->
                            <div class="flex items-start bg-gray-50 rounded-lg p-4">
                                <input type="checkbox" id="terms"
                                    class="mt-1 rounded w-4 h-4 border-gray-300 text-fnac-red focus:ring-fnac-red"
                                    required>
                                <label for="terms" class="ml-3 text-sm text-gray-700">
                                    J'accepte les <a href="#"
                                        class="font-semibold text-fnac-red hover:text-red-700">conditions
                                        d'utilisation</a> et la <a href="#"
                                        class="font-semibold text-fnac-red hover:text-red-700">politique de
                                        confidentialité</a>
                                </label>
                            </div>

                            <!-- Boutons -->
                            <div class="flex gap-4 pt-4 border-t border-gray-100">
                                <a href="{{ route('cart.index') }}"
                                    class="flex-1 text-center px-6 py-3 border-2 border-gray-300 text-gray-700 font-bold rounded-full hover:bg-gray-100 transition duration-200">
                                    Retour
                                </a>
                                <button type="submit" class="btn-fnac-primary flex-1 justify-center">
                                    Confirmer le paiement
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Informations de sécurité -->
                    <div class="mt-6 bg-red-50 border border-fnac-red rounded-lg p-6">
                        <p class="flex items-start text-sm text-fnac-dark">
                            <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span><strong>Sécurité garantie :</strong> Vos données de paiement sont chiffrées et nous ne
                                conservons jamais vos informations bancaires.</span>
                        </p>
                    </div>
                </div>

                <!-- Résumé de commande -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-200 sticky top-6">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-fnac-dark mb-6">Résumé de commande</h3>

                            <div class="space-y-3 mb-6 max-h-80 overflow-y-auto">
                                @foreach ($cartItems as $item)
                                    <div
                                        class="flex justify-between text-sm text-gray-700 border-b border-gray-200 pb-3 last:border-b-0">
                                        <div>
                                            <p class="font-bold text-fnac-dark">{{ $item->show->title }}</p>
                                            <p class="text-xs text-gray-500">Qty: {{ $item->quantity }}</p>
                                        </div>
                                        <span
                                            class="font-bold whitespace-nowrap">{{ number_format($item->show->price * $item->quantity, 2, ',', ' ') }}
                                            €</span>
                                    </div>
                                @endforeach
                            </div>

                            <div class="border-t border-gray-200 pt-4 space-y-3">
                                <div class="flex justify-between text-gray-700">
                                    <span>Sous-total:</span>
                                    <span class="font-semibold">{{ number_format($total, 2, ',', ' ') }} €</span>
                                </div>
                                <div class="flex justify-between text-gray-700">
                                    <span>Frais de service:</span>
                                    <span class="font-semibold">{{ number_format(0, 2, ',', ' ') }} €</span>
                                </div>
                                <div
                                    class="flex justify-between text-xl font-bold text-fnac-dark bg-gray-50 rounded-lg p-4 border border-fnac-red">
                                    <span>TOTAL:</span>
                                    <span class="text-fnac-red">{{ number_format($total, 2, ',', ' ') }} €</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
