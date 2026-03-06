<!DOCTYPE html>
<html lang="fr" style="font-family: 'Figtree', Helvetica, Arial, sans-serif;">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de commande</title>
</head>

<body
    style="background-color: #f9fafb; color: #111827; margin: 0; padding: 40px 0; -webkit-font-smoothing: antialiased;">
    <div class="container"
        style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">

        <!-- Header avec Dégradé FNAC -->
        <div class="header"
            style="background: #dc2626; background: linear-gradient(90deg, #dc2626 0%, #b91c1c 50%, #111827 100%); padding: 40px 20px; text-align: center;">
            <h1
                style="color: #ffffff; margin: 0; font-size: 24px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">
                Confirmation de commande</h1>
        </div>

        <div class="content" style="padding: 30px;">
            <p style="font-size: 18px; font-weight: 600; margin-bottom: 10px;">Bonjour {{ $user->name }},</p>
            <p style="color: #4b5563; line-height: 1.6; margin-bottom: 30px;">
                Bonne nouvelle ! Votre paiement a été validé. Voici le récapitulatif de vos billets pour vos prochains
                spectacles :</p>

            @foreach ($reservations as $res)
                <div class="show-card"
                    style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 15px; margin-bottom: 15px;">
                    <div class="show-title"
                        style="font-weight: 700; font-size: 16px; color: #dc2626; margin-bottom: 5px;">
                        {{ $res->show->title }}
                    </div>
                    <div class="show-details" style="font-size: 14px; color: #6b7280;">
                        {{ $res->show->show_date->format('d/m/Y à H:i') }}<br>
                        {{ $res->quantity }} place{{ $res->quantity > 1 ? 's' : '' }}<br>
                        {{ number_format($res->amount, 2, ',', ' ') }} €
                    </div>
                </div>
            @endforeach

            <div class="summary"
                style="background-color: #f3f4f6; padding: 20px; border-radius: 8px; margin-top: 30px;">
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td
                            style="font-size: 18px; font-weight: 800; color: #111827; border-top: 2px solid #e5e7eb; padding-top: 10px;">
                            Montant Total
                        </td>
                        <td align="right"
                            style="font-size: 18px; font-weight: 800; color: #111827; border-top: 2px solid #e5e7eb; padding-top: 10px;">
                            {{ number_format($reservations->sum('amount'), 2, ',', ' ') }} €
                        </td>
                    </tr>
                </table>
            </div>

            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ url('/reservations') }}"
                    style="display: inline-block; background-color: #dc2626; color: #ffffff; padding: 12px 25px; border-radius: 9999px; text-decoration: none; font-weight: 600;">
                    Voir mes réservations</a>
            </div>
        </div>

        <div class="footer" style="text-align: center; padding: 20px; font-size: 12px; color: #9ca3af;">
            &copy; {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.<br>
            Ceci est un message automatique, merci de ne pas y répondre.
        </div>
    </div>
</body>

</html>
