<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Confirmation du Paiement') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-2xl border border-gray-200 dark:border-gray-700 p-8">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Finalisez votre achat par carte</h3>

                <form id="payment-form" class="space-y-6">
                    <div id="payment-element"
                        class="p-4 border border-gray-100 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900/50">
                        <!-- Stripe Elements will be inserted here -->
                    </div>

                    <button id="submit"
                        class="w-full bg-fnac-red text-white font-bold py-4 px-6 rounded-xl hover:bg-red-700 transition duration-200 shadow-lg flex items-center justify-center">
                        <span id="button-text">Payer maintenant</span>
                        <div id="spinner"
                            class="hidden animate-spin h-6 w-6 border-2 border-white border-t-transparent rounded-full">
                        </div>
                    </button>

                    <div id="payment-message"
                        class="hidden p-4 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 text-sm font-semibold text-center mt-4 border border-red-100 dark:border-red-800">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe("{{ $stripeKey }}");
        const clientSecret = "{{ $clientSecret }}";
        const orderId = "{{ $orderId }}";

        const elements = stripe.elements({
            clientSecret
        });
        const paymentElement = elements.create("payment");
        paymentElement.mount("#payment-element");

        const form = document.querySelector("#payment-form");
        const submitBtn = document.querySelector("#submit");
        const spinner = document.querySelector("#spinner");
        const buttonText = document.querySelector("#button-text");
        const messageContainer = document.querySelector("#payment-message");

        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            setLoading(true);

            const {
                error,
                paymentIntent
            } = await stripe.confirmPayment({
                elements,
                redirect: "if_required",
            });

            if (error) {
                messageContainer.textContent = error.message;
                messageContainer.classList.remove("hidden");
                setLoading(false);
            } else if (paymentIntent && paymentIntent.status === "succeeded") {
                // Inform Laravel that payment succeeded
                try {
                    const response = await fetch("{{ route('cart.payment-success') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            order_id: orderId
                        })
                    });

                    if (response.ok) {
                        window.location.href = "{{ route('cart.success') }}";
                    } else {
                        messageContainer.textContent =
                            "Erreur lors de la validation de votre réservation. Veuillez contacter le support.";
                        messageContainer.classList.remove("hidden");
                        setLoading(false);
                    }
                } catch (err) {
                    console.error("Error during payment validation:", err);
                    messageContainer.textContent =
                        "Erreur lors de la validation de votre réservation. Veuillez contacter le support.";
                    messageContainer.classList.remove("hidden");
                    setLoading(false);
                }
            }
        });

        function setLoading(isLoading) {
            if (isLoading) {
                submitBtn.disabled = true;
                spinner.classList.remove("hidden");
                buttonText.classList.add("hidden");
            } else {
                submitBtn.disabled = false;
                spinner.classList.add("hidden");
                buttonText.classList.remove("hidden");
            }
        }
    </script>
</x-app-layout>
