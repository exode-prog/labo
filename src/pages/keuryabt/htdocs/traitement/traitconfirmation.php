<?php
include("../bd/connexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_client = $_POST['id_client'];
    $id_commandes = $_POST['id_commande'];

    // Vérifier que les commandes sont spécifiées
    if (!empty($id_commandes) && is_array($id_commandes)) {
        // Récupérer l'e-mail du client
        $emailQuery = "SELECT emailc FROM clients WHERE id_client = :id_client";
        $stmtEmail = $connexion->prepare($emailQuery);
        $stmtEmail->bindParam(':id_client', $id_client, PDO::PARAM_INT);
        $stmtEmail->execute();
        $emailResult = $stmtEmail->fetch(PDO::FETCH_ASSOC);
        $email_client = $emailResult['emailc'];

        // Mettre à jour l'état des commandes de l'utilisateur
        $updateQuery = "UPDATE commandes SET etat = :etat WHERE id_commande = :id_commande";
        $stmt = $connexion->prepare($updateQuery);
        $etat = 'confirmée'; // Vous pouvez personnaliser cela selon vos besoins

        foreach ($id_commandes as $id_commande) {
            $stmt->bindParam(':etat', $etat, PDO::PARAM_STR);
            $stmt->bindParam(':id_commande', $id_commande, PDO::PARAM_INT);
            $stmt->execute();
        }

        // Envoyer les e-mails de confirmation
        // Adresse e-mail du client (expéditeur)
        $from_client = $email_client;

        // Adresse e-mail de l'entreprise (destinataire)
        $to_entreprise = "contact@amsickshop.com";

        // Sujet de l'e-mail pour l'entreprise
        $subject_entreprise = "Nouvelle commande";

        // Contenu de l'e-mail pour l'entreprise
        $message_entreprise = "Une nouvelle commande a été confirmée pour le client. Adresse email du client : $email_client";

        // En-tête de l'e-mail pour l'entreprise
        $headers_entreprise = "From: $from_client" . "\r\n";
        $headers_entreprise .= "Content-Type: text/plain; charset=UTF-8" . "\r\n";

        // Envoyer l'e-mail à l'entreprise
        mail($to_entreprise, $subject_entreprise, $message_entreprise, $headers_entreprise);

        // Adresse e-mail de l'entreprise (expéditeur)
        $from_entreprise = "contact@amsickshop.com";

        // Sujet de l'e-mail pour le client
        $subject_client = "Confirmation de commande";

        // Contenu de l'e-mail pour le client
        $message_client = "Votre commande est confirmée avec succès.";

        // En-tête de l'e-mail pour le client
        $headers_client = "From: $from_entreprise" . "\r\n";
        $headers_client .= "Content-Type: text/plain; charset=UTF-8" . "\r\n";

        // Envoyer l'e-mail au client
        mail($email_client, $subject_client, $message_client, $headers_client);
        
        // Rediriger vers la liste des commandes après validation
        header("Location: ../admin/confirmationcommande.php?page=1");
        exit();
    } else {
        // Gestion des erreurs si aucune commande n'est spécifiée
        echo "Aucune commande spécifiée pour la confirmation.";
    }
}

// Fermer la connexion
$connexion = null;
?>
