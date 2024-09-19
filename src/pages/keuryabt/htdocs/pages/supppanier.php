<?php
session_start();
require('../bd/connexion.php');

// Vérifiez si l'ID de suppression est défini dans l'URL
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];

    // Vérifiez si l'utilisateur est connecté
    if (isset($_SESSION['user']) && isset($_SESSION['user']->id_client)) {
        $userId = $_SESSION['user']->id_client;

        try {
            // Supprimez le produit du panier dans la base de données
            $deleteQuery = "DELETE FROM panier_produits 
                            WHERE id = :deleteId 
                            AND id_panier IN (SELECT id_panier FROM paniers WHERE id_client = :userId)";
            $stmt = $connexion->prepare($deleteQuery);
            $stmt->bindParam(':deleteId', $deleteId, PDO::PARAM_INT);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();

            // Vérifiez si la suppression a été effectuée
            if ($stmt->rowCount() > 0) {
                $_SESSION['message'] = "Produit supprimé du panier.";
            } else {
                $_SESSION['message'] = "Produit introuvable ou erreur de suppression.";
            }

        } catch (PDOException $e) {
            $_SESSION['message'] = "Erreur: " . $e->getMessage();
        }

    } else {
        // Utilisateur non authentifié - Supprimez le produit du panier en session
        if (isset($_SESSION['panier'])) {
            foreach ($_SESSION['panier'] as $key => $item) {
                if (isset($item['id_produit']) && $item['id_produit'] == $deleteId) {
                    unset($_SESSION['panier'][$key]);
                    $_SESSION['message'] = "Produit supprimé du panier.";
                    break;
                }
                if (isset($item['id_promo']) && $item['id_promo'] == $deleteId) {
                    unset($_SESSION['panier'][$key]);
                    $_SESSION['message'] = "Produit supprimé du panier.";
                    break;
                }
            }
        } else {
            $_SESSION['message'] = "Panier introuvable.";
        }
    }

    // Redirigez l'utilisateur vers la page du panier après suppression
    header('Location: panier.php');
    exit();
} else {
    // Si l'ID de suppression n'est pas défini, redirigez vers la page du panier
    header('Location: panier.php');
    exit();
}
?>
