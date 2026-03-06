<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ $show->title }}
            </h2>
            <a href="{{ route('shows.index') }}"
                class="btn-fnac-secondary bg-white/10 border-white text-white hover:bg-white/20">
                Retour aux spectacles
            </a>
        </div>
    </x-slot>

    <div class="pb-32 sm:pb-24">
        <div class="w-full h-[40vh] sm:h-[50vh] bg-fnac-dark overflow-hidden flex items-center justify-center relative shadow-inner">
            @if ($show->image)
                <div class="absolute inset-0 opacity-20 select-none pointer-events-none">
                    <img src="{{ asset('storage/' . $show->image) }}" alt=""
                        class="w-full h-full object-cover blur-2xl">
                </div>
                <img src="{{ asset('storage/' . $show->image) }}" alt="{{ $show->title }}"
                    class="relative z-10 h-full w-auto object-contain">
            @else
                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                    <span class="text-gray-400 text-sm uppercase tracking-wide">Aucune image</span>
                </div>
            @endif
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
            {{-- Title --}}
            <h1 class="text-3xl sm:text-4xl font-extrabold text-fnac-dark mb-4">
                {{ $show->title }}
            </h1>

            <div class="card-fnac p-6 mb-6">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-fnac-red flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v2a1 1 0 001 1h1v5H4a2 2 0 00-2 2v2a1 1 0 001 1h1v1a1 1 0 102 0v-1h8v1a1 1 0 102 0v-1h1a1 1 0 001-1v-2a2 2 0 00-2-2h-1V9h1a2 2 0 002-2V5a1 1 0 001-1V4a2 2 0 00-2-2h-1V3a1 1 0 00-1-1H6zm0 5v5h8V7H6z"
                                clip-rule="evenodd" />
                        </svg>
                        <div>
                            <span class="text-gray-500 block text-xs uppercase">Date</span>
                            <span class="font-semibold text-fnac-dark">
                                {{ $show->show_date->format('d/m/Y à H:i') }}
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z"
                                clip-rule="evenodd" />
                        </svg>
                        <div>
                            <span class="text-gray-500 block text-xs uppercase">Durée</span>
                            <span class="font-semibold text-fnac-dark">
                                {{ $show->duration }} minutes
                            </span>
                        </div>
                    </div>
                    @if (isset($show->places_disponibles))
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                    clip-rule="evenodd" />
                            </svg>
                            <div>
                                <span class="text-gray-500 block text-xs uppercase">Places</span>
                                <span class="badge-fnac-round text-xs">
                                    {{ $show->places_disponibles }} disponibles
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Description --}}
            <div class="card-fnac p-6 mb-6">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-[0.2em] mb-4">
                    Description
                </h3>
                <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">
                    {{ $show->description }}
                </p>
            </div>

            <div class="text-xs text-gray-400 mb-8">
                Créé le {{ $show->created_at->format('d/m/Y H:i') }}
                @if ($show->updated_at != $show->created_at)
                    · Modifié le {{ $show->updated_at->format('d/m/Y H:i') }}
                @endif
            </div>

            @can('show-edit')
                <div class="flex gap-3 mb-8">
                    <a href="{{ route('shows.edit', $show) }}" class="btn-fnac-secondary flex-1 justify-center">
                        Modifier
                    </a>
                    <form action="{{ route('shows.destroy', $show) }}" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center px-4 py-2 rounded-full font-semibold bg-red-50 text-fnac-red hover:bg-red-100 transition"
                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce spectacle ?')">
                            Supprimer
                        </button>
                    </form>
                </div>
            @endcan
        </div>
    </div>

    {{-- Sticky bar at bottom for price/quantity/reservation --}}
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 shadow-lg z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-col sm:flex-row items-center gap-4">
                {{-- Price --}}
                <div class="flex-1 text-center sm:text-left">
                    <div class="text-xs text-gray-500 uppercase mb-1">Prix unitaire</div>
                    <div class="text-3xl font-extrabold text-fnac-red">
                        {{ number_format($show->price, 2, ',', ' ') }} €
                    </div>
                </div>

                {{-- Reservation form --}}
                @auth
                    <form action="{{ route('cart.store') }}" method="POST"
                        class="flex-1 w-full sm:w-auto flex flex-col sm:flex-row items-center gap-3">
                        @csrf
                        <input type="hidden" name="show_id" value="{{ $show->id }}">

                        <div class="flex-1 w-full sm:w-auto">
                            <label for="quantity" class="sr-only">Nombre de billets</label>
                            <input type="number" name="quantity" id="quantity" value="1" min="1"
                                max="10"
                                class="w-full sm:w-24 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-fnac-red focus:border-fnac-red text-center">
                        </div>

                        <button type="submit" class="btn-fnac-primary w-full sm:w-auto justify-center whitespace-nowrap">
                            🛒 Ajouter au panier
                        </button>
                    </form>
                @else
                    <div class="flex-1 text-center sm:text-right">
                        <a href="{{ route('login') }}" class="btn-fnac-primary whitespace-nowrap">
                            Se connecter pour réserver
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</x-app-layout>
