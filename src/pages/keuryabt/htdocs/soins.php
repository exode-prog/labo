<?php
session_start();
include("bd/connexion.php");
$maxArticles = 12; 
$totalCheveux = $connexion->query("SELECT COUNT(idp) as count FROM produits JOIN categories ON produits.idcategorie = categories.idcategorie WHERE nomcategorie = 'Cheveux'")->fetch(PDO::FETCH_OBJ)->count;
$pagesCheveux = ceil($totalCheveux / $maxArticles);
$pageCheveux = isset($_GET['page_cheveux']) && (int)$_GET['page_cheveux'] > 0 ? (int)$_GET['page_cheveux'] : 1;
$offsetCheveux = ($pageCheveux - 1) * $maxArticles;

$selectCheveux = "SELECT * FROM produits JOIN categories ON produits.idcategorie = categories.idcategorie WHERE nomcategorie = 'Cheveux' ORDER BY idp DESC LIMIT $maxArticles OFFSET $offsetCheveux";
$stmtCheveux = $connexion->prepare($selectCheveux);
$stmtCheveux->execute();
$cheveux = $stmtCheveux->fetchAll(PDO::FETCH_ASSOC);


$totalVisages = $connexion->query("SELECT COUNT(idp) as count FROM produits JOIN categories ON produits.idcategorie = categories.idcategorie WHERE nomcategorie = 'Visages'")->fetch(PDO::FETCH_OBJ)->count;
$pagesVisages = ceil($totalVisages / $maxArticles);
$pageVisages = isset($_GET['page_visages']) && (int)$_GET['page_visages'] > 0 ? (int)$_GET['page_visages'] : 1;
$offsetVisages = ($pageVisages - 1) * $maxArticles;

$selectVisages = "SELECT * FROM produits JOIN categories ON produits.idcategorie = categories.idcategorie WHERE nomcategorie = 'Visages' ORDER BY idp DESC LIMIT $maxArticles OFFSET $offsetVisages";
$stmtVisages = $connexion->prepare($selectVisages);
$stmtVisages->execute();
$visages = $stmtVisages->fetchAll(PDO::FETCH_ASSOC);

$totalCorps = $connexion->query("SELECT COUNT(idp) as count FROM produits JOIN categories ON produits.idcategorie = categories.idcategorie WHERE nomcategorie = 'Corps'")->fetch(PDO::FETCH_OBJ)->count;
$pagesCorps = ceil($totalCorps / $maxArticles);
$pageCorps = isset($_GET['page_corps']) && (int)$_GET['page_corps'] > 0 ? (int)$_GET['page_corps'] : 1;
$offsetCorps = ($pageCorps - 1) * $maxArticles;

$selectCorps = "SELECT * FROM produits JOIN categories ON produits.idcategorie = categories.idcategorie WHERE nomcategorie = 'Corps' ORDER BY idp DESC LIMIT $maxArticles OFFSET $offsetCorps";
$stmtCorps = $connexion->prepare($selectCorps);
$stmtCorps->execute();
$corps = $stmtCorps->fetchAll(PDO::FETCH_ASSOC);


$totalParfums = $connexion->query("SELECT COUNT(idp) as count FROM produits JOIN categories ON produits.idcategorie = categories.idcategorie WHERE nomcategorie = 'Parfums'")->fetch(PDO::FETCH_OBJ)->count;
$pagesParfums = ceil($totalParfums / $maxArticles);
$pageParfums = isset($_GET['page_parfums']) && (int)$_GET['page_parfums'] > 0 ? (int)$_GET['page_parfums'] : 1;
$offsetParfums = ($pageParfums - 1) * $maxArticles;

$selectParfums = "SELECT * FROM produits JOIN categories ON produits.idcategorie = categories.idcategorie WHERE nomcategorie = 'Parfums' ORDER BY idp DESC LIMIT $maxArticles OFFSET $offsetParfums";
$stmtParfums = $connexion->prepare($selectParfums);
$stmtParfums->execute();
$parfums = $stmtParfums->fetchAll(PDO::FETCH_ASSOC);


$totalMaquillages = $connexion->query("SELECT COUNT(idp) as count FROM produits JOIN categories ON produits.idcategorie = categories.idcategorie WHERE nomcategorie = 'Maquillages'")->fetch(PDO::FETCH_OBJ)->count;
$pagesMaquillages = ceil($totalMaquillages / $maxArticles);
$pageMaquillages = isset($_GET['page_maquillages']) && (int)$_GET['page_maquillages'] > 0 ? (int)$_GET['page_maquillages'] : 1;
$offsetMaquillages = ($pageMaquillages - 1) * $maxArticles;

