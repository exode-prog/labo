<?php
include("../bd/connexion.php");
include('../functions/functionsecure.php');
authenticate();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
    header('location:../authentificationcom.php');
    exit;
}

$requet1 = "SELECT * FROM livraisons";
$results1 = $connexion->prepare($requet1);
$results1->execute();
$livraisons = $results1->fetchAll();

// Récupère l'identifiant du client à partir de la session
$id_client = $_SESSION['user']->id_client;

// Requête SQL pour récupérer les informations du client
$requete = "SELECT * FROM clients WHERE id_client = :id_client";
$resultat = $connexion->prepare($requete);
$resultat->bindParam(':id_client', $id_client); 
$resultat->execute();
$client = $resultat->fetch();

// Requête SQL pour récupérer les produits dans le panier de l'utilisateur
$requete_panier = "SELECT p.id_panier, p.idp, pr.nom, pr.photo, p.quantite, p.prix 
                   FROM paniers p 
                   JOIN produits pr ON p.idp = pr.idp 
                   WHERE p.id_client = :id_client";
$resultat_panier = $connexion->prepare($requete_panier);
$resultat_panier->bindParam(':id_client', $id_client); 
$resultat_panier->execute();
$produits_panier = $resultat_panier->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validation de Commande</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Validation de Commande</h2>
        <form action="../traitement/traitcommandes.php" method="POST">
            <h3>Contact</h3>
            <label for="emailc">Prenom et Nom</label>
            <select class="form-control" name="id_client" id="id_inscription">
                <option value="<?php echo $client['id_client']; ?>"><?php echo $client['prenomc']; ?> <?php echo $client['nomc']; ?></option>
            </select>
            <h3>Produits dans le Panier</h3>
            <?php foreach($produits_panier as $produit): ?>
                <div class="mb-3">
                    <label for="produit_<?php echo $produit['id_panier']; ?>"><?php echo $produit['nom']; ?></label>
                    <input type="number" class="form-control" name="quantite_<?php echo $produit['id_panier']; ?>" id="produit_<?php echo $produit['id_panier']; ?>" value="<?php echo $produit['quantite']; ?>">
                    
                    <input type="text" name="id_panier" value="<?php echo $produit['id_panier']; ?>">
                </div>
            <?php endforeach; ?>

            <h3>Livraison</h3>
            <label for="pays">Pays :</label>
            <select id="pays" name="pays" class="form-control" required>
                <option value="Choisir un pays">Choisir un pays</option>
                <option value="Senegal">Senegal</option>
            </select>
            <label for="ville">Ville :</label>
            <select id="ville" name="ville" class="form-control" required>
                <option value="Choisir une ville">Choisir une ville</option>
                <option value="Dakar">Dakar</option>
            </select>

            <h3>Mode d'expédition</h3>
            <label>Choisissez un mode d'expédition :</label>
             <select class="form-control" name="id_livraison">
            <option  value="choix">--Choisir la livraisons--</option>
            <?php foreach($livraisons as $value): ?>
            <option value="<?php echo $value['id_livraison']; ?>"><?php echo $value['ville']; ?> <?php echo $value['montantlivraison']; ?></option>
            <?php endforeach; ?>
            </select>

            <h3>Paiement</h3>
            <label>Choisissez un moyen de paiement :</label>
            <select id="moyenpayement" name="moyenpayement" class="form-control" required>
             <option value="standard_dakar">Choisissez un moyen de paiement</option>
             <option value="standard_dakar">Paiement à la livraison</option>
            </select>
            <input type="submit" value="Valider la commande" name="ok">
        </form>
    </div>
</body>
</html>
