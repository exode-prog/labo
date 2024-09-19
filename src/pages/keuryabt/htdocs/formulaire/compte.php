<?php
include('../functions/functionsecure.php');
     authenticate();
if ($_SESSION['user']->typecompte == 'ADMIN') {
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentification - Service4milles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #e9ecef;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 400px;
            margin: 0 auto;
            margin-top: 50px;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
        }
        input[type="email"],
        input[type="password"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            background-color: #82C91E;
            color: #ffffff;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo img {
            width: 150px;
        }
    </style>
</head>
<body>
    <div class="col-md-2 offset-2">
        <a href="../admin/clients.php">
          <button type="button" class="btn btn-primary"><i class="fa fa-chevron-left"></i> Retour</button>
        </a>
      </div>
    <div class="container">
        
        <h2>Creer un compte</h2>
        <form action="../traitement/traitclientadmin.php" method="post">
            <div class="form-group">
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="prenom">Prenom :</label>
                <input type="text" id="prenom" name="prenom" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="prenom">Sexe :</label>
                <select class="form-control" id="sexe_input" name="sexe">
                  <option value="choix">--Choisir le sexe--</option>
                  <option value="masculin">Masculin</option>
                  <option value="feminin">Feminin</option>
              </select>
            </div>
            <div class="form-group">
                <label for="adresse">Adresse :</label>
                <input type="text" id="adresse" name="adresse" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="telephone">Telephone :</label>
                <input type="text" id="telephone" name="telephone" class="form-control" required>
            </div> 
            <div class="form-group">
                <label for="email">Email :</label>
                <input type="text" id="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password :</label>
                <input type="text" id="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Creer un compte" class="btn btn-primary" name="ok">
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../css/public/js/bootstrap.min.js"></script>
   <?php
 } else {
   header('Location: ../admin/pageinterdit.php');
   exit();
 }
?>
</body>
</html>