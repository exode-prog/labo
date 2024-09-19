<?php
session_start();
require('../bd/connexion.php');

// Fonction pour obtenir les produits du panier à partir de la table panier_produits
function getCartProducts($userId, $connexion) {
    $select = "SELECT pp.id_panier,pp.id, pp.idp, pp.id_promo, 
                      COALESCE(pr.nom, promo.nom) as nom, 
                      COALESCE(pr.photo, promo.photo) as photo, 
                      pp.prix_unitaire, pp.quantite
               FROM panier_produits as pp
               LEFT JOIN produits as pr ON pp.idp = pr.idp
               LEFT JOIN promotions as promo ON pp.id_promo = promo.id_promo
               WHERE pp.id_panier IN (
                   SELECT id_panier FROM paniers WHERE id_client = :userId
               )";
    
    $execut = $connexion->prepare($select);
    $execut->bindParam(':userId', $userId, PDO::PARAM_INT);
    $execut->execute();
    return $execut->fetchAll(PDO::FETCH_OBJ);
}

// Fonction pour obtenir les produits du panier à partir de la session
function getCartProductsFromSession($cart, $connexion) {
    $items = [];
    foreach ($cart as $item) {
        if (isset($item['id_produit'])) {
            $query = $connexion->prepare("SELECT idp as id, nom, photo, :prix as prix_unitaire FROM produits WHERE idp = :id");
            $query->bindParam(':id', $item['id_produit'], PDO::PARAM_INT);
        } else {
            $query = $connexion->prepare("SELECT id_promo as id, nom, photo, :prix as prix_unitaire FROM promotions WHERE id_promo = :id");
            $query->bindParam(':id', $item['id_promo'], PDO::PARAM_INT);
        }
        $query->bindParam(':prix', $item['prix'], PDO::PARAM_STR);
        $query->execute();
        $product = $query->fetch(PDO::FETCH_OBJ);
        if ($product) {
            $product->quantite = $item['quantite'];
            $items[] = $product;
        }
    }
    return $items;
}

// Initialisation des variables
$count = 0;
$result = [];
$total = 0;

// Vérifie si l'utilisateur est authentifié
if (isset($_SESSION['user']) && isset($_SESSION['user']->id_client)) {
    $userId = $_SESSION['user']->id_client;

    // Récupération des produits du panier depuis la base de données
    $result = getCartProducts($userId, $connexion);

    // Calcul du nombre d'articles dans le panier et du total
    $panierCountQuery = "SELECT count(id_panier) as nbre FROM paniers WHERE id_client = :userId";
    $execut_panier = $connexion->prepare($panierCountQuery);
    $execut_panier->bindParam(':userId', $userId, PDO::PARAM_INT);
    $execut_panier->execute();
    $count = $execut_panier->fetch(PDO::FETCH_OBJ)->nbre;
} else {
    // Utilisateur non authentifié - Récupération des produits depuis la session
    $cart = isset($_SESSION['panier']) ? $_SESSION['panier'] : [];
    $result = getCartProductsFromSession($cart, $connexion);
    $count = count($cart);
}
$count = new stdClass();
$count->nbre = 0;

