<?php
session_start();
include("../bd/connexion.php");

if (isset($_POST['ok'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Préparation de la requête pour récupérer l'utilisateur par email
    $select = $connexion->prepare("SELECT * FROM clients WHERE emailc = :email");
    $select->bindParam(':email', $email);
    $select->execute();
    $result = $select->fetch(PDO::FETCH_OBJ);

    // Vérification du mot de passe
    if ($result && password_verify($password, $result->password)) {
        $_SESSION['user'] = $result;

        // Redirection selon le profil de l'utilisateur
        switch ($result->profil) {
            case "CLIENT":
                header('location:../index.php'); 
                break;
            default:
                break;
        }
    } else {
        // Si les identifiants sont incorrects, message d'erreur
        $_SESSION['erreurLogin'] = "<strong>Erreur!</strong> Identifiant ou mot de passe incorrects!";
        header('location:../authentification.php');
    }
}
?>
