<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Produit</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/webfonts/all.css">
    <style type="text/css">
        /* style.css */
        body {
            font-family: 'Playfair Display', sans-serif;
            background-color: #e9ecef;
        }
        #anene {
              text-align: center;
              color: #ffffff;
              border-radius: 8%  30px;
              background-color: #82C91E;
              width: 70%;
              margin-top: 3px;
            }
        .container1 {
            max-width: 600px;
            margin: 50px auto;
            background-color: #af094b;
            padding: 20px;
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
            overflow: hidden; /* Cache la couleur de fond */
            background-color: transparent !important; /* Rend la couleur de fond transparente */
        }

        h1, h2, p {
            margin: 0;
        }


        .container1:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            background-color: #f8f9fa !important; /* Change la couleur de fond au survol */
        }

        .product-title {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .product-text {
            font-size: 1rem;
            color: #777;
        }

        .product-details {
            display: flex;
            justify-content: space-between;
        }

        .product-image {
            width: 40%;
        }

        .product-info {
            width: 55%;
            padding-left: 20px;
        }

        .product-image img {
            max-width: 100%;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .product-title {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .product-price {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .product-quantity {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .product-description {
            font-size: 16px;
            line-height: 1.6;
        }
                footer {
            background-color: #333;
            color: #fff;
            padding-top: 10px;
        }
        footer h5 {
            color: #fff; 
            font-size: 26px;
        }
        footer h3 {
            color: #fff; 
            font-size: 14px;
        }

        footer ul {
            list-style: none;
            padding: 0;
        }

        footer ul li a {
            color: #fff;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        footer ul li a:hover {
            color: #fff; 
        }
        footer hr {
            border-top: 1px solid #555;
        }
        footer div.text-center {
            background-color: #222;
            padding: 10px 0;
        }
        footer div.row:last-child p {
            margin: 0;
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
    </style>
</head>
<body>
<div class="page-header offset-2" id="anene">
    <h4><marquee behavior="alternate">BIENVENUE SUR Modest Beauty Haven</marquee></h4>
  </div>
  <h4 align="center">BESOIN D'AIDE POUR COMMANDER ? APPELLEZ LE 78 132 69 69</h4>
<div class="container1">
    <?php
    require_once("../bd/connexion.php");
    $idProduit = $_GET['id'];

    // Requête préparée pour sélectionner un produit spécifique par son ID
    $selectProduit = "SELECT produits.*, categories.nomcategorie 
                      FROM produits 
                      JOIN categories ON produits.idcategorie = categories.idcategorie 
                      WHERE idp = :id";
    $stmt = $connexion->prepare($selectProduit);
    $stmt->bindParam(':id', $idProduit, PDO::PARAM_INT);
    $stmt->execute();
    $produit = $stmt->fetch(PDO::FETCH_ASSOC);
    ?>
    <div class="product-details">
        <div class="product-image">
            <img src="../photo/<?php echo $produit['photo']; ?>" alt="<?php echo $produit['nom']; ?>">
        </div>
        <div class="product-info">
            <h1 class="product-title mt-3">Catégorie : <?php echo $produit['nomcategorie']; ?></h1>
            <h4 class="product-category">Nom : <?php echo $produit['nom']; ?></h4>
            <h4 class="product-price mt-3">Prix : <?php echo $produit['prix']; ?> FCFA</h4>
            <h4 class="product-quantity mt-3">Quantité disponible : <?php echo $produit['quantite']; ?></h4>
            <h4 class="product-description mt-3">Description : <?php echo $produit['details']; ?></h4>
            <form action="../pages/add_cookie.php" method="POST">
                <input type="number" name="quantite" value="1" min="1" placeholder="Quantité" required class="form-control mt-5">
                <input type="hidden" name="idp" value="<?php echo $produit['idp']; ?>">
                <input type="hidden" name="prix" value="<?php echo $produit['prix']; ?>">
                <button type="submit" class="rounded-pill btn btn-info mt-3 offset-4" name="add_to_cart">Ajouter au panier</button>
            </form>
        </div>
    </div>
</div>
<footer class="bg-dark text-white py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <img src="../css/image/compo.jpg" alt="Footer Image" style="max-width: 80%; height: 100%; object-fit: cover; border-radius: 8px;">
            </div>
             <div class="col-md-3">
                <h5>Livraisons</h5>
                <ul class="list-unstyled">
                    <li><a href="#">Dakar 2000 FCFA</a></li>
                    <li><a href="#">THIAROYE, MBAO, KEUR MASSAR 2500 FCFA</a></li>
                    <li><a href="#">THIES 2500FCFA</a></li>
                    <li><a href="#">AUTRES REGIONS 4000 FCFA</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h5>Nos produits</h5>
                <ul class="list-unstyled">
                    <li><a href="#">Visages</a></li>
                    <li><a href="#">Corps</a></li>
                    <li><a href="#">Parfums</a></li>
                    <li><a href="#">Maquillages</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h5>Contacts</h5>
                <ul class="list-unstyled">
                    <li><a href="#"><i class="fa fa-map-marker"></i> Sénégal-Dakar</a></li>
                    <li><a href="#"><i class="fas fa-phone"></i> 78 132 69 69</a></li>
                    <li><a href="#"><i class="fa fa-envelope"></i> amsikshop@gmail.com</a></li>
                </ul>
            </div>
        </div>
        <hr>
        <div class="row offset-4">
            <div class="col-md-12">
                <h3>Développé par :  <span style="margin-right: 5px;">AHAMADI NASRY, ahamadinasry@gmail.com</span></h3>
            </div>
        </div>
    </div>
    <div class="text-center py-2" style="background-color: #333; color: #fff; border-top: 1px solid #666;">
        &copy; 2024 Amsik Shop. Tous droits réservés.
    </div>
  <div id="scrollToTop" class="show category-card">
    <i class="fa fa-chevron-up"></i>
  </div>
</footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <script>
    $(window).scroll(function () {
      if ($(this).scrollTop() > 200) {
        $('#scrollToTop').addClass('show');
      } else {
        $('#scrollToTop').removeClass('show');
      }
    });
    $('#scrollToTop').click(function () {
      $('html, body').animate({ scrollTop: 0 }, 'slow');
      return false;
    });
  </script>
</body>
</html>