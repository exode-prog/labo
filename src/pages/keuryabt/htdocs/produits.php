<?php
session_start();
include("bd/connexion.php");
$maxArticles = 8; 
$totalElectromenager = $connexion->query("SELECT COUNT(idp) as count FROM produits JOIN categories ON produits.idcategorie = categories.idcategorie WHERE nomcategorie = 'Electromenager'")->fetch(PDO::FETCH_OBJ)->count;
$pagesElectromenager = ceil($totalElectromenager / $maxArticles);
$pageElectromenager = isset($_GET['page_electromenager']) && (int)$_GET['page_electromenager'] > 0 ? (int)$_GET['page_electromenager'] : 1;
$offsetElectromenager = ($pageElectromenager - 1) * $maxArticles;

$selectElectromenager = "SELECT * FROM produits JOIN categories ON produits.idcategorie = categories.idcategorie WHERE nomcategorie = 'Electromenager' ORDER BY idp DESC LIMIT $maxArticles OFFSET $offsetElectromenager";
$stmtElectromenager = $connexion->prepare($selectElectromenager);
$stmtElectromenager->execute();
$electromenagers = $stmtElectromenager->fetchAll(PDO::FETCH_ASSOC);

$totalInformatique = $connexion->query("SELECT COUNT(idp) as count FROM produits JOIN categories ON produits.idcategorie = categories.idcategorie WHERE nomcategorie = 'Informatique'")->fetch(PDO::FETCH_OBJ)->count;
$pagesInformatique = ceil($totalInformatique / $maxArticles);
$pageInformatique = isset($_GET['page_informatique']) && (int)$_GET['page_informatique'] > 0 ? (int)$_GET['page_informatique'] : 1;
$offsetInformatique = ($pageInformatique - 1) * $maxArticles;

$selectInformatique = "SELECT * FROM produits JOIN categories ON produits.idcategorie = categories.idcategorie WHERE nomcategorie = 'Informatique' ORDER BY idp DESC LIMIT $maxArticles OFFSET $offsetInformatique"; 
$stmtInformatique = $connexion->prepare($selectInformatique);
$stmtInformatique->execute();
$informatiques = $stmtInformatique->fetchAll(PDO::FETCH_ASSOC);

$totalCuisines = $connexion->query("SELECT COUNT(idp) as count FROM produits JOIN categories ON produits.idcategorie = categories.idcategorie WHERE nomcategorie = 'Cuisines'")->fetch(PDO::FETCH_OBJ)->count;
$pagesCuisines = ceil($totalCuisines / $maxArticles);
$pageCuisines = isset($_GET['page_cuisines']) && (int)$_GET['page_cuisines'] > 0 ? (int)$_GET['page_cuisines'] : 1;
$offsetCuisines = ($pageCuisines - 1) * $maxArticles;

$selectCuisines = "SELECT * FROM produits JOIN categories ON produits.idcategorie = categories.idcategorie WHERE nomcategorie = 'Cuisines' ORDER BY idp DESC LIMIT $maxArticles OFFSET $offsetCuisines";
$stmtCuisines = $connexion->prepare($selectCuisines);
$stmtCuisines->execute();
$cuisines = $stmtCuisines->fetchAll(PDO::FETCH_ASSOC);

$totalDecorations = $connexion->query("SELECT COUNT(idp) as count FROM produits JOIN categories ON produits.idcategorie = categories.idcategorie WHERE nomcategorie = 'Decorations'")->fetch(PDO::FETCH_OBJ)->count;
$pagesDecorations = ceil($totalDecorations / $maxArticles);
$pageDecorations = isset($_GET['page_decorations']) && (int)$_GET['page_decorations'] > 0 ? (int)$_GET['page_decorations'] : 1;
$offsetDecorations = ($pageDecorations - 1) * $maxArticles;

