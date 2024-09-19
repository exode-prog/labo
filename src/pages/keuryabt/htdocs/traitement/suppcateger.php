<?php  
	include("../bd/connexion.php");
	if(isset($_GET['id']) && is_numeric($_GET['id'])) {
		$id = $_GET['id'];
		$delete = "DELETE FROM categories WHERE idcategorie = :id";
		$statement = $connexion->prepare($delete);
		$statement->bindParam(':id', $id, PDO::PARAM_INT);
		$statement->execute(); 
		header('location:../affichages/categoriesger.php');
	} else {
		header('location:../pages/erreur.php');
	}
?>
