<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>amsik shop | Accès Interdit</title>
    <style>
        body {
            font-family: 'Bookman Old Style', sans-serif;
            background-color: #e9ecef;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        .container {
            background-color: #fff;
            max-width: 500px;
            margin: 100px auto;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        h1 {
            color: #d9534f;
        }

        p {
            color: #333;
        }

        .icon {
            font-size: 48px;
            color: #d9534f;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">&#9888;</div>
        <h1>Accès Interdit</h1>
        <p>Désolé, vous n'avez pas l'autorisation d'accéder à cette page.</p>
        <p><a href="javascript:history.back(../accueil.php)">Revenir en arrière</a></p>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Redirection automatique vers la page d'accueil après 5 secondes
    setTimeout(function() {
      window.location.href = 'authentification.php';
    }, 5000);
  </script>
</body>
</html>

