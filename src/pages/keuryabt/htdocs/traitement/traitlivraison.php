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
  $ville = validateInput($_POST['ville']); 
  $montant = validateInput($_POST['montant']); 

  if (empty($ville) || empty($montant)) {
    $message = "Veuillez renseigner tous les champs";
  } else {
        $insert = $connexion->prepare("INSERT INTO livraisons(ville,montantlivraison) VALUES(:ville, :montant)");
        $insert->bindParam(':ville', $ville, PDO::PARAM_STR);
        $insert->bindParam(':montant', $montant, PDO::PARAM_STR);
        $insert->execute();

        if ($insert) {
          $message = "$ville ajouté avec succès";
          header("location:../formulaire/livraisons.php");
        }
      }
    } 
?>