$selectDecorations = "SELECT * FROM produits JOIN categories ON produits.idcategorie = categories.idcategorie WHERE nomcategorie = 'Decorations' ORDER BY idp DESC LIMIT $maxArticles OFFSET $offsetDecorations";
$stmtDecorations = $connexion->prepare($selectDecorations);
$stmtDecorations->execute();
$decorations = $stmtDecorations->fetchAll(PDO::FETCH_ASSOC);

$totalSoins = $connexion->query("SELECT COUNT(idp) as count FROM produits JOIN categories ON produits.idcategorie = categories.idcategorie WHERE nomcategorie = 'Soins'")->fetch(PDO::FETCH_OBJ)->count;
$pagesSoins = ceil($totalSoins / $maxArticles);
$pageSoins = isset($_GET['page_soins']) && (int)$_GET['page_soins'] > 0 ? (int)$_GET['page_soins'] : 1;
$offsetSoins = ($pageSoins - 1) * $maxArticles;
$selectSoins = "SELECT * FROM produits JOIN categories ON produits.idcategorie = categories.idcategorie WHERE nomcategorie = 'Soins' ORDER BY idp DESC LIMIT $maxArticles OFFSET $offsetSoins";
$stmtSoins = $connexion->prepare($selectSoins);
$stmtSoins->execute();
$soins = $stmtSoins->fetchAll(PDO::FETCH_ASSOC);

// Définition initiale du nombre de produits dans le panier
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
    <title>keuryabt | Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.7.2/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="css/webfonts/all.css"> 
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
            max-height: 120px;
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
        .instagram-img {
            max-width: 100%; 
            height: auto;
        }
        .btn-success{
            background-color:#82C91E;
        }
        h5 a{
            text-decoration: none;
            color: black; 
        }
          #scrollToTop {
      position: fixed;
      bottom: 20px;
      right: 20px;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background-color: blue;
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
    .fixed-top {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1030;
        background-color: rgba(255, 255, 255, 0.9); 
      }
    .button-container {
    position: fixed;
    bottom: 60px; 
    right: 20px; 
    display: flex; 
    align-items: center; 
    z-index: 1000; 
  }      
  #whatsappButton,
  #scrollToTop {
    margin-left: 10px; 
  }
         .text-transition {
      font-size: 20px;
      text-align: center;
      overflow: hidden; 
      border-right: .15em solid orange; 
      white-space: nowrap; 
      margin: 0 auto; 
      letter-spacing: .15em; 
      animation: typing 3.5s steps(40, end), blink-caret .75s step-end infinite;
    }
    .carousel-image {
            max-height: 300px; /* Ajustez cette valeur selon vos besoins */
            width: auto; /* Permet à la largeur de s'adapter proportionnellement à la hauteur spécifiée */
        }
        .carousel-caption {
            top: 50%;
            transform: translateY(-50%);
        }
        .carousel-caption h1, .carousel-caption h2 {
            color: #fff; /* Ajustez la couleur du texte selon vos besoins */
        }


    /* Media Queries pour la réactivité */
    @media (max-width: 768px) {
      .swiper-container {
        height: 300px;
      }
      .image-overlay h2 {
        font-size: 2rem;
      }
      .image-overlay p {
        font-size: 1rem;
      }
      .image-overlay .btn-primary {
        padding: 8px 16px;
      }
    }

    @media (max-width: 480px) {
      .swiper-container {
        height: 200px;
      }
      .image-overlay h2 {
        font-size: 1.5rem;
      }
      .image-overlay p {
        font-size: 0.875rem;
      }
      .image-overlay .btn-primary {
        padding: 6px 12px;
      }
    }
    .nav-link {
    position: relative;
    display: inline-block;
}

