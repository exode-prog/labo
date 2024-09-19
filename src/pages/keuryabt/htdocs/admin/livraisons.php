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
  <title>keuryabt | Gestion des livraisons</title>
  <link rel="stylesheet" type="text/css" href="../css/webfonts/all.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
     body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
    }
    .card {
      transition: transform 0.3s;
      border: none;
      border-radius: 15px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .card-body {
      background-color: #fff;
      color: #82C91E;
      border-radius: 20px;
    }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 12px rgba(0, 0, 0, 0.1);
    }
    .header {
      background-color: #82C91E;
      padding: 20px;
      color: #fff;
      text-align: center;
      border-bottom-left-radius: 15px;
      border-bottom-right-radius: 15px;
    }
    .header h2 {
      margin-bottom: 0;
    }
    #footer {
      background-color: #343a40;
      padding: 10px;
      text-align: center;
      font-size: 12px;
      color: white;
      position: absolute;
      bottom: 0;
      width: 100%;
    }
    #header {
      background-color: #82C91E;
      padding: 10px;
      color: #fff;
      text-align: center;
    }
    
    h1{
      color: #82C91E;
    }
  }
  </style>
</head>
<body>
<div id="header">
    <h3 class="category-card">Keur Yaye Adji Bineta Thiam</h3>
  </div>
<div class="container mt-2">
  <div class="col-md-2">
        <a href="menuadmin.php">
          <button type="button" class="btn btn-primary"><i class="fa fa-chevron-left"></i> Retour</button>
        </a>
      </div>
      <h1 class="text-center">Gestion des livraisons</h1>
  <div class="row">
    <div class="col-md-6">
      <div class="card mb-4">
        <div class="card-body text-center">
          <i class="fas fa-user-plus fa-3x mb-3"></i>
          <h5 class="card-title">Ajouter livraison</h5>
          <p class="card-text">Ajoutez un nouvel livraison à votre boutique.</p>
          <a href="../formulaire/livraisons.php" class="btn btn-primary">Ajouter livraison</a>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card mb-4">
        <div class="card-body text-center">
          <i class="fas fa-users fa-3x mb-3"></i>
          <h5 class="card-title">Liste des livraisons</h5>
          <p class="card-text">Affichez la liste de tous les livraisons de votre boutique.</p>
          <a href="../affichages/livraisons.php" class="btn btn-primary">Afficher livraisons</a>
        </div>
      </div>
    </div>
  </div>
</div>
<div id="footer" class="bg-dark text-white">
    <p>© keuryabt - 2024 - Tous droits réservés  
  </div>
   <?php
 } else {
   header('Location: ../admin/pageinterdit.php');
   exit();
 }
?>
</body>
</html>