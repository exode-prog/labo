<?php
// Inclure le fichier de fonctions de sécurité
include('../functions/functionsecure.php');
// Authentifier l'utilisateur
authenticate();
// Inclure le fichier de connexion à la base de données
include("../bd/connexion.php");

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header('location:../authentificationcom.php');
    exit;
}

// Récupère l'identifiant du client à partir de la session
$id_client = $_SESSION['user']->id_client;

// Requête SQL pour récupérer les informations du client
$requete_client = "SELECT * FROM clients WHERE id_client = :id_client";
$resultat_client = $connexion->prepare($requete_client);
$resultat_client->bindParam(':id_client', $id_client); 
$resultat_client->execute();
$client = $resultat_client->fetch();

// Requête SQL pour récupérer les paniers du client
$requete_paniers = "SELECT p.id_panier, pp.idp, pp.quantite, pp.prix_unitaire, pr.nom, pr.photo AS produit_photo, promo.photo AS promo_photo, promo.id_promo
                    FROM paniers p
                    JOIN panier_produits pp ON p.id_panier = pp.id_panier
                    LEFT JOIN produits pr ON pp.idp = pr.idp
                    LEFT JOIN promotions promo ON pp.id_promo = promo.id_promo
                    WHERE p.id_client = :id_client";
$resultat_paniers = $connexion->prepare($requete_paniers);
$resultat_paniers->bindParam(':id_client', $id_client); 
$resultat_paniers->execute();
$paniers = $resultat_paniers->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_ASSOC);

// Requête SQL pour sélectionner toutes les livraisons
$requete_livraisons = "SELECT * FROM livraisons";
$resultats_livraisons = $connexion->prepare($requete_livraisons);
$resultats_livraisons->execute();
// Récupérer toutes les livraisons
$livraisons = $resultats_livraisons->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Validation des Commandes</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* Ajoutez votre propre CSS personnalisé ici */
  </style>
</head>
<body>
  <div class="container">
    <h1 class="mt-5">Validation des Commandes</h1>
    <div class="row">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            Informations sur la Commande
          </div>
          <div class="card-body">
            <form method="POST" action="../traitement/traitcommandes.php">
              <div class="mb-3">
                <label class="form-label">ID du Panier</label>
                <!-- Boucle pour afficher les checkbox pour chaque panier -->
                <?php foreach ($paniers as $id_panier => $produits): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="id_panier[]" value="<?php echo $id_panier; ?>" checked>
                        <label class="form-check-label"><?php echo $id_panier; ?></label>
                    </div>
                <?php endforeach; ?>
              </div>
              <div class="mb-3">
                <label for="idClient" class="form-label">ID du Client</label>
                <select class="form-select" name="id_client" id="id_inscription">
                  <option value="<?php echo $client['id_client']; ?>"><?php echo $client['prenomc']; ?> <?php echo $client['nomc']; ?></option>
                </select>
              </div>
              <div class="mb-3">
                <label for="livraison" class="form-label">Livraison</label>
                <select class="form-select" name="id_livraison" id="livraison">
                  <option value="Choisissez une livraison">Choisissez une livraison</option>
                  <!-- Boucle pour afficher les options de livraison -->
                  <?php foreach ($livraisons as $livraison): ?>
                    <option value="<?php echo $livraison['id_livraison']; ?>"><?php echo $livraison['ville']; ?> - <?php echo $livraison['montantlivraison']; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="mb-3">
                <label for="pays" class="form-label">Pays</label>
                <select id="pays" name="pays" class="form-control" required>
                  <option value="Choisir un pays">Choisir un pays</option>
                  <option value="Senegal">Senegal</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="ville" class="form-label">Ville</label>
                <select id="ville" name="ville" class="form-control" required>
                  <option value="Choisir une ville">Choisir une ville</option>
                  <option value="Dakar">Dakar</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="modePaiement" class="form-label">Mode de Paiement</label>
                <select id="moyenpayement" name="moyenpayement" class="form-control" required>
                  <option value="Choisissez un moyen de paiement">Choisissez un moyen de paiement</option>
                  <option value="paiement à la livraison">Paiement à la livraison</option>
                </select>
              </div>
              <button type="submit" class="btn btn-primary" name="ok">Valider la Commande</button>
            </form>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-header">
            Produits dans le Panier
          </div>
          <div class="card-body">
            <?php foreach ($paniers as $id_panier => $produits): ?>
              <!-- Exemple de carte pour afficher les produits -->
              <div class="card mb-3">
                <div class="card-header">Produits du Panier <?php echo $id_panier; ?></div>
                <div class="card-body">
                  <ul class="list-group">
                    <?php foreach ($produits as $produit): ?>
                      <li class="list-group-item">
                       <img src="../photo/<?php echo $produit['promo_photo'] ? $produit['promo_photo'] : $produit['produit_photo']; ?>" alt="<?php echo $produit['nom']; ?>" style="max-width: 100px; max-height: 100px;"><br>
                        <?php echo $produit['nom']; ?> <br> Prix: <?php echo $produit['prix_unitaire']; ?> FCFA <br>Quantité: <?php echo $produit['quantite']; ?>
                      </li>
                    <?php endforeach; ?>
                  </ul>
                </div>
              </div>
            <?php endforeach; ?>
            <!-- Vous pouvez ajouter plus de cartes pour chaque produit dans le panier -->
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
