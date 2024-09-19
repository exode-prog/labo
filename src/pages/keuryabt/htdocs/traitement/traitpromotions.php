<?php
require_once("../bd/connexion.php");

function validateInput($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

if (isset($_POST['ok'])) {
    $nom = validateInput($_POST['nom']); 
    $idcategorie = validateInput($_POST['idcategorie']); 
    $ancienprix = validateInput($_POST['ancienprix']); 
    $nouveauprix = validateInput($_POST['nouveauprix']); 
    $quantite = validateInput($_POST['quantite']); 
    $details = validateInput($_POST['details']); 

    if (empty($nom) || empty($idcategorie) || empty($ancienprix) || empty($nouveauprix) || empty($quantite) || empty($details)) {
        $message = "Veuillez renseigner tous les champs";
    } else {
        $image = $_FILES['img']; 
        $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
        $file_extension = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));

        if (!in_array($file_extension, $allowed_extensions)) {
            $message = "Seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
        } else {
            $transfert = "../photo/" . $image['name']; 
            if (move_uploaded_file($image['tmp_name'], $transfert)) {
                $insert = $connexion->prepare("INSERT INTO promotions(nom, idcategorie, photo, ancienprix, prix, quantite, details, dateajout) VALUES(:nom, :idcategorie, :photo, :ancienprix, :prix, :quantite, :details, NOW())");
                $insert->bindParam(':nom', $nom, PDO::PARAM_STR);
                $insert->bindParam(':idcategorie', $idcategorie, PDO::PARAM_INT);
                $insert->bindParam(':photo', $image['name'], PDO::PARAM_STR);
                $insert->bindParam(':ancienprix', $ancienprix, PDO::PARAM_STR);
                $insert->bindParam(':prix', $nouveauprix, PDO::PARAM_STR); // Utiliser PDO::PARAM_STR pour les valeurs décimales
                $insert->bindParam(':quantite', $quantite, PDO::PARAM_INT);
                $insert->bindParam(':details', $details, PDO::PARAM_STR);

                $insert->execute();

                if ($insert) {
                    $message = "Promotion ajoutée avec succès";
                    header('location:../formulaire/promotions.php');
                    exit(); // Assurez-vous de sortir du script après la redirection
                }
            } else {
                $message = "Une erreur s'est produite lors du téléchargement du fichier.";
            }
        }
    }
}
?>
