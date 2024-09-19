<?php
session_start();
require('../bd/connexion.php');

// Ajouter un produit ou une promotion au panier
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    // Valider et nettoyer les données entrantes
    $id_produit = isset($_POST["idp"]) ? intval($_POST["idp"]) : null;
    $id_promo = isset($_POST["id_promo"]) ? intval($_POST["id_promo"]) : null;
    $qte = isset($_POST["quantite"]) ? intval($_POST["quantite"]) : null;
    $prix = isset($_POST["prix"]) ? floatval($_POST["prix"]) : null;

    if (($id_produit || $id_promo) && $qte && $prix && $qte > 0 && $prix > 0) {
        if (isset($_SESSION['user'])) {
            // Utilisateur connecté - Ajouter au panier en base de données
            $id_client = $_SESSION['user']->id_client;

            // Vérifier si le panier existe pour l'utilisateur
            $query = $connexion->prepare("SELECT id_panier FROM paniers WHERE id_client = :id_client AND statut = 'en cours'");
            $query->bindParam(':id_client', $id_client, PDO::PARAM_INT);
            $query->execute();
            $panier = $query->fetch(PDO::FETCH_ASSOC);

            if (!$panier) {
                // Créer un nouveau panier s'il n'existe pas encore pour cet utilisateur
                $insertPanier = $connexion->prepare("INSERT INTO paniers (id_client, date_creation, statut) VALUES (:id_client, NOW(), 'en cours')");
                $insertPanier->bindParam(':id_client', $id_client, PDO::PARAM_INT);
                $insertPanier->execute();
                $id_panier = $connexion->lastInsertId();
            } else {
                $id_panier = $panier['id_panier'];
            }

            // Vérifier si le produit ou la promotion est déjà dans le panier
            $query_item = $connexion->prepare("SELECT id FROM panier_produits WHERE id_panier = :id_panier AND idp = :id_produit");
            $query_item->bindParam(':id_panier', $id_panier, PDO::PARAM_INT);
            $query_item->bindParam(':id_produit', $id_produit, PDO::PARAM_INT);
            $query_item->execute();
            $existing_item = $query_item->fetch(PDO::FETCH_ASSOC);

            if ($existing_item) {
                // Élément déjà dans le panier, augmenter la quantité
                $id_produit_panier = $existing_item['id'];
                $update = $connexion->prepare("UPDATE panier_produits SET quantite = quantite + :qte WHERE id = :id_produit_panier");
                $update->bindParam(':qte', $qte, PDO::PARAM_INT);
                $update->bindParam(':id_produit_panier', $id_produit_panier, PDO::PARAM_INT);
                $update->execute();
            } else {
                // Ajouter le produit ou la promotion au panier
                $insertProduit = $connexion->prepare("INSERT INTO panier_produits (id_panier, idp,id_promo,quantite, prix_unitaire) VALUES (:id_panier, :id_produit,:id_promo, :qte, :prix)");
                $insertProduit->bindParam(':id_panier', $id_panier, PDO::PARAM_INT);
                $insertProduit->bindParam(':id_produit', $id_produit, PDO::PARAM_INT);
                $insertProduit->bindParam(':id_promo', $id_promo, PDO::PARAM_INT);
                $insertProduit->bindParam(':qte', $qte, PDO::PARAM_INT);
                $insertProduit->bindParam(':prix', $prix, PDO::PARAM_STR);
                $insertProduit->execute();
            }
        } else {
            // Utilisateur non connecté - Gestion du panier temporaire avec session
            if (!isset($_SESSION['panier'])) {
                $_SESSION['panier'] = [];
            }

            // Gérer le panier temporaire
            $item = [
                'id_produit' => $id_produit,
                'id_promo' => $id_promo,
                'quantite' => $qte,
                'prix' => $prix
            ];

            // Ajouter l'article au panier temporaire
            $_SESSION['panier'][] = $item;
        }

        $_SESSION['success_message'] = "L'élément a été ajouté au panier avec succès.";
        header('Location: ../index.php');
        exit();
    } else {
        $_SESSION['error_message'] = "Veuillez fournir des informations valides pour ajouter l'élément au panier.";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
}

// Redirection vers la page précédente si aucune action spécifique n'est détectée
if (!isset($_POST['add_to_cart'])) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

?>
