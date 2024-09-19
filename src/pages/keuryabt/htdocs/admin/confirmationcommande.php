<?php
include("../bd/connexion.php");
session_start();

// Obtenir la page actuelle à partir des paramètres de requête ou définir la page par défaut à 1
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) $page = 1;

// Nombre d'utilisateurs par page
$usersPerPage = 1;

// Calculer l'offset
$offset = ($page - 1) * $usersPerPage;

// Requête SQL pour obtenir les utilisateurs qui ont des commandes
$userQuery = "SELECT c.id_client, c.prenomc, c.nomc, c.emailc 
              FROM clients c 
              WHERE c.id_client IN (SELECT DISTINCT id_client FROM commandes)
              LIMIT $offset, $usersPerPage";
$userResult = $connexion->query($userQuery);

if ($userResult->rowCount() > 0) {
    $user = $userResult->fetch(PDO::FETCH_ASSOC);

    // Requête SQL pour obtenir les commandes avec les produits et les promotions
    $ordersQuery = "SELECT cp.id_commande, cp.idp, cp.quantite, cp.prix_unitaire, p.nom, p.photo, co.dateajout, co.etat, pr.nom AS promo_nom, pr.details AS promo_details
                    FROM commande_produits cp
                    INNER JOIN produits p ON cp.idp = p.idp
                    LEFT JOIN promotions pr ON cp.id_promo = pr.id_promo
                    INNER JOIN commandes co ON cp.id_commande = co.id_commande
                    WHERE co.id_client = :id_client
                    ORDER BY co.dateajout DESC";
    $stmt = $connexion->prepare($ordersQuery);
    $stmt->execute(['id_client' => $user['id_client']]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Requête SQL pour obtenir le nombre total de clients ayant passé des commandes
$totalUsersQuery = "SELECT COUNT(DISTINCT id_client) AS total FROM commandes";
$totalUsersResult = $connexion->query($totalUsersQuery);
$totalUsersRow = $totalUsersResult->fetch(PDO::FETCH_ASSOC);
$totalUsers = $totalUsersRow['total'];

// Calculer le nombre total de pages
$totalPages = ceil($totalUsers / $usersPerPage);

// Fermer la connexion
$connexion = null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Commandes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <link rel="shortcut icon" type="image/png" href="../css/image/WhatsApp Image 2024-05-19 at 11.25.42 (1).jpeg">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1000px;
            margin: 100px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
        }
        h2 {
            margin-bottom: 20px;
            color: #4CAF50;
            font-size: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .user-section {
            margin-bottom: 30px;
        }
        .validate-btn {
            margin-top: 10px;
            text-align: right;
        }
        .pagination {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center">Liste des Commandes</h2>
        <?php if (!empty($user) && !empty($orders)): ?>
            <div class="user-section">
                <h3><?php echo htmlspecialchars($user['prenomc']) . ' ' . htmlspecialchars($user['nomc']) . ' (' . htmlspecialchars($user['emailc']) . ')'; ?></h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Quantité</th>
                            <th>Prix</th>
                            <th>Photo</th>
                            <th>Etat</th>
                            <th>Date Ajout</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['nom']); ?></td>
                                <td><?php echo htmlspecialchars($order['quantite']); ?></td>
                                <td><?php echo htmlspecialchars($order['prix_unitaire']); ?></td>
                                <td><img src="../photo/<?php echo htmlspecialchars($order['photo']); ?>" style='width: 55px;'></td>
                                <td><?php echo htmlspecialchars($order['etat']); ?></td>
                                <td><?php echo htmlspecialchars($order['dateajout']); ?></td>
                            </tr>
                            <?php if (!empty($order['promo_nom'])): ?>
                                <tr>
                                    <td colspan="6">
                                        <strong>Promotion :</strong> <?php echo htmlspecialchars($order['promo_nom']); ?> - <?php echo htmlspecialchars($order['promo_details']); ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="validate-btn">
                    <form method="POST" action="../traitement/traitconfirmation.php">
                        <input type="hidden" name="id_client" value="<?php echo htmlspecialchars($user['id_client']); ?>">
                        <?php foreach ($orders as $order): ?>
                            <input type="hidden" name="id_commande[]" value="<?php echo htmlspecialchars($order['id_commande']); ?>">
                        <?php endforeach; ?>
                        <button type="submit" class="btn btn-success">Valider les Commandes</button>
                    </form>
                </div>
                <div class="validate-btn">
                    <form method="GET" action="../admin/reçu.php">
                        <input type="hidden" name="id_client" value="<?php echo htmlspecialchars($user['id_client']); ?>">
                        <button type="submit" class="btn btn-info">Reçu</button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <p>Aucun utilisateur avec des commandes trouvées.</p>
        <?php endif; ?>

        <div class="pagination">
            <a class="btn btn-primary <?php echo ($page <= 1) ? 'disabled' : ''; ?>" href="?page=<?php echo $page - 1; ?>">Précédent</a>
            <a class="btn btn-primary <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>" href="?page=<?php echo $page + 1; ?>">Suivant</a>
        </div>
    </div>
</body>
</html>
