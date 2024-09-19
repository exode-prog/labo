<?php  
	include("../bd/connexion.php");
	if(isset($_GET['id']) && is_numeric($_GET['id'])) {
		$id = $_GET['id'];
		$delete = "DELETE FROM promotions WHERE id_promotion = :id";
		$statement = $connexion->prepare($delete);
		$statement->bindParam(':id', $id, PDO::PARAM_INT);
		$statement->execute(); 
		header('location:../affichages/promotionsger.php');
	} else {
		header('location:../pages/erreur.php');
	}
?>