#cart-count {
    position: absolute;
    top: -20px; /* Ajustez cette valeur pour positionner verticalement */
    right: -10px; /* Ajustez cette valeur pour positionner horizontalement */
    color: #82C91E; /* Couleur du texte du badge */
    border-radius: 50%; /* Pour rendre le badge rond */
    padding: 5px 10px; /* Taille du badge */
    font-size: 15px; /* Taille du texte */
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
        <a class="navbar-brand" href="../index.php"><img src="css/image/WhatsApp Image 2024-05-19 at 11.25.42 (1).jpeg" alt="Logo SlayZone Official" class="navbar-logo"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="electromenagers.php">Electroménagers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="informatiques.php">Informatiques</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="cuisines.php">Cuisines</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="decorations.php">Décorations</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="soins.php">Soins</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="promotions.php">Promotions</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contacts.php">Contacts</a>
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
                    <li><a class="dropdown-item" href="compte.php">Inscription</a></li>
                    <li><a class="dropdown-item" href="authentification.php">Connexion</a></li>
                </ul>
            </div>
            <a href="pages/panier.php" class="btn btn-success position-relative">
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
                <form method="POST" action="pages/recherche.php">
                    <div class="mb-3">
                        <input class="form-control" type="search" name="nom" placeholder="Rechercher" aria-label="Search">
                    </div>
                    <button class="btn btn-success" type="submit" name="search"><i class="bi bi-search"></i> Rechercher</button>
                </form>
            </div>
        </div>
    </div>
</div>
  <section class="jumbotron text-center">
        <div class="container">
            <h1 class="display-3">Keur Yaye Adji Bineta Thiam</h1>
            <h2 class="text-transition" id="dynamic-text"></h2>
        </div>
        <div id="carouselExampleIndicators" class="carousel slide mx-auto mt-4" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="css/image/WhatsApp Image 2024-05-21 at 17.50.36.jpeg" class="carousel-image d-block w-100" alt="...">
                    <div class="carousel-caption d-none d-md-block text-center">
                        <h5 class="display-4">Keur Yaye Adji Bineta Thiam</h5>
                        <h2 class="text-transition" id="dynamic-text">Texte pour la première image</h2>
                        <p class="animate__animated animate__fadeInUp">Une description de la première image.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="css/image/WhatsApp Image 2024-05-21 at 17.50.36 (1).jpeg" class="carousel-image d-block w-100" alt="...">
                    <div class="carousel-caption d-none d-md-block text-center">
                        <h5 class="display-4">Keur Yaye Adji Bineta Thiam</h5>
                        <h2 class="text-transition" id="dynamic-text">Texte pour la deuxième image</h2>
                        <p class="animate__animated animate__fadeInUp">Une description de la deuxième image.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="css/image/WhatsApp Image 2024-05-21 at 17.50.54.jpeg" class="carousel-image d-block w-100" alt="...">
                    <div class="carousel-caption d-none d-md-block text-center">
                        <h5 class="display-4">Keur Yaye Adji Bineta Thiam</h5>
                        <h2 class="text-transition" id="dynamic-text">Texte pour la troisième image</h2>
                        <p class="animate__animated animate__fadeInUp">Une description de la troisième image.</p>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>

<section class="bg-pink py-5">
    <div class="container">
        <h2 class="text-center mb-4">Nos Produits</h2>
        <div class="row row-cols-2 row-cols-md-4 g-4">
             <?php foreach ($electromenagers as $produit) : ?>
            <div class="col">
                <div class="product d-flex flex-column align-items-center">
                    <img src="photo/<?php echo $produit['photo']; ?>" style="width: 80%; height: 80%; margin-left: 10%; padding: 1rem; border-radius: 10%;" alt="<?php echo $produit['nom']; ?>">
                    <h4><?php echo $produit['nom']; ?></h4>
                    <p><?php echo $produit['details']; ?></p>
                    <p><?php echo $produit['prix']; ?> FCFA</p>
                    <a href="detailproduit.php?id=<?php echo $produit['idp']; ?>" class="btn btn-success">J’achète</a>
                </div>
            </div>
          <?php endforeach; ?>  
    </div>
