<?php
include("../bd/connexion.php");

// Initialisation des variables
$client = null;
$orders = [];
$sous_total = 0;
$total = 0;

if (isset($_GET['id_client'])) {
    $id_client = intval($_GET['id_client']);

    // Récupérer les informations du client
    $clientQuery = "SELECT prenomc, nomc, emailc FROM clients WHERE id_client = :id_client";
    $stmtClient = $connexion->prepare($clientQuery);
    $stmtClient->bindParam(':id_client', $id_client, PDO::PARAM_INT);
    $stmtClient->execute();
    $client = $stmtClient->fetch(PDO::FETCH_ASSOC);

    // Récupérer les commandes du client avec les détails des produits et livraison
    $ordersQuery = "SELECT cp.idp, cp.quantite, cp.prix_unitaire, produit.photo, produit.nom, co.id_commande, co.dateajout, co.etat, livraison.ville, livraison.montantlivraison, co.modepaiement
                    FROM commandes co 
                    JOIN commande_produits cp ON co.id_commande = cp.id_commande
                    JOIN produits produit ON cp.idp = produit.idp
                    JOIN livraisons livraison ON co.id_livraison = livraison.id_livraison
                    WHERE co.id_client = :id_client
                    ORDER BY co.dateajout";
    $stmtOrders = $connexion->prepare($ordersQuery);
    $stmtOrders->bindParam(':id_client', $id_client, PDO::PARAM_INT);
    $stmtOrders->execute();
    $orders = $stmtOrders->fetchAll(PDO::FETCH_ASSOC);

    // Calcul du sous-total et du total
    foreach ($orders as $order) {
        $sous_total += $order['prix_unitaire'] * $order['quantite'];
    }

    if (!empty($orders)) {
        $total = $sous_total + $orders[0]['montantlivraison']; // Utilisation de $orders[0] car le montant de livraison est le même pour toutes les commandes du client
    }
}

// Fermer la connexion
$connexion = null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>amsik shop | Reçu de Paiement</title>
    <link rel="shortcut icon" type="image/png" href="../css/image/WhatsApp Image 2024-05-19 at 11.25.42 (1).jpeg">
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }
        .container {
            width: 21cm;
            max-width: 100%;
            padding: 2cm;
            margin: auto;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo img {
            max-width: 200px;
        }
        .header {
            border-bottom: 1px solid #ddd;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            color: #4CAF50;
            font-size: 24px;
        }
        .details {
            margin-bottom: 30px;
        }
        .details table {
            width: 100%;
            border-collapse: collapse;
        }
        .details th, .details td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .details th {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
        }
        .info {
            margin-bottom: 10px;
        }
        .signature {
            margin-top: 50px;
            text-align: center;
        }
        .signature img {
            max-width: 150px;
            margin-bottom: 10px;
        }
        .signature p {
            margin: 0;
            font-weight: bold;
        }
        .seal {
            text-align: center;
            margin-top: 20px;
        }
        .seal img {
            max-width: 100px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="../css/image/image_6483441.JPG" alt="Logo de la boutique">
        </div>
        <div class="header">
            <h2 class="text-center">Reçu de Paiement</h2>
        </div>
        <div class="details">
            <h2 class="text-center">Reçu de <?php echo htmlspecialchars($client['prenomc'] . ' ' . $client['nomc']); ?></h2>
            <table>
                <tr>
                    <th>Produit</th>
                    <th>Photo</th>
                    <th>Prix unitaire</th>
                    <th>Quantité</th>
                </tr>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo htmlspecialchars($order['nom']); ?></td>
                    <td><img src="../photo/<?php echo htmlspecialchars($order['photo']); ?>" style='width: 55px;'></td>
                    <td><?php echo number_format($order['prix_unitaire'], 2, ',', ' '); ?> FCFA</td>
                    <td><?php echo htmlspecialchars($order['quantite']); ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <div class="info">Sous-total: <strong><?php echo number_format($sous_total, 2, ',', ' '); ?> FCFA</strong></div>
            <?php if (!empty($orders)): ?>
            <div class="info">Frais de livraison: <strong><?php echo number_format($orders[0]['montantlivraison'], 2, ',', ' '); ?> FCFA</strong></div>
            <div class="info">Méthode de paiement : <strong><?php echo htmlspecialchars($orders[0]['modepaiement']); ?></strong></div>
            <div class="info">Date : <strong><?php echo htmlspecialchars($orders[0]['dateajout']); ?></strong></div>
            <?php endif; ?>
            <div class="info">Total: <strong><?php echo number_format($total, 2, ',', ' '); ?> FCFA</strong></div>
        </div>
        <div class="seal">
            <img src="../css/image/image_6483441.JPG" alt="Cachet de l'entreprise">
        </div>
        <div class="footer">
            <p>Merci pour votre commande</p>
        </div>
    </div>
</body>
</html>