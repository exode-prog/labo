<?php
include('../functions/functionsecure.php');
     authenticate();
if ($_SESSION['user']->typecompte == 'GERENTE') {
require_once("../bd/connexion.php");
$stmtProduits = $connexion->prepare("SELECT COUNT(*) AS totalProduits FROM produits");
$stmtProduits->execute();
$nombreProduits = $stmtProduits->fetch(PDO::FETCH_ASSOC);
$stmtUtilisateurs = $connexion->prepare("SELECT COUNT(*) AS totalUtilisateurs FROM utilisateurs");
$stmtUtilisateurs->execute();
$nombreUtilisateurs = $stmtUtilisateurs->fetch(PDO::FETCH_ASSOC);

$stmtClients = $connexion->prepare("SELECT COUNT(*) AS totalClients FROM clients");
$stmtClients->execute();
$nombreClients = $stmtClients->fetch(PDO::FETCH_ASSOC);

$stmtCategories = $connexion->prepare("SELECT COUNT(*) AS totalCategories FROM categories");
$stmtCategories->execute();
$nombreCategories = $stmtCategories->fetch(PDO::FETCH_ASSOC);

// Récupérer le nombre de commandes
// $stmtCommandes = $connexion->prepare("SELECT COUNT(*) AS totalCommandes FROM commandes");
// $stmtCommandes->execute();
// $nombreCommandes = $stmtCommandes->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>keuryabt | Tableau de Bord</title>
   <link rel="stylesheet" type="text/css" href="../css/webfonts/all.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Montserrat', sans-serif;
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
  </style>
</head>
<body>
<div id="header">
    <h3 class="category-card">KEURYABT</h3>
  </div>
<div class="container mt-2">
  <div class="col-md-2">
        <a href="menugerente.php">
          <button type="button" class="btn btn-dark"><i class="fa fa-chevron-left"></i> Retour</button>
        </a>
      </div>
   <h1 class="text-center">Tableau de bord</h1>
  <div class="row">
    <div class="col-md-4">
      <div class="card mb-4">
        <div class="card-body text-center">
          <i class="fas fa-users fa-3x mb-3"></i>
          <h5 class="card-title">Utilisateurs</h5>
          <p class="card-text">Nombre total d'utilisateurs : <strong><?php echo $nombreUtilisateurs['totalUtilisateurs']; ?></strong>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card mb-4">
        <div class="card-body text-center">
          <i class="fas fa-tags fa-3x mb-3"></i>
          <h5 class="card-title">Catégories</h5>
          <p class="card-text">Nombre total de catégories : <strong><?php echo $nombreCategories['totalCategories']; ?></strong></p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card mb-4">
        <div class="card-body text-center">
          <i class="fas fa-box-open fa-3x mb-3"></i>
          <h5 class="card-title">Produits</h5>
          <p class="card-text">Nombre total de produits : <strong><?php echo $nombreProduits['totalProduits']; ?></strong></p>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-4">
      <div class="card mb-4">
        <div class="card-body text-center">
          <i class="fas fa-user-plus fa-3x mb-3"></i>
          <h5 class="card-title">Clients</h5>
          <p class="card-text">Nombre total de clients : <strong><?php echo $nombreClients['totalClients']; ?></strong></p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card mb-4">
        <div class="card-body text-center">
          <i class="fas fa-shopping-cart fa-3x mb-3"></i>
          <h5 class="card-title">Commandes</h5>
          <p class="card-text">Nombre total de commandes : <strong>2000</strong></p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card mb-4">
        <div class="card-body text-center">
          <i class="fas fa-chart-line fa-3x mb-3"></i>
          <h5 class="card-title">Statistiques</h5>
          <p class="card-text">Visualisez les statistiques détaillées.</p>
          <a href="#" class="btn btn-primary">Voir les statistiques</a>
        </div>
      </div>
    </div>
  </div>
</div>
<div id="footer" class="bg-dark text-white">
    <p>© Keuryabt - 2024 - Tous droits réservés  
  </div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" charset="utf-8"></script>
  <script src="../css/public/js/bootstrap.min.js"></script>
 <script src="../css/loader.js"></script>
   <?php
 } else {
   header('Location: ../admin/pageinterdit.php');
   exit();
 }
?>
 </body>
</html>