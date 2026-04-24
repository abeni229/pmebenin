<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: #f7faf4;
            border-radius: 10px;
        }
        .header {
            background: linear-gradient(135deg, #7ccf55, #f3c863);
            color: #152713;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
        }
        .content {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #7ccf55, #f3c863);
            color: #152713;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }
        .footer {
            text-align: center;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Réinitialisation de mot de passe</h1>
        </div>

        <div class="content">
            <p>Bonjour {{ $user->name }},</p>

            <p>Vous avez demandé la réinitialisation de votre mot de passe PME Bénin. Cliquez sur le lien ci-dessous pour créer un nouveau mot de passe.</p>

            <p style="text-align: center;">
                <a href="{{ $resetUrl }}" class="button">Réinitialiser mon mot de passe</a>
            </p>

            <p>Ce lien est valable pendant 2 heures. Si vous n'avez pas fait cette demande, ignorez simplement cet email.</p>

            <p>Cordialement,<br>L'équipe PME Bénin</p>
        </div>

        <div class="footer">
            <p>&copy; 2026 PME Bénin. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>