</section>
<section class="bg-pink py-5">
    <div class="container">
        <div class="row row-cols-2 row-cols-md-4 g-4">
            <?php foreach ($informatiques as $produit) : ?>
                <div class="col">
                    <div class="product d-flex flex-column align-items-center">
                        <img src="photo/<?php echo $produit['photo']; ?>" style="width: 80%; height: 80%; margin-left: 10%; padding: 1rem; border-radius: 10%;" alt="<?php echo $produit['nom']; ?>">
                        <div class="product-details text-center">
                            <h4><?php echo $produit['nom']; ?></h4>
                            <p><?php echo $produit['details']; ?></p>
                            <p><?php echo $produit['prix']; ?> FCFA</p>
                            <a href="detailproduit.php?id=<?php echo $produit['idp']; ?>" class="btn btn-success">J’achète</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>  
        </div>
    </div>
</section>

<section class="bg-pink py-5">
    <div class="container">
        <div class="row row-cols-2 row-cols-md-4 g-4">
             <?php foreach ($cuisines as $produit) : ?>
            <div class="col">
                <div class="product d-flex flex-column align-items-center">
                    <img src="photo/<?php echo $produit['photo']; ?>" style="width: 80%; height: 80%; margin-left: 10%; padding: 1rem; border-radius: 10%;" alt="<?php echo $produit['nom']; ?>">
                    <h4><?php echo $produit['nom']; ?></h4>
                    <p><?php echo $produit['details']; ?></p>
                    <p><?php echo $produit['prix']; ?> FCFA</p>
                    <a href="detailproduit.php?id=<?php echo $produit['idp']; ?>" class="btn btn-success">J’achète</a>
                </div>
            </div>
          <?php endforeach; ?>  
    </div>
</section>
<section class="bg-pink py-5">
    <div class="container">
        <div class="row row-cols-2 row-cols-md-4 g-4">
             <?php foreach ($decorations as $produit) : ?>
            <div class="col">
                <div class="product d-flex flex-column align-items-center">
                    <img src="photo/<?php echo $produit['photo']; ?>" style="width: 80%; height: 80%; margin-left: 10%; padding: 1rem; border-radius: 10%;" alt="<?php echo $produit['nom']; ?>">
                    <h4><?php echo $produit['nom']; ?></h4>
                    <p><?php echo $produit['details']; ?></p>
                    <p><?php echo $produit['prix']; ?> FCFA</p>
                    <a href="detailproduit.php?id=<?php echo $produit['idp']; ?>" class="btn btn-success">J’achète</a>
                </div>
            </div>
          <?php endforeach; ?>  
    </div>
</section>
<section class="bg-pink py-5">
    <div class="container">
        <div class="row row-cols-2 row-cols-md-4 g-4">
             <?php foreach ($soins as $produit) : ?>
            <div class="col">
                <div class="product d-flex flex-column align-items-center">
                    <img src="photo/<?php echo $produit['photo']; ?>" style="width: 80%; height: 80%; margin-left: 10%; padding: 1rem; border-radius: 10%;" alt="<?php echo $produit['nom']; ?>">
                    <h4><?php echo $produit['nom']; ?></h4>
                    <p><?php echo $produit['details']; ?></p>
                    <p><?php echo $produit['prix']; ?> FCFA</p>
                    <a href="detailproduit.php?id=<?php echo $produit['idp']; ?>" class="btn btn-success">J’achète</a>
                </div>
            </div>
          <?php endforeach; ?>  
    </div>
     <a href="produits.php" class="btn btn-success offset-5">TOUT AFFICHER</a>
</section>
<!-- Section pour les tablettes et les ordinateurs -->
<section class="instagram-section py-5 d-none d-md-block">
    <div class="container">
         <h2 class="text-center mb-4">SUIVEZ-NOUS SUR <span>
    <a href="https://www.facebook.com" class="me-4 text-dark"><i class="bi bi-facebook"></i></a>
    <a href="https://www.tiktok.com/@modestbeautyhaven_?_t=8kIUXzjeXDi&_r=1" class="me-4 text-dark"><i class="bi bi-tiktok"></i></a>
    <a href="https://www.instagram.com/?hl=fr" class="me-4 text-dark"><i class="bi bi-instagram"></i></a>
    <a href="https://api.whatsapp.com/send?phone=221781326969" target="_blank" class="me-4 text-dark"><i class="bi bi-whatsapp"></i></a>
