<x-app-layout>
    <x-slot name="header">
        <div class="py-8 sm:py-12 flex flex-col items-center justify-center gap-4">
            {{-- Centered logo --}}
            <div class="flex items-center justify-center">
                <img src="{{ Vite::asset('resources/img/Logo_App.png') }}" alt="Logo application"
                    class="h-20 sm:h-28 md:h-32 w-auto drop-shadow-lg">
            </div>
            {{-- Centered main title --}}
            <div class="text-center">
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold tracking-tight text-white">
                    Billetterie Spectacles
                </h1>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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

            {{-- Search bar --}}
            <div class="mb-6">
                <form action="{{ route('shows.index') }}" method="GET" class="max-w-2xl mx-auto">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Rechercher un spectacle..."
                            class="block w-full pl-12 {{ request('search') ? 'pr-12' : 'pr-4' }} py-3 border border-gray-300 rounded-xl bg-white text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm transition-shadow">
                        @if (request('search'))
                            <a href="{{ route('shows.index') }}"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            @can('show-create')
                <div class="inline-flex mb-6 w-full sm:justify-end">
                    <div>
                        <a href="{{ route('shows.create') }}" class="btn-fnac-primary px-8 py-3">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Créer un spectacle
                        </a>
                    </div>
                </div>
            @endcan

            <div class="bg-white rounded-2xl shadow-lg border border-gray-200">
                <div class="p-6 sm:p-8">
                    @if ($shows->count() > 0)
                        <div class="mb-6 flex items-center justify-between">
                            <p class="text-sm text-gray-500">
                                @if (request('search'))
                                    {{ $shows->total() }} résultat{{ $shows->total() > 1 ? 's' : '' }} pour
                                    "{{ request('search') }}"
                                @else
                                    {{ $shows->total() }} spectacle{{ $shows->total() > 1 ? 's' : '' }} au catalogue
                                @endif
                            </p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($shows as $show)
                                <article class="card-fnac flex flex-col overflow-hidden group">
                                    <div class="relative h-48 bg-gray-200 overflow-hidden rounded-t-2xl">
                                        @if ($show->image)
                                            <img src="{{ asset('storage/' . $show->image) }}"
                                                alt="{{ $show->title }}"
                                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                                loading="lazy">
                                            {{-- Subtle hover overlay for image --}}
                                            <div
                                                class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            </div>
                                        @else
                                            {{-- Placeholder with FNAC gradient and show title --}}
                                            <div
                                                class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-red-600 via-red-700 to-gray-900 relative overflow-hidden">
                                                {{-- Decorative pattern in background --}}
                                                <div class="absolute inset-0 opacity-10">
                                                    <div class="absolute top-0 left-0 w-full h-full"
                                                        style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 24px 24px;">
                                                    </div>
                                                </div>
                                                {{-- Show icon --}}
                                                <div class="relative z-10 mb-3">
                                                    <svg class="w-16 h-16 text-white/80" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.5"
                                                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                                {{-- Centered show title --}}
                                                <div class="relative z-10 px-4 text-center">
                                                    <h4
                                                        class="text-white font-bold text-sm sm:text-base line-clamp-2 drop-shadow-lg">
                                                        {{ $show->title }}
                                                    </h4>
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Favorite button --}}
                                        <div class="absolute top-3 left-3 z-20">
                                            <button type="button"
                                                class="favorite-btn p-2 rounded-full bg-white/90 backdrop-blur-sm text-gray-400 hover:text-red-500 transition-all shadow-md hover:shadow-lg"
                                                data-show-id="{{ $show->id }}"
                                                data-favorited="{{ $show->isFavorited() ? 'true' : 'false' }}"
                                                @auth
