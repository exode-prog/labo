<?php
require_once("../bd/connexion.php");
function validateInput($input)
{
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}
if (isset($_POST['ok'])) {
    $nom = validateInput($_POST['nom']); 
    $idcategorie = validateInput($_POST['idcategorie']); 
    $prix = validateInput($_POST['prix']); 
    $quantite = validateInput($_POST['quantite']); 
    $details = validateInput($_POST['details']); 
    if (empty($nom) || empty($idcategorie) || empty($prix) || empty($quantite) || empty($details)) {
        $message = "Veuillez renseigner tous les champs";
    } else {
        $image = $_FILES['img']; 
        $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
        $file_extension = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));

        if (!in_array($file_extension, $allowed_extensions)) {
            $message = "Seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
        } else {
            $transfert = "../photo/".$image['name']; 
            if (move_uploaded_file($image['tmp_name'], $transfert)) {
                $insert = $connexion->prepare("INSERT INTO produits(nom, idcategorie, photo, prix, quantite, details, dateajout) VALUES(:nom, :idcategorie, :photo, :prix, :quantite, :details, NOW())");
                $insert->bindParam(':nom', $nom, PDO::PARAM_STR);
                $insert->bindParam(':idcategorie', $idcategorie, PDO::PARAM_INT);
                $insert->bindParam(':photo', $image['name'], PDO::PARAM_STR);
                $insert->bindParam(':prix', $prix, PDO::PARAM_INT);
                $insert->bindParam(':quantite', $quantite, PDO::PARAM_INT);
                $insert->bindParam(':details', $details, PDO::PARAM_STR);
                $insert->execute();

                if ($insert) {
                    $message = "Produit ajouté avec succès";
                    header('location:../formulaire/produits.php');
                }
            } else {
                $message = "Une erreur s'est produite lors du téléchargement du fichier.";
            }
        }
    }
}
?>