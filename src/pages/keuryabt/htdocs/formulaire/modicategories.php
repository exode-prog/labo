<?php 
include('../functions/functionsecure.php');
authenticate();
if ($_SESSION['user']->typecompte == 'ADMIN') {
    include("../bd/connexion.php");

    // Vérification que l'id est présent et est un entier
    if(isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = $_GET['id'];
        $select = "SELECT * FROM categories WHERE idcategorie = :id";
        $statement = $connexion->prepare($select);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        $results = $statement->fetch(PDO::FETCH_ASSOC);
    } else {
        
        header('location:../pages/erreur.php');
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une Catégorie</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .form-container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="col-md-2">
            <a href="../menu/menu3.php">
                <button type="button" class="btn btn-dark"><i class="fas fa-arrow-circle-left"></i> Retour</button>
            </a>
        </div>
        <div class="form-container">
            <h1 class="mb-4 text-center">Modifier une Catégorie</h1>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="nom" class="form-label fw-bold">Nom :</label>
                    <input type="text" class="form-control" id="nom" name="nom" value="<?php echo $results['nomcategorie']; ?>">
                </div>
                <div class="form-group">
                    <label for="description" class="form-label fw-bold">Description :</label>
                    <textarea class="form-control" id="description" name="description"><?php echo $results['description'];?></textarea>
                </div>
                <button type="reset" class="btn btn-outline-danger">Annuler</button>
                <button type="submit" class="btn btn-primary" name="modifier">Modifier</button>
            </form>
        </div>
    </div>
    <script src="../css/public/js/bootstrap.min.js"></script>
</body>
</html>
<?php 
}
?>
<?php 
// lorsque le boutton enregistrer est cliqué 
if (isset($_POST['modifier'])) {
    extract($_POST); 
    if (empty($nom) || empty($description)) {
        echo "veuillez renseigner tous les champs";
    }else{
        // Requête préparée pour mettre à jour la catégorie
        $update = "UPDATE categories SET nomcategorie=:nom, description=:description WHERE idcategorie=:id";
        $statement = $connexion->prepare($update);
        $statement->bindParam(':nom', $nom, PDO::PARAM_STR);
        $statement->bindParam(':description', $description, PDO::PARAM_STR);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        if ($statement) {
            header('location:../affichages/categories.php');
            //$message = "produit ajouté avec succès";
            echo "categorie modifiée avec succès";
        }
    }
}
?>