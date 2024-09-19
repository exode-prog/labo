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
  $etat = validateInput($_POST['etat']); 
  $typecompte = validateInput($_POST['typecompte']); 
  if (empty($prenom) || empty($nom) || empty($sexe) || empty($adresse) || empty($telephone) || empty($password) || empty($etat) || empty($typecompte)) {
    $message = "Veuillez renseigner tous les champs";
  } else {
    if (strlen($password) >= 8) {
      $email = strtolower($prenom) . '.' . strtolower($nom) . '@keuryabt.com';
      $requeteEmail = "SELECT COUNT(*) AS count FROM utilisateurs WHERE emailu = :email";
      $resultatEmail = $connexion->prepare($requeteEmail);
      $resultatEmail->bindParam(':email', $email, PDO::PARAM_STR);
      $resultatEmail->execute();
      $countEmail = $resultatEmail->fetchColumn();

      if ($countEmail > 0) {
        $message = "L'adresse e-mail est déjà utilisée.";
      } else {
        $salt = password_hash(random_bytes(16), PASSWORD_DEFAULT);
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $insert = $connexion->prepare("INSERT INTO utilisateurs(prenomu, nomu, sexe, adresse, telephoneu, emailu, password, etat, typecompte,dateajout) VALUES(:prenom, :nom, :sexe, :adresse, :telephone, :email, :password, :etat, :typecompte, NOW())");
        $insert->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        $insert->bindParam(':nom', $nom, PDO::PARAM_STR);
        $insert->bindParam(':sexe', $sexe, PDO::PARAM_STR);
        $insert->bindParam(':adresse', $adresse, PDO::PARAM_STR);
        $insert->bindParam(':telephone', $telephone, PDO::PARAM_STR);
        $insert->bindParam(':email', $email, PDO::PARAM_STR);
        $insert->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        $insert->bindParam(':etat', $etat, PDO::PARAM_STR);
        $insert->bindParam(':typecompte', $typecompte, PDO::PARAM_STR);
        $insert->execute();

        if ($insert) {
          $message = "$nom $prenom ajouté avec succès";
          header("location:../formulaire/utilisateurs.php");
        }
      }
    } else {
      $message = "Le mot de passe doit comporter au moins 8 caractères";
    }
  }
}
?>