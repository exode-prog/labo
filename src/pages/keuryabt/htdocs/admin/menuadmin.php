<?php
include('../functions/functionsecure.php');
     authenticate();
if ($_SESSION['user']->typecompte == 'ADMIN') {
?>
<!DOCTYPE html>
<html>
<head>
  <title>keuryabt | Admin</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="../css/webfonts/all.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="shortcut icon" type="image/png" href="../css/image/WhatsApp Image 2024-05-19 at 11.25.42 (1).jpeg">
  <style>
     body {
      font-family: 'Poppins', sans-serif;
      position: relative; 
      min-height: 100vh;
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
      transform: translateY(-5px); /* Effet de lévitation */
      box-shadow: 0 8px 12px rgba(0, 0, 0, 0.1);
    }
    #header {
      background-color: #82C91E;
      padding: 10px;
      color: #fff;
      font-family: 'Poppins', sans-serif;
      text-align: center;
    }
    .header-title {
      text-align: center;
      margin-top: 20px;
      margin-bottom: 20px;
      color: #82C91E;
    }
    .category-card {
      transition: transform 0.3s ease-in-out;
    }
    .category-card:hover {
      transform: scale(1.1);
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
  </style>
</head>
<body>
  <div id="header">
    <h3 class="category-card">Keur Yaye Adji Bineta Thiam</h3>
  </div>
  <nav class="navbar navbar-expand-lg navbar-light bg-light justify-content-center">
    <ul class="navbar-nav text-center">
      <li class="nav-item">
        <a class="nav-link text-dark category-card" href="paiement.php"><i class="fas fa-money-check-alt"></i>&nbspPAIEMENTS</a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-dark category-card" href="livraisons.php"><i class="fas fa-chart-line"></i>&nbspLIVRAISONS</a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-dark category-card" href="statistique.php"><i class="fas fa-chart-line"></i>&nbspTABLEAU DE BORD</a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-dark category-card" href="../pages/parametres.php"><i class="fas fa-cog"></i>&nbspPARAMÈTRES</a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-danger category-card" href="logout.php"><i class="fas fa-sign-out-alt"></i>&nbspDÉCONNEXION</a>
      </li>
    </ul>
  </nav>
  <div class="container">
    <h4 class="header-title category-card">Bienvenue : <?= htmlspecialchars($_SESSION['user']->prenomu); ?> <?= htmlspecialchars($_SESSION['user']->nomu); ?></h4>
    <div class="row">
      <div class="col-md-4">
        <div class="card mb-4">
          <a href="utilisateurs.php" class="text-decoration-none">
            <div class="card-body text-center">
              <i class="fa fa-user-plus fa-2x mb-3"></i>
              <h5 class="card-title">UTILISATEURS</h5>
            </div>
          </a>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card mb-4">
          <a href="categories.php" class="text-decoration-none">
            <div class="card-body text-center">
              <i class="fas fa-tags fa-2x mb-3"></i>
              <h5 class="card-title">CATEGORIES</h5>
            </div>
          </a>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card mb-4">
          <a href="produits.php" class="text-decoration-none">
            <div class="card-body text-center">
              <i class="fas fa-box-open fa-2x mb-3"></i>
              <h5 class="card-title">PRODUITS</h5>
            </div>
          </a>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <div class="card mb-4">
          <a href="clients.php" class="text-decoration-none">
            <div class="card-body text-center">
              <i class="fa fa-users  fa-2x mb-3"></i>
              <h5 class="card-title">CLIENTS</h5>
            </div>
          </a>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card mb-4">
          <a href="confirmationcommande.php" class="text-decoration-none">
            <div class="card-body text-center">
               <i class="fas fa-shopping-cart fa-2x mb-3"></i>
               <h5 class="card-title">COMMANDES</h5>
            </div>
          </a>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card mb-4">
           <a href="promotions.php" class="text-decoration-none">
            <div class="card-body text-center">
              <i class="fas fa-chart-line fa-2x mb-3"></i>
              <h5 class="card-title">PROMOTIONS</h5>
            </div>
          </a>
        </div>
      </div>
    </div>
  </div>
  <div id="footer" class="bg-dark text-white">
    <p>© Keuryabt - 2024 - Tous droits réservés  
  </div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" charset="utf-8"></script>
  <script src="../css/public/js/bootstrap.min.js"></script>
  <?php
 } else {
   header('Location: ../admin/pageinterdit.php');
   exit();
 }
?>
</body>
</html>