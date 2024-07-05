<html>
    <head>
        <title>Reset Password</title>
    </head>

    <body>
        <p>Click the link below to reset your password:</p>

        <a href="http://localhost:3000/password-reset?token=<?= esc($token)?>">  <?= esc($token)?></a>

        <p>If you did not request to reset password, please ignore this email.</p>
    </body>
</html>