<?php
include("../bd/connexion.php");
session_start();

// Définir le fuseau horaire
date_default_timezone_set('Africa/Dakar');

// Si le formulaire de validation est soumis
if(isset($_POST['ok'])) {
    function validateInput($input)
    {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;
    }

    // Récupérer l'ID du client depuis la session
    $id_client = $_SESSION['user']->id_client;

    // Requête SQL pour récupérer les informations du client, y compris son email et son numéro de téléphone
    $requete_client = "SELECT emailc, telephonec FROM clients WHERE id_client = :id_client";
    $resultat_client = $connexion->prepare($requete_client);
    $resultat_client->bindParam(':id_client', $id_client);
    $resultat_client->execute();

    // Vérifier s'il y a un résultat
    if($resultat_client->rowCount() > 0) {
        // Récupérer l'email et le téléphone du client
        $row = $resultat_client->fetch(PDO::FETCH_ASSOC);
        $email_client = $row['emailc'];
        $telephone_client = $row['telephonec'];

        // Récupérer les ID des paniers sélectionnés
        $id_paniers = $_POST['id_panier'];

        // Récupérer l'ID de livraison depuis le formulaire
        $id_livraison = validateInput($_POST['id_livraison']);
        if ($id_livraison === 'Choisissez une livraison') {
            $id_livraison = null;
        }

        // Récupérer les autres champs depuis le formulaire
        $pays = validateInput($_POST['pays']);
        $ville = validateInput($_POST['ville']);
        $modepaiement = $_POST['moyenpayement'];

        // Pour chaque panier sélectionné, insérer la commande dans la base de données
        foreach($id_paniers as $id_panier) {
            // Insérer la commande dans la table commandes
            $requete_insert_commande = "INSERT INTO commandes (id_client, id_livraison, pays, ville, modepaiement, etat, dateajout) 
                                        VALUES (:id_client, :id_livraison, :pays, :ville, :modepaiement, :etat, NOW())";
            $resultat_insert_commande = $connexion->prepare($requete_insert_commande);
            $resultat_insert_commande->bindParam(':id_client', $id_client);

            // Gestion de l'ID de livraison
            if ($id_livraison !== null) {
                $resultat_insert_commande->bindParam(':id_livraison', $id_livraison);
            } else {
                $resultat_insert_commande->bindValue(':id_livraison', null, PDO::PARAM_NULL);
            }

            $resultat_insert_commande->bindParam(':pays', $pays);
            $resultat_insert_commande->bindParam(':ville', $ville);
            $resultat_insert_commande->bindParam(':modepaiement', $modepaiement);
            $etat = "En attente";
            $resultat_insert_commande->bindParam(':etat', $etat);
            $resultat_insert_commande->execute();

            // Récupérer l'ID de la commande insérée
            $id_commande = $connexion->lastInsertId();

            // Sélectionner les produits du panier pour les insérer dans commande_produits
            $select_panier_produits = "SELECT idp, quantite, prix_unitaire, id_promo FROM panier_produits WHERE id_panier = :id_panier";
            $stmt_panier_produits = $connexion->prepare($select_panier_produits);
            $stmt_panier_produits->bindParam(':id_panier', $id_panier);
            $stmt_panier_produits->execute();
            $panier_items = $stmt_panier_produits->fetchAll(PDO::FETCH_ASSOC);

            foreach ($panier_items as $item) {
                $id_produit = $item['idp'];
                $quantite = $item['quantite'];
                $prix_unitaire = $item['prix_unitaire'];
                $id_promo = $item['id_promo']; // Récupérer l'ID de la promotion

                // Insérer les détails du produit dans commande_produits
                $insert_commande_produits = "INSERT INTO commande_produits (id_commande, idp, id_promo, quantite, prix_unitaire) 
                                             VALUES (:id_commande, :id_produit, :id_promo, :quantite, :prix_unitaire)";
                $stmt_insert_produits = $connexion->prepare($insert_commande_produits);
                $stmt_insert_produits->bindParam(':id_commande', $id_commande);
                $stmt_insert_produits->bindParam(':id_produit', $id_produit);
                $stmt_insert_produits->bindParam(':id_promo', $id_promo);
                $stmt_insert_produits->bindParam(':quantite', $quantite);
                $stmt_insert_produits->bindParam(':prix_unitaire', $prix_unitaire);
                $stmt_insert_produits->execute();
            }

            // Après avoir inséré la commande, supprimer les produits du panier
            $delete_panier_items = "DELETE FROM panier_produits WHERE id_panier = :id_panier";
            $stmt_delete_panier_items = $connexion->prepare($delete_panier_items);
            $stmt_delete_panier_items->bindParam(':id_panier', $id_panier);
            $stmt_delete_panier_items->execute();

            // Supprimer le panier lui-même
            $delete_panier = "DELETE FROM paniers WHERE id_panier = :id_panier";
            $stmt_delete_panier = $connexion->prepare($delete_panier);
            $stmt_delete_panier->bindParam(':id_panier', $id_panier);
            $stmt_delete_panier->execute();
        }

        // Redirection vers une page de confirmation ou autre
        header('location:../pages/confirmation.php');
        exit;
    }
}
?>
