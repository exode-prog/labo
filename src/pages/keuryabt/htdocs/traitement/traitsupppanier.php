<?php
include("../bd/connexion.php");
session_start();

// Vérifier si l'utilisateur est connecté
if(isset($_SESSION['user'])) {
    // Récupérer l'ID du client depuis la session
    $id_client = $_SESSION['user']->id_client;

    // Requête SQL pour supprimer les produits du panier de l'utilisateur
    $requete_suppression_panier = "DELETE FROM paniers WHERE id_client = :id_client";
    $stmt_suppression_panier = $connexion->prepare($requete_suppression_panier);
    $stmt_suppression_panier->bindParam(':id_client', $id_client, PDO::PARAM_INT);

    // Exécuter la requête de suppression
    if($stmt_suppression_panier->execute()) {
        // Rediriger l'utilisateur vers la page d'accueil
        header('Location: ../index.php');
        exit;
    } else {
        // En cas d'échec de la suppression, afficher un message d'erreur ou rediriger vers une page d'erreur
        echo "Une erreur s'est produite lors de la suppression du panier. Veuillez réessayer.";
        // Vous pouvez également rediriger vers une page d'erreur personnalisée
        // header('Location: ../pages/erreur.php');
        // exit;
    }
} else {
    // Si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
    header('Location: ../pages/connexion.php');
    exit;
}
?>
