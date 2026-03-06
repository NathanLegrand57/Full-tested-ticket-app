<x-app-layout>
    <x-slot name="header">
        <div class="py-8 sm:py-12 flex flex-col items-center justify-center gap-4">
            <div class="text-center">
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold tracking-tight text-white">
                    ❤️ Mes Favoris
                </h1>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-xl flex items-start gap-3 shadow-sm">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                              clip-rule="evenodd" />
                    </svg>
                    <span class="font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-lg border border-gray-200">
                <div class="p-6 sm:p-8">
                    @if ($favoriteShows->count() > 0)
                        <div class="mb-6 flex items-center justify-between">
                            <p class="text-sm text-gray-500">
                                {{ $favoriteShows->total() }} spectacle{{ $favoriteShows->total() > 1 ? 's' : '' }} favori{{ $favoriteShows->total() > 1 ? 's' : '' }}
                            </p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($favoriteShows as $show)
                                <article class="card-fnac flex flex-col overflow-hidden group">
                                    <div class="relative h-48 bg-gray-200 overflow-hidden rounded-t-2xl">
                                        @if ($show->image)
                                            <img 
                                                src="{{ asset('storage/' . $show->image) }}" 
                                                alt="{{ $show->title }}"
                                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                                loading="lazy"
                                            >
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                        @else
                                            <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-red-600 via-red-700 to-gray-900 relative overflow-hidden">
                                                <div class="absolute inset-0 opacity-10">
                                                    <div class="absolute top-0 left-0 w-full h-full" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 24px 24px;"></div>
                                                </div>
                                                <div class="relative z-10 mb-3">
                                                    <svg class="w-16 h-16 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                              d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                                <div class="relative z-10 px-4 text-center">
                                                    <h4 class="text-white font-bold text-sm sm:text-base line-clamp-2 drop-shadow-lg">
                                                        {{ $show->title }}
                                                    </h4>
                                                </div>
                                            </div>
                                        @endif

                                        @if (isset($show->places_disponibles))
                                            <div class="absolute top-3 right-3 z-20">
                                                <span class="badge-fnac-round text-[0.7rem] shadow-lg">
                                                    {{ $show->places_disponibles }} places
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="p-5 flex-1 flex flex-col">
                                        <h3 class="text-lg font-bold text-fnac-dark mb-2 line-clamp-1">
                                            {{ $show->title }}
                                        </h3>

                                        <div class="space-y-2 text-sm text-gray-600 mb-3 flex-1">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-fnac-red flex-shrink-0"
                                                     fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                          d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v2a1 1 0 001 1h1v5H4a2 2 0 00-2 2v2a1 1 0 001 1h1v1a1 1 0 102 0v-1h8v1a1 1 0 102 0v-1h1a1 1 0 001-1v-2a2 2 0 00-2-2h-1V9h1a2 2 0 002-2V5a1 1 0 001-1V4a2 2 0 00-2-2h-1V3a1 1 0 00-1-1H6zm0 5v5h8V7H6z"
                                                          clip-rule="evenodd" />
                                                </svg>
                                                <span class="font-semibold">
                                                    {{ $show->show_date->format('d/m/Y H:i') }}
                                                </span>
                                            </div>

                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-gray-500 flex-shrink-0"
                                                     fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                          d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z"
                                                          clip-rule="evenodd" />
                                                </svg>
                                                <span class="font-medium text-gray-700">
                                                    {{ $show->duration }} min
                                                </span>
                                            </div>

                                            <p class="text-xs text-gray-700 line-clamp-2 mt-1">
                                                {{ Str::limit($show->description, 80) }}
                                            </p>
                                        </div>

                                        <div class="mt-2 flex items-center justify-between">
                                            <span class="text-2xl font-extrabold text-fnac-red">
                                                {{ number_format($show->price, 2, ',', ' ') }} €
                                            </span>
                                        </div>

                                        <div class="mt-4 space-y-2">
                                            <a href="{{ route('shows.show', $show) }}"
                                               class="btn-fnac-secondary w-full justify-center border-fnac-red text-fnac-red hover:bg-fnac-red hover:text-white">
                                                Voir le détail
                                            </a>

                                            <form action="{{ route('shows.favorite', $show) }}" method="POST" class="w-full">
                                                @csrf
                                                <button type="submit"
                                                        class="w-full inline-flex items-center justify-center px-4 py-2 rounded-full font-semibold bg-red-50 text-fnac-red hover:bg-red-100 border border-red-200 transition">
                                                    ❤️ Retirer des favoris
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <div class="mt-8">
                            {{ $favoriteShows->links() }}
                        </div>
                    @else
                        <div class="text-center py-16">
                            <svg class="mx-auto h-16 w-16 text-gray-300 mb-6" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            <h3 class="text-2xl font-bold text-fnac-dark mb-2">
                                Aucun favori
                            </h3>
                            <p class="text-gray-600 mb-6 text-lg">
                                Vous n'avez pas encore ajouté de spectacle à vos favoris.
                            </p>
                            <a href="{{ route('shows.index') }}" class="btn-fnac-primary px-8 py-3">
                                Découvrir les spectacles
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
