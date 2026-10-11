<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Confirmation de réservation</title>
</head>
<body style="margin:0; padding:0; background:#eef2f7; font-family:Segoe UI, Arial, sans-serif; color:#1f2937;">
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background:#eef2f7; padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width:560px; background:#ffffff; border-radius:14px; overflow:hidden; box-shadow:0 4px 20px rgba(15,23,42,0.08);">
                    <tr>
                        <td style="background:linear-gradient(135deg,#c0392b 0%, #96281b 100%); padding:30px 32px;">
                            <div style="font-size:22px; font-weight:700; color:#ffffff;">Réservation reçue ✓</div>
                            <div style="font-size:13px; color:rgba(255,255,255,0.75); margin-top:6px;">Votre demande a été bien enregistrée.</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px 32px 20px; background:#fdfdfd;">
                            <p style="margin:0 0 16px; font-size:15px; line-height:1.8; color:#374151;">
                                Bonjour <strong>{{ $nom }}</strong>,<br><br>
                                Nous avons bien reçu votre demande de réservation.
                                Un conseiller vous recontactera prochainement pour confirmer les détails de la location.
                            </p>

                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background:#ffffff; border:1px solid #e5e7eb; border-radius:10px; margin:22px 0;">
                                <tr>
                                    <td style="padding:18px 20px; font-size:13px; line-height:1.8;">
                                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                                            <tr>
                                                <td style="color:#6b7280; width:120px; padding:6px 0;">Véhicule</td>
                                                <td style="padding:6px 0; font-weight:600; color:#111827;">{{ $vehicule ?? 'Non précisé' }}</td>
                                            </tr>
                                            <tr>
                                                <td style="color:#6b7280; width:120px; padding:6px 0;">Dates</td>
                                                <td style="padding:6px 0; font-weight:600; color:#111827;">{{ $dateDebut }} au {{ $dateFin }}</td>
                                            </tr>
                                            <tr>
                                                <td style="color:#6b7280; width:120px; padding:6px 0;">Lieu</td>
                                                <td style="padding:6px 0; font-weight:600; color:#111827;">{{ $lieuPrise ?? 'Non précisé' }}</td>
                                            </tr>
                                            <tr>
                                                <td style="color:#6b7280; width:120px; padding:6px 0;">Téléphone</td>
                                                <td style="padding:6px 0; font-weight:600; color:#111827;">{{ $telephone }}</td>
                                            </tr>
                                            @if(!empty($email))
                                                <tr>
                                                    <td style="color:#6b7280; width:120px; padding:6px 0;">Email</td>
                                                    <td style="padding:6px 0; font-weight:600; color:#111827;">{{ $email }}</td>
                                                </tr>
                                            @endif
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <div style="background:#f0fdf4; border-left:4px solid #22c55e; border-radius:0 8px 8px 0; padding:14px 16px; color:#166534; font-size:12.5px; line-height:1.6; margin:18px 0;">
                                ✅ Merci pour votre confiance. Votre demande est bien prise en compte dans notre système.
                            </div>

                            <p style="margin:18px 0 0; font-size:13px; color:#6b7280; line-height:1.8;">
                                Une question ? Contactez-nous à
                                <a href="mailto:{{ MAIL_CONTACT }}" style="color:#c0392b; text-decoration:none;">{{ MAIL_CONTACT }}</a>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background:#e8edf5; padding:18px 20px; font-size:11.5px; color:#6b7280; text-align:center; border-top:1px solid #dfe6f0;">
                            © {{ date('Y') }} {{ APP_NAME }} · Tous droits réservés
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
