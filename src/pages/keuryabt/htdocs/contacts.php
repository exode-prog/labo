<?php
session_start();
include("bd/connexion.php");
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
  <title>keuryabt | Contactez-nous</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.7.2/font/bootstrap-icons.min.css"> 
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
   <link rel="stylesheet" href="css/webfonts/all.css">
    <link rel="shortcut icon" type="image/png" href="css/image/WhatsApp Image 2024-05-19 at 11.25.42 (1).jpeg">
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
            max-height: 80px;
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
    #scrollToTop {
      position: fixed;
      bottom: 20px;
      right: 20px;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background-color: #82C91E;
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
    .jumbotron {
      background-image: url('css/image/com.jpg');
      background-size: cover;
      color: #fff;
      padding: 6px 0;
    }

    .jumbotron h1 {
      font-size: 3.5rem;
    }
    h5 a{
            text-decoration: none;
            color: black; 
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
  <!-- Contenu de la page -->
  <div class="container container-contact">
    <h2 class="text-center mb-4">BESOIN D'AIDE POUR PASSER UNE COMMANDE ?</h2>
    <h5 class="text-center mb-4">Si vous rencontrez des difficultés à passer une commande merci de nous appeler sur les numéros suivant:</h5><p class="text-center"> APPELLEZ LE NUMERO 78 132 69 69 OU SUR WHATSAPP : 78 132 69 69 ?</p>
    <div class="row">
      <div class="col-md-6">
        <!-- Informations de l'entreprise -->
        <div class="card card-contact mb-4 shadow-sm">
          <div class="card-body">
            <h3 class="text-center">Informations de l'entreprise</h3>
            <p>Adresse : Dakar-Sénégal</p>
            <p>Téléphone : 78 132 69 69</p>
            <p>Email : contact@Keuryabt.com</p>
            <p>Heures d'ouverture : Du mardi au jeudi : 10h-19h / Vendredi: 9h-13h /15h-20h / Samedi et Dimanche: 10h - 20h / Lundi: Fermé</p>
            <!-- Réseaux sociaux -->
            <h3>Réseaux sociaux</h3>
            <ul class="list-inline">
              <li class="list-inline-item"><a href="#" class="text-decoration-none text-black me-3"><i class="fab fa-facebook"></i></a></li>
              <li class="list-inline-item"><a href="#" class="text-decoration-none text-black me-3"><i class="fab fa-whatsapp"></i></a></li>
              <li class="list-inline-item"><a href="#" class="text-decoration-none text-black me-3"><i class="fab fa-instagram"></i></a></li>
              <li class="list-inline-item"><a href="https://www.tiktok.com/@modestbeautyhaven_?_t=8kIUXzjeXDi&_r=1" class="text-decoration-none text-black me-3"><i class="fab fa-tiktok"></i></a></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <!-- Carte de localisation -->
        <div class="card card-contact mb-4 shadow-sm">
          <div class="card-body">
            <h3 class="text-center">Localisation</h3>
            <div class="map-container">
              <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3964.3870055519266!2d3.854833614401902!3d7.174351517312425!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x103b86c8e6d28df3%3A0xcdebc573a6b57a0f!2sLagos!5e0!3m2!1sen!2sng!4v1645816226967!5m2!1sen!2sng" allowfullscreen="" loading="lazy"></iframe>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-8 offset-md-2">
        <!-- Formulaire de contact -->
        <div class="card card-contact mb-4 shadow-sm">
          <div class="card-body">
            <h3 class="text-center">Envoyez-nous un message</h3>
            <form>
              <div class="mb-3">
                <label for="name" class="form-label">Nom</label>
                <input type="text" class="form-control" id="name" name="name" required>
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
              </div>
              <div class="mb-3">
                <label for="subject" class="form-label">Sujet</label>
                <input type="text" class="form-control" id="subject" name="subject" required>
              </div>
              <div class="mb-3">
                <label for="message" class="form-label">Message</label>
                <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
              </div>
              <button type="submit" class="btn btn-primary">Envoyer</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>