// Vérifiez si l'utilisateur est authentifié et récupérez son ID client
if (isset($_SESSION['user']) && isset($_SESSION['user']->id_client)) {
    $userId = $_SESSION['user']->id_client;

    try {
        // Préparez la requête SQL pour compter le nombre total de produits dans les paniers
        $productCountQuery = "SELECT COUNT(*) AS nbre 
                              FROM paniers p
                              JOIN panier_produits pp ON p.id_panier = pp.id_panier
                              WHERE p.id_client = :userId";
        $execut_panier = $connexion->prepare($productCountQuery);
        $execut_panier->bindParam(':userId', $userId, PDO::PARAM_INT);

        // Exécutez la requête
        $execut_panier->execute();

        // Récupérez le résultat
        $count = $execut_panier->fetch(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
        // En cas d'erreur, affichez le message d'erreur
        echo "Erreur: " . $e->getMessage();
        $count = new stdClass();
        $count->nbre = 0;
    }
} elseif (isset($_SESSION['panier'])) {
    // Utilisateur non authentifié - Vérifiez le panier temporaire en session
    $count = new stdClass();
    $count->nbre = count($_SESSION['panier']);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>keuryabt | Panier</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="shortcut icon" type="image/png" href="../css/image/WhatsApp Image 2024-05-19 at 11.25.42 (1).jpeg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.7.2/font/bootstrap-icons.min.css"> 
    <style>
         body {
      font-family: 'Poppins', sans-serif;
      background-color: #f5f5f5;
    }
        .bg-beige {
             font-family: 'Poppins', sans-serif;
            background-color: #82C91E;
            color: #fff;
        }
        .navbar-logo {
            max-height: 50px;
        }

        main {
            margin-top: 20px;
        }

        h2 {
            color: #343a40;
        }

        .card {
            border: none;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
            border-radius: 10px;
        }

        .card-title {
            color: #343a40;
            font-size: 1.2rem;
            font-weight: bold;
        }

        .card-text {
            color: #1d1d1d;
            margin-bottom: 6px;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
        .footer {
            background-color: #f6f6f6;
            color: #1d1d1d;
            padding: 50px 0;
        }
        .footer-links ul {
            list-style-type: none;
            padding: 0;
        }
        .footer-links ul li {
            margin-bottom: 10px;
        }
        .footer-links ul li a {
            color: #1d1d1d;
            text-decoration: none;
            font-size: 14px;
        }
        .footer-contact-info {
            font-size: 14px;
            margin-top: 30px;
        }
        .footer-contact-info p {
            margin-bottom: 5px;
        }
        .footer-social-icons {
            font-size: 24px;
        }
        .btn-success{
            background-color:#82C91E;
        }
        #scrollToTop {
          position: fixed;
          bottom: 20px;
          right: 20px;
          width: 50px;
          height: 50px;
          border-radius: 50%;
          background-color:  #82C91E;
          color: #fff;
          text-align: center;
          line-height: 50px;
          cursor: pointer;
          opacity: 0;
          visibility: hidden;
          transition: opacity 0.3s, visibility 0.3s;
        }
        #scrollToTop.show {
          opacity: 1;
          visibility: visible;
        }
        h5 a{
            text-decoration: none;
            color: black; 
        }
    </style>
</head>
<body>
     <div class="delivery-banner bg-beige text-center py-2">
        <p class="m-0">
            Livraison : Dakar 3500 FCFA | Guédiawaye 3000 FCFA | Pikine 2500FCFA| Rufisque 3000FCFA
        </p>
    </div>
  <nav class="navbar navbar-expand-lg navbar-light bg-light py-3">
    <div class="container">
        <a class="navbar-brand" href="../index.php"><img src="../css/image/WhatsApp Image 2024-05-19 at 11.25.42 (1).jpeg" alt="Logo SlayZone Official" class="navbar-logo"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="../index.php">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../electromenagers.php">Electroménagers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../informatiques.php">Informatiques</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../cuisines.php">Cuisines</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../decorations.php">Décorations</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../soins.php">Soins</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../promotions.php">Promotions</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../contacts.php">Contacts</a>
                </li>
            </ul>
        </div>
        <!-- Barre de recherche, icône de connexion et icône du panier -->
        <div class="d-flex align-items-center text-center">
            <!-- Bouton pour ouvrir le modal de recherche -->
            <button class="btn btn-success me-3" data-bs-toggle="modal" data-bs-target="#searchModal">
                <i class="bi bi-search"></i>
            </button>
            <div class="dropdown me-3">
                <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person"></i>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href="../compte.php">Inscription</a></li>
                    <li><a class="dropdown-item" href="../authentification.php">Connexion</a></li>
                </ul>
            </div>
            <a href="../pages/panier.php" class="btn btn-success position-relative">
                    <i class="bi bi-basket"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        <?= $count->nbre; ?>
                    </span>
                </a>
        </div>
    </div>
</nav>

<!-- Modal -->
<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="searchModalLabel">Recherche</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="../pages/recherche.php">
                    <div class="mb-3">
                        <input class="form-control" type="search" name="nom" placeholder="Rechercher" aria-label="Search">
                    </div>
                    <button class="btn btn-success" type="submit" name="search"><i class="bi bi-search"></i> Rechercher</button>
                </form>
            </div>
        </div>
    </div>
</div>
    <main class="container mt-3">
    <h2 class="mb-4 text-center">Votre Panier</h2>
    <div class="row">
        <?php foreach($result as $value): ?>
        <div class="col-lg-4 mb-3">
            <div class="card">
                <img src="../photo/<?php echo $value->photo; ?>" class="card-img-top" alt="Product Image">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $value->nom; ?></h5>
                    <p class="card-text">Prix unitaire: <?php echo $value->prix_unitaire; ?> FCFA</p>
                    <p class="card-text">Quantité: <?php echo $value->quantite; ?> </p>
                    <p class="card-text">Total: <?php echo $value->prix_unitaire * $value->quantite; ?> FCFA</p>
                    <a href="supppanier.php?delete_id=<?php echo $value->id; ?>" class="btn btn-danger w-100" onclick="return confirm('Êtes-vous sûr de vouloir retirer ce produit du panier ?')">Retirer</a>
                </div>
            </div>
        </div>
        <?php $total += $value->prix_unitaire * $value->quantite; ?>
        <?php endforeach; ?>
        <div class="col-lg-5">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-text">Sous-total: <?php echo $total; ?> FCFA</h3>
                    <div class="delivery-info">
                        <h4 class="mt-3">Conditions de Livraison</h4>
                        <p>1- Dakar 3500 FCFA</p> 
                        <p>2- Guédiawaye 3000 FCFA </p>
                        <p>3- Pikine 2500FCFA </p>
                        <p>4- Rufisque 3000 FCFA</p>
                    </div>
                    <div class="mt-3">
                        <a href="../produits.php" class="btn btn-warning d-block mb-2">
                            <i class="fas fa-shopping-cart me-1"></i> Continuer vos achats
                        </a>
                        <a href="https://api.whatsapp.com/send?phone=221781326969&text=Bonjour,%20je%20souhaite%20commander%20les%20produits%20suivants%20:%0A<?php foreach($result as $value): ?>%0A- <?php echo $value->nom; ?>%20(x<?php echo $value->quantite; ?>)%20pour%20<?php echo $value->prix_unitaire * $value->quantite; ?>%20FCFA%0A<?php endforeach; ?>" class="btn btn-success d-block mb-2">
                            <i class="fab fa-whatsapp me-1"></i> Commander par WhatsApp
                        </a>
                        <a href="../formulaire/commandes.php" class="btn btn-primary d-block">
                            <i class="fas fa-cash-register me-1"></i> Passer à la caisse
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>A propos de nous</h5>
                    <h5>Liens rapides</h5>
                    <div class="footer-links">
                        <ul>
                            <li><a href="#">Recherche</a></li>
                            <li><a href="#">Politique de retours</a></li>
                            <li><a href="#">Conditions d'utilisation</a></li>
                            <li><a href="#">Newsletter</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <h5>Livraison</h5>
                    <p>Dakar 3500 FCFA</p>
                    <p>Guédiawaye 3000 FCFA</p>
                    <p>Pikine 2500FCFA</p>
                    <p>Rufisque 3000FCFA</p>
                </div>
                <div class="col-md-4">
                    <h5><a href="contacts.php" class="text-decoration">CONTACTS</a></h5>
                    <p>+221 78 132 69 69</p>
                    <p class="text-dark"><i class="fa fa-envelope"></i> contact@amsickshop.com</p>
                    <div class="footer-social-icons">
                        <a href="https://www.tiktok.com/@modestbeautyhaven_?_t=8kIUXzjeXDi&_r=1" class="text-dark"><i class="bi bi-tiktok"></i></a>
                        <a href="https://www.instagram.com/?hl=fr" class="text-dark"><i class="bi bi-instagram"></i></a>
                        <a href="https://api.whatsapp.com/send?phone=221781326969" target="_blank" class="text-dark"><i class="bi bi-whatsapp"></i></a>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-12 text-center">
                    <p>© Keuryabt - 2024 - Tous droits réservés</p>
                </div>
            </div>
        </div>
      <a href="https://api.whatsapp.com/send?phone=221781326969" target="_blank">
        <div id="scrollToTop" class="show category-card">
            <i class="bi bi-whatsapp"></i> Contacter via WhatsApp
        </div>
    </a>
    </footer>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
