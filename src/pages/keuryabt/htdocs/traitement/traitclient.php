<?php
require_once("../bd/connexion.php");

if (isset($_POST['ok'])) {
    function validateInput($input)
    {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;
    }

    $prenom = validateInput($_POST['prenom']);
    $nom = validateInput($_POST['nom']);
    $sexe = validateInput($_POST['sexe']);
    $adresse = validateInput($_POST['adresse']);
    $telephone = validateInput($_POST['telephone']);
    $password = validateInput($_POST['password']);
    $email = validateInput($_POST['email']);
    $typecompte = 'CLIENT';

    if (empty($prenom) || empty($nom) || empty($sexe) || empty($adresse) || empty($telephone) || empty($password) || empty($email)) {
        $message = "Veuillez renseigner tous les champs.";
    } else {
        if (strlen($password) >= 8) {
            $requeteEmail = "SELECT COUNT(*) AS count FROM clients WHERE emailc = :email";
            $resultatEmail = $connexion->prepare($requeteEmail);
            $resultatEmail->bindParam(':email', $email, PDO::PARAM_STR);
            $resultatEmail->execute();
            $countEmail = $resultatEmail->fetchColumn();

            if ($countEmail > 0) {
                $message = "L'adresse e-mail est déjà utilisée.";
            } else {
                // Insertion des données dans la base de données
                $salt = password_hash(random_bytes(16), PASSWORD_DEFAULT);
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $insert = $connexion->prepare("INSERT INTO clients(prenomc, nomc, sexe, adressec, telephonec, emailc, password, profil, dateajout) VALUES(:prenom, :nom, :sexe, :adresse, :telephone, :email, :password, :typecompte, NOW())");
                $insert->bindParam(':prenom', $prenom, PDO::PARAM_STR);
                $insert->bindParam(':nom', $nom, PDO::PARAM_STR);
                $insert->bindParam(':sexe', $sexe, PDO::PARAM_STR);
                $insert->bindParam(':adresse', $adresse, PDO::PARAM_STR);
                $insert->bindParam(':telephone', $telephone, PDO::PARAM_STR);
                $insert->bindParam(':email', $email, PDO::PARAM_STR);
                $insert->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
                $insert->bindParam(':typecompte', $typecompte, PDO::PARAM_STR);
                $insert->execute();

                if ($insert) {
                    // Envoi de l'email de notification
                    $to = $email;
                    $subject = 'Confirmation de création de compte';
                    $message = "
                        <html>
                        <head>
                            <title>Confirmation de création de compte</title>
                        </head>
                        <body>
                            <p>Bonjour $prenom $nom,</p>
                            <p>Votre compte a été créé avec succès sur notre site. Vous pouvez maintenant vous connecter avec votre adresse e-mail et votre mot de passe.</p>
                            <p>Cordialement,</p>
                            <p>L'équipe de keuryabt</p>
                            <br>
                            <img src='https://example.com/logo.png' alt='Logo de l'entreprise'>
                        </body>
                        </html>
                    ";
                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    $headers .= 'From: <contact@keuryabt.com>' . "\r\n";

                    // Envoi du mail
                    mail($to, $subject, $message, $headers);

                    $message = "Votre compte a été créé avec succès. Un email de confirmation a été envoyé à $email.";
                    header("location:../authentification.php");
                    exit();
                } else {
                    $message = "Une erreur s'est produite lors de l'ajout du client.";
                    header("location:../compte.php");
                }
            }
        } else {
            $message = "Le mot de passe doit comporter au moins 8 caractères.";
            header("location:../compte.php");
        }
    }
}
?>