</span></h2>
        <!-- Galerie d'images pour les tablettes et ordinateurs -->
        <div class="row row-cols-2 row-cols-md-4 g-4">
            <div class="col">
                <a href="#"><img src="css/image/WhatsApp Image 2024-05-19 at 11.25.42 (1).jpeg" alt="Instagram Post 1" class="instagram-img"></a>
            </div>
            <div class="col">
                <a href="#"><img src="css/image/WhatsApp Image 2024-05-19 at 11.25.42 (1).jpeg" alt="Instagram Post 2" class="instagram-img"></a>
            </div>
            <div class="col">
                <a href="#"><img src="css/image/WhatsApp Image 2024-05-19 at 11.25.42 (1).jpeg" alt="Instagram Post 3" class="instagram-img"></a>
            </div>
            <div class="col">
                <a href="#"><img src="css/image/WhatsApp Image 2024-05-19 at 11.25.42 (1).jpeg" alt="Instagram Post 4" class="instagram-img"></a>
            </div>
        </div>
    </div>
</section>

<!-- Section pour les téléphones -->
<section class="instagram-section py-5 d-md-none">
    <div class="container">
       <h2 class="text-center mb-4">SUIVEZ-NOUS SUR <span>
    <a href="https://www.facebook.com" class="me-4 text-dark"><i class="bi bi-facebook"></i></a>
    <a href="https://www.tiktok.com/@modestbeautyhaven_?_t=8kIUXzjeXDi&_r=1" class="me-4 text-dark"><i class="bi bi-tiktok"></i></a>
    <a href="https://www.instagram.com/?hl=fr" class="me-4 text-dark"><i class="bi bi-instagram"></i></a>
    <a href="https://api.whatsapp.com/send?phone=221781326969" target="_blank" class="me-4 text-dark"><i class="bi bi-whatsapp"></i></a>
    </span></h2>
        <!-- Carrousel d'images pour les téléphones -->
        <div id="instagramCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="css/image/WhatsApp Image 2024-05-19 at 11.25.42 (1).jpeg" class="d-block w-100" alt="Instagram Post 1">
                </div>
                <div class="carousel-item">
                    <img src="css/image/WhatsApp Image 2024-05-19 at 11.25.42 (1).jpeg" class="d-block w-100" alt="Instagram Post 2">
                </div>
                <div class="carousel-item">
                    <img src="css/image/WhatsApp Image 2024-05-19 at 11.25.42 (1).jpeg" class="d-block w-100" alt="Instagram Post 3">
                </div>
                <div class="carousel-item">
                    <img src="css/image/WhatsApp Image 2024-05-19 at 11.25.42 (1).jpeg" class="d-block w-100" alt="Instagram Post 4">
                </div>
                <!-- Ajoutez autant d'images que nécessaire -->
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#instagramCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#instagramCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</section>
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
                    <p class="text-dark"><i class="fa fa-envelope"></i> contact@keuryabt.com</p>
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
    </footer>
    <div id="scrollToTop" class="show category-card">
    <i class="fa fa-chevron-up"></i>
  </div>
  <div class="button-container">
  <!-- Bouton WhatsApp -->
  <div id="whatsappButton">
    <a href="https://wa.me/yourphonenumber" class="btn btn-success btn-lg"><i class="fab fa-whatsapp"></i> </a>
  </div>
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
    <!-- Ajoutez la bibliothèque animate.css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script>
    // Liste des textes à afficher
    var texts = ["d'informatiques", "d'Electromenager", "de cuisines", "de décoration"," de beauté (cosmétiques)","soins"];
    var index = 0;

    // Fonction pour changer le texte à intervalles réguliers
    function changeText() {
        document.getElementById('dynamic-text').textContent = "Découvrez notre gamme exclusive de produits " + texts[index];
        index = (index + 1) % texts.length;
    }

    // Appel de la fonction initiale
    changeText();

    // Changement de texte toutes les 3 secondes
    setInterval(changeText, 3000);
</script>
</body>
</html>