onclick="toggleFavorite({{ $show->id }})"
                                                @else
                                                    onclick="window.location.href='{{ route('login') }}'" @endauth>
                                                <svg class="w-6 h-6 {{ $show->isFavorited() ? 'fill-red-500' : 'fill-transparent' }}"
                                                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                                                    fill="none">
                                                    <path
                                                        d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>

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
                                                <svg class="w-4 h-4 text-fnac-red flex-shrink-0" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v2a1 1 0 001 1h1v5H4a2 2 0 00-2 2v2a1 1 0 001 1h1v1a1 1 0 102 0v-1h8v1a1 1 0 102 0v-1h1a1 1 0 001-1v-2a2 2 0 00-2-2h-1V9h1a2 2 0 002-2V5a1 1 0 001-1V4a2 2 0 00-2-2h-1V3a1 1 0 00-1-1H6zm0 5v5h8V7H6z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <span class="font-semibold">
                                                    {{ $show->show_date->format('d/m/Y H:i') }}
                                                </span>
                                            </div>

                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="currentColor"
                                                    viewBox="0 0 20 20">
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
                                            <button type="button"
                                                onclick="openDescriptionModal({{ $show->id }})"
                                                class="text-xs text-fnac-red hover:text-fnac-dark font-semibold mt-2 flex items-center gap-1 transition-colors">
                                                Voir la description complète
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </button>
                                        </div>

                                        <div class="mt-2 flex items-center justify-between">
                                            <span class="text-2xl font-extrabold text-fnac-red">
                                                {{ number_format($show->price, 2, ',', ' ') }} €
                                            </span>
                                            <span class="text-xs text-gray-500 favorites-count">
                                                ❤️ {{ $show->favorites_count }}
                                                {{ $show->favorites_count <= 1 ? 'personne' : 'personnes' }} en favori
                                            </span>
                                        </div>

                                        <div class="mt-4 space-y-2">
                                            <a href="{{ route('shows.show', $show) }}"
                                                class="btn-fnac-secondary w-full justify-center border-fnac-red text-fnac-red hover:bg-fnac-red hover:text-white">
                                                Voir le détail
                                            </a>

                                            @auth
                                                <form action="{{ route('cart.store') }}" method="POST" class="w-full">
                                                    @csrf
                                                    <input type="hidden" name="show_id" value="{{ $show->id }}">
                                                    <input type="hidden" name="quantity" value="1">
                                                    <button type="submit" class="btn-fnac-primary w-full justify-center">
                                                        🛒 Ajouter au panier
                                                    </button>
                                                </form>

                                                @can('show-delete')
                                                    <div class="grid grid-cols-2 gap-2 pt-2 border-t border-gray-100 mt-3">
                                                        <a href="{{ route('shows.edit', $show) }}"
                                                            class="text-center text-xs font-semibold px-3 py-2 rounded-full border border-gray-300 text-gray-700 hover:bg-gray-100 transition">
                                                            Éditer
                                                        </a>
                                                        <form action="{{ route('shows.destroy', $show) }}" method="POST"
                                                            class="w-full">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="w-full text-center text-xs font-semibold px-3 py-2 rounded-full bg-red-50 text-fnac-red hover:bg-red-100 transition"
                                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce spectacle ?')">
                                                                Supprimer
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endcan
                                            @endauth
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <div class="mt-8">
                            {{ $shows->links() }}
                        </div>
                    @else
                        <div class="text-center py-16">
                            <svg class="mx-auto h-16 w-16 text-gray-300 mb-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <h3 class="text-2xl font-bold text-fnac-dark mb-2">
                                @if (request('search'))
                                    Aucun résultat trouvé
                                @else
                                    Aucun spectacle disponible
                                @endif
                            </h3>
                            <p class="text-gray-600 mb-6 text-lg">
                                @if (request('search'))
                                    Aucun spectacle ne correspond à votre recherche "{{ request('search') }}".
                                @else
                                    Aucun spectacle n'est disponible pour le moment.
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Modal for full description with image --}}
    <div id="descriptionModal"
        class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4"
        onclick="closeDescriptionModal(event)">
        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto"
            onclick="event.stopPropagation()">
            <div
                class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between rounded-t-2xl">
                <h3 id="modalTitle" class="text-xl font-bold text-fnac-dark"></h3>
                <button onclick="closeDescriptionModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <div id="modalImage" class="mb-6 rounded-xl overflow-hidden">
                    {{-- The image will be injected here --}}
                </div>
                <div id="modalDescription" class="text-gray-700 leading-relaxed">
                    {{-- The description will be injected here --}}
                </div>
            </div>
        </div>
    </div>

    {{-- Show data for the modal --}}
    <script>
        const showsData = {
            @foreach ($shows as $show)
                {{ $show->id }}: {
                    title: @json($show->title),
                    description: @json($show->description),
                    image: @json($show->image ? asset('storage/' . $show->image) : null),
                },
            @endforeach
        };

        function openDescriptionModal(showId) {
            const show = showsData[showId];
            if (!show) return;

            const modal = document.getElementById('descriptionModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalImage = document.getElementById('modalImage');
            const modalDescription = document.getElementById('modalDescription');

            modalTitle.textContent = show.title;
            modalDescription.textContent = show.description;

            if (show.image) {
                modalImage.innerHTML = `
                    <div class="relative w-full h-64 sm:h-96 bg-fnac-dark overflow-hidden flex items-center justify-center">
                        <div class="absolute inset-0 opacity-20 select-none pointer-events-none">
                            <img src="${show.image}" alt="" class="w-full h-full object-cover blur-2xl">
                        </div>
                        <img src="${show.image}" alt="${show.title}" class="relative z-10 h-full w-auto object-contain">
                    </div>
                `;
            } else {
                modalImage.innerHTML = `
                    <div class="w-full h-96 bg-gradient-to-br from-red-600 via-red-700 to-gray-900 flex items-center justify-center">
                        <svg class="w-24 h-24 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </div>
                `;
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeDescriptionModal(event) {
            if (event && event.target !== event.currentTarget) return;

            const modal = document.getElementById('descriptionModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }

        // Fermer avec la touche Escape
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeDescriptionModal();
            }
        });
    </script>

    @auth
        <script>
            function toggleFavorite(showId) {
                const btn = document.querySelector(`[data-show-id="${showId}"]`);
                const svg = btn.querySelector('svg');
                const isFavorited = btn.getAttribute('data-favorited') === 'true';

                // Optimistic UI update
                if (isFavorited) {
                    svg.classList.remove('fill-red-500');
                    svg.classList.add('fill-transparent');
                    btn.setAttribute('data-favorited', 'false');
                } else {
                    svg.classList.remove('fill-transparent');
                    svg.classList.add('fill-red-500');
                    btn.setAttribute('data-favorited', 'true');
                }

                // AJAX request
                fetch(`/shows/${showId}/favorite`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({})
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update button state
                            btn.setAttribute('data-favorited', data.isFavorited ? 'true' : 'false');
                            if (data.isFavorited) {
                                svg.classList.remove('fill-transparent');
                                svg.classList.add('fill-red-500');
                            } else {
                                svg.classList.remove('fill-red-500');
                                svg.classList.add('fill-transparent');
                            }

                            // Update favorites count (if element exists)
                            const countElement = btn.closest('article').querySelector('.favorites-count');
                            if (countElement) {
                                const count = data.favoritesCount;
                                countElement.textContent = `❤️ ${count} ${count <= 1 ? 'personne' : 'personnes'} en favori`;
                            }
                        } else {
                            // Revert on error
                            if (isFavorited) {
                                svg.classList.remove('fill-transparent');
                                svg.classList.add('fill-red-500');
                                btn.setAttribute('data-favorited', 'true');
                            } else {
                                svg.classList.remove('fill-red-500');
                                svg.classList.add('fill-transparent');
                                btn.setAttribute('data-favorited', 'false');
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // Revert on error
                        if (isFavorited) {
                            svg.classList.remove('fill-transparent');
                            svg.classList.add('fill-red-500');
                            btn.setAttribute('data-favorited', 'true');
                        } else {
                            svg.classList.remove('fill-red-500');
                            svg.classList.add('fill-transparent');
                            btn.setAttribute('data-favorited', 'false');
                        }
                    });
            }
        </script>
    @endauth
</x-app-layout>