$selectMaquillages = "SELECT * FROM produits JOIN categories ON produits.idcategorie = categories.idcategorie WHERE nomcategorie = 'Maquillages' ORDER BY idp DESC LIMIT $maxArticles OFFSET $offsetMaquillages";
$stmtMaquillages = $connexion->prepare($selectMaquillages);
$stmtMaquillages->execute();
$maquillages = $stmtMaquillages->fetchAll(PDO::FETCH_ASSOC);

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
    <title>keuryabt | Cheveux</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.7.2/font/bootstrap-icons.min.css"> 
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="css/webfonts/all.css"> 
    <link rel="shortcut icon" type="image/png" href="css/image/WhatsApp Image 2024-05-19 at 11.25.42 (1).jpeg">
    <style>
         body {
       font-family: 'Poppins', sans-serif;
      background-color: #f5f5f5;
    }
    .bg-pink{
        font-family: 'Poppins', sans-serif;

    }
        .bg-beige {
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
    .section-background {
      background-image: url('css/image/WhatsApp Image 2024-05-25 at 12.27.26 (1).jpeg');
      height: 400px; 
      position: relative; 
      background-size: cover; 
      background-position: center; 
      background-repeat: no-repeat;
    }
    .image-overlay {
      position: absolute; 
      top: 0;
      left: 0;
      width: 100%; 
      height: 100%; 
      background-color: rgba(0, 0, 0, 0.6); 
      display: flex; 
      justify-content: center; 
      align-items: center; 
      color: white; 
      text-align: center; 
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
<section class="py-5 bg-light section-background" data-aos="fade-up">
<div class="container">
    <div class="row">
      <div class="image-overlay"> 
        <div class="col-lg-12 text-center text-light"> 
            <h1 class="text-transition fs-3 mb-3">SOINS</h1> 
        </div>
    </div>
</div>
</div>
</section>
<section class="bg-pink py-5">
    <div class="container">
<h2 class="text-center mb-4">SOINS</h2>
 <nav class="navbar navbar-expand-lg navbar-light ">
    <div class="container">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
        <ul class="navbar-nav mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active fw-bold" href="#categorie-cheveux">Cheveux</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active fw-bold" href="#visage">Soin Visages</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active fw-bold" href="#corps">Corps</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active fw-bold" href="#parfums">Parfums</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active fw-bold" href="#maquillages">Maquillages</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
        <div class="row row-cols-2 row-cols-md-4 g-4" id="categorie-cheveux">
             <?php foreach ($cheveux as $produit) : ?>
            <div class="col">
                <div class="product">
                    <img src="photo/<?php echo $produit['photo']; ?>" style="width: 80%; height: 80%; margin-left: 10%; padding: 1rem; border-radius: 10%;" alt="<?php echo $produit['nom']; ?>">
                    <h4><?php echo $produit['nom']; ?></h4>
                    <p><?php echo $produit['details']; ?></p>
                    <p><?php echo $produit['prix']; ?> FCFA</p>
                    <a href="detailproduit.php?id=<?php echo $produit['idp']; ?>" class="btn btn-success">J’achète</a>
                </div>
            </div>
          <?php endforeach; ?>  
    </div>
    <div class="pagination justify-content-center mt-4">
        <ul class="pagination">
            <?php if ($pageCheveux > 1) : ?>
                <li class="page-item"><a class="page-link" href="?page_cheveux=<?php echo $pageCheveux - 1; ?>"><i class="bi bi-arrow-left"></i></a></li>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $pageCheveux; $i++) : ?>
                <li class="page-item <?php if ($i === $pageCheveux) echo 'active'; ?>"><a class="page-link" href="?page_cheveux=<?php echo $i; ?>">Page <?php echo $i; ?></a></li>
            <?php endfor; ?>
            <?php if ($pageCheveux < $pagesCheveux) : ?>
                <li class="page-item"><a class="page-link" href="?page_cheveux=<?php echo $pagecheveux + 1; ?>"><i class="bi bi-arrow-right"></i></a></li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="row row-cols-2 row-cols-md-4 g-4" id="visage">
             <?php foreach ($visages as $produit) : ?>
            <div class="col">
                <div class="product">
                    <img src="photo/<?php echo $produit['photo']; ?>" style="width: 80%; height: 80%; margin-left: 10%; padding: 1rem; border-radius: 10%;" alt="<?php echo $produit['nom']; ?>">
                    <h4><?php echo $produit['nom']; ?></h4>
                    <p><?php echo $produit['details']; ?></p>
                    <p><?php echo $produit['prix']; ?> FCFA</p>
                    <a href="detailproduit.php?id=<?php echo $produit['idp']; ?>" class="btn btn-success">J’achète</a>
                </div>
            </div>
          <?php endforeach; ?>  
    </div>
    <div class="pagination justify-content-center mt-4">
        <ul class="pagination">
            <?php if ($pageVisages > 1) : ?>
                <li class="page-item"><a class="page-link" href="?page_visages=<?php echo $pageVisages - 1; ?>"><i class="bi bi-arrow-left"></i></a></li>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $pageVisages; $i++) : ?>
                <li class="page-item <?php if ($i === $pageVisages) echo 'active'; ?>"><a class="page-link" href="?page_visages=<?php echo $i; ?>">Page <?php echo $i; ?></a></li>
            <?php endfor; ?>
            <?php if ($pageVisages < $pagesVisages) : ?>
                <li class="page-item"><a class="page-link" href="?page_visages=<?php echo $pagevisages + 1; ?>"><i class="bi bi-arrow-right"></i></a></li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="row row-cols-2 row-cols-md-4 g-4" id="corps">
             <?php foreach ($corps as $produit) : ?>
            <div class="col">
                <div class="product">
                    <img src="photo/<?php echo $produit['photo']; ?>" style="width: 80%; height: 80%; margin-left: 10%; padding: 1rem; border-radius: 10%;" alt="<?php echo $produit['nom']; ?>">
                    <h4><?php echo $produit['nom']; ?></h4>
                    <p><?php echo $produit['details']; ?></p>
                    <p><?php echo $produit['prix']; ?> FCFA</p>
                    <a href="detailproduit.php?id=<?php echo $produit['idp']; ?>" class="btn btn-success">J’achète</a>
                </div>
            </div>
          <?php endforeach; ?>  
    </div>
    <div class="pagination justify-content-center mt-4">
        <ul class="pagination">
            <?php if ($pageCorps > 1) : ?>
                <li class="page-item"><a class="page-link" href="?page_corps=<?php echo $pageCorps - 1; ?>"><i class="bi bi-arrow-left"></i></a></li>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $pageCorps; $i++) : ?>
                <li class="page-item <?php if ($i === $pageCorps) echo 'active'; ?>"><a class="page-link" href="?page_corps=<?php echo $i; ?>">Page <?php echo $i; ?></a></li>
            <?php endfor; ?>
            <?php if ($pageCorps < $pagesCorps) : ?>
                <li class="page-item"><a class="page-link" href="?page_corps=<?php echo $pagecorps + 1; ?>"><i class="bi bi-arrow-right"></i></a></li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="row row-cols-2 row-cols-md-4 g-4" id="parfums">
             <?php foreach ($parfums as $produit) : ?>
            <div class="col">
                <div class="product">
                    <img src="photo/<?php echo $produit['photo']; ?>" style="width: 80%; height: 80%; margin-left: 10%; padding: 1rem; border-radius: 10%;" alt="<?php echo $produit['nom']; ?>">
                    <h4><?php echo $produit['nom']; ?></h4>
                    <p><?php echo $produit['details']; ?></p>
                    <p><?php echo $produit['prix']; ?> FCFA</p>
                    <a href="detailproduit.php?id=<?php echo $produit['idp']; ?>" class="btn btn-success">J’achète</a>
                </div>
            </div>
          <?php endforeach; ?>  
    </div>
    <div class="pagination justify-content-center mt-4">
        <ul class="pagination">
            <?php if ($pageParfums > 1) : ?>
                <li class="page-item"><a class="page-link" href="?page_parfums=<?php echo $pageParfums - 1; ?>"><i class="bi bi-arrow-left"></i></a></li>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $pageParfums; $i++) : ?>
                <li class="page-item <?php if ($i === $pageParfums) echo 'active'; ?>"><a class="page-link" href="?page_parfums=<?php echo $i; ?>">Page <?php echo $i; ?></a></li>
            <?php endfor; ?>
            <?php if ($pageParfums < $pagesParfums) : ?>
                <li class="page-item"><a class="page-link" href="?page_parfums=<?php echo $pageparfums + 1; ?>"><i class="bi bi-arrow-right"></i></a></li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="row row-cols-2 row-cols-md-4 g-4" id="maquillages">
             <?php foreach ($maquillages as $produit) : ?>
            <div class="col">
                <div class="product">
                    <img src="photo/<?php echo $produit['photo']; ?>" style="width: 80%; height: 80%; margin-left: 10%; padding: 1rem; border-radius: 10%;" alt="<?php echo $produit['nom']; ?>">
                    <h4><?php echo $produit['nom']; ?></h4>
                    <p><?php echo $produit['details']; ?></p>
                    <p><?php echo $produit['prix']; ?> FCFA</p>
                    <a href="detailproduit.php?id=<?php echo $produit['idp']; ?>" class="btn btn-success">J’achète</a>
                </div>
            </div>
          <?php endforeach; ?>  
    </div>
    <div class="pagination justify-content-center mt-4">
        <ul class="pagination">
            <?php if ($pageMaquillages > 1) : ?>
                <li class="page-item"><a class="page-link" href="?page_maquillages=<?php echo $pageMaquillages - 1; ?>"><i class="bi bi-arrow-left"></i></a></li>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $pageMaquillages; $i++) : ?>
                <li class="page-item <?php if ($i === $pageMaquillages) echo 'active'; ?>"><a class="page-link" href="?page_maquillages=<?php echo $i; ?>">Page <?php echo $i; ?></a></li>
            <?php endfor; ?>
            <?php if ($pageMaquillages < $pagesMaquillages) : ?>
                <li class="page-item"><a class="page-link" href="?page_maquillages=<?php echo $pagemaquillages + 1; ?>"><i class="bi bi-arrow-right"></i></a></li>
            <?php endif; ?>
        </ul>
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
      <a href="https://api.whatsapp.com/send?phone=221781326969" target="_blank">
        <div id="scrollToTop" class="show category-card">
            <i class="bi bi-whatsapp"></i> Contacter via WhatsApp
        </div>
    </a>
    </footer>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.bundle.min.js"></script>
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