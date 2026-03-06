<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-2xl border border-gray-200 dark:border-gray-700 p-12 text-center">
                <div class="mb-6 flex justify-center">
                    <div class="rounded-full bg-green-100 dark:bg-green-900/30 p-4">
                        <svg class="w-16 h-16 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>

                <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-4">Paiement confirmé !</h2>
                
                <p class="text-lg text-gray-600 dark:text-gray-400 mb-8">
                    Un mail de confirmation vous a été envoyé. 
                    Merci pour votre achat !
                </p>

                <div class="bg-gray-50 dark:bg-gray-900/50 rounded-xl p-6 border border-gray-100 dark:border-gray-800">
                    <p class="text-gray-600 dark:text-gray-400">
                        Vous allez être redirigé vers vos réservations dans <span id="countdown" class="font-bold text-fnac-red text-xl">5</span> secondes.
                    </p>
                </div>

                <div class="mt-8">
                    <a href="{{ route('reservations.history') }}" class="text-fnac-red hover:text-red-700 font-semibold transition-colors duration-200">
                        Cliquez ici si vous n'êtes pas redirigé automatiquement
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        let seconds = 5;
        const countdownElement = document.getElementById('countdown');
        
        const interval = setInterval(() => {
            seconds--;
            countdownElement.textContent = seconds;
            if (seconds <= 0) {
                clearInterval(interval);
                window.location.href = "{{ route('reservations.history') }}";
            }
        }, 1000);
    </script>
</x-app-layout>
