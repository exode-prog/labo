<?php
session_start();
include("../bd/connexion.php");

// Lorsque le bouton "ok" est cliqué
if (isset($_POST['ok'])) {
    // Récupération sécurisée des données du formulaire
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    // Vérification si les champs sont bien remplis
    if (!empty($email) && !empty($password)) {
        // Sélection de l'utilisateur avec une condition WHERE en utilisant une requête préparée
        $select = $connexion->prepare("SELECT * FROM utilisateurs WHERE emailu = :email");
        $select->bindParam(':email', $email);
        $select->execute();
        $result = $select->fetch(PDO::FETCH_OBJ);

        // Vérification si l'utilisateur existe et si le mot de passe est correct
        if ($result && password_verify($password, $result->password)) {
            if ($result->etat == 'OUI') {
                // Stockage des informations utilisateur dans la session
                $_SESSION['user'] = $result;

                // Redirection en fonction du typecompte
                switch ($result->typecompte) {
                    case "ADMIN":
                        header('Location: ../admin/menuadmin.php'); // Redirige vers la page d'administration
                        exit; // S'assurer que le script s'arrête après la redirection
                    case "GERENTE":
                        header('Location: ../admin/menugerent.php');
                        exit;
                    default:
                        $_SESSION['erreurLogin'] = "Type de compte inconnu.";
                        header('Location: ../admin/index.php');
                        exit;
                }
            } else {
                // Si le compte est désactivé
                $_SESSION['erreurLogin'] = "<strong>Erreur!</strong> Votre compte est désactivé. Veuillez contacter l'administrateur.";
                header('Location: ../admin/pageinterdit.php');
                exit;
            }
        } else {
            // Si l'utilisateur n'existe pas ou le mot de passe est incorrect
            $_SESSION['erreurLogin'] = "<strong>Erreur!</strong> Identifiant ou mot de passe incorrect.";
            header('Location: ../admin/index.php');
            exit;
        }
    } else {
        // Si les champs email ou mot de passe sont vides
        $_SESSION['erreurLogin'] = "<strong>Erreur!</strong> Veuillez remplir tous les champs.";
        header('Location: ../admin/index.php');
        exit;
    }
}
?>
