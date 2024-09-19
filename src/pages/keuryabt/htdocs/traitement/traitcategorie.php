<?php
// inclure le fichier connexion.php
require_once("../bd/connexion.php"); 
if (isset($_POST['ok'])) {
  function validateInput($input)
  {
      $input = trim($input);
      $input = stripslashes($input);
      $input = htmlspecialchars($input);
      return $input;
  }
  // elle extrait les champs du formulaire
  $nomcategorie = validateInput($_POST['nomcategorie']); 
  $description = validateInput($_POST['description']); 

  if (empty($nomcategorie) || empty($description)) {
    $message = "Veuillez renseigner tous les champs";
  } else {
        $insert = $connexion->prepare("INSERT INTO categories(nomcategorie,description) VALUES(:nomcategorie, :description)");
        $insert->bindParam(':nomcategorie', $nomcategorie, PDO::PARAM_STR);
        $insert->bindParam(':description', $description, PDO::PARAM_STR);
        $insert->execute();

        if ($insert) {
          $message = "$nomcategorie ajouté avec succès";
          header("location:../formulaire/categories.php");
        }
      }
    } 
?>