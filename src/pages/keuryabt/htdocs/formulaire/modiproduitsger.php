<?php 
include('../functions/functionsecure.php');
authenticate();
if ($_SESSION['user']->typecompte == 'GERENTE') {
    include("../bd/connexion.php");
    $req = "SELECT * FROM categories";
    $result = $connexion->prepare($req);
    $result->execute();
    $categorie = $result->fetchAll();
    
    if(isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = $_GET['id'];

        $select = "SELECT * FROM produits WHERE idp = :id";
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
  <title>Modifier un Produit</title>
  <link rel="stylesheet" type="text/css" href="../css/webfonts/all.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
    }
    .form-container {
      max-width: 500px;
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
        <a href="../affichages/produitsger.php">
          <button type="button" class="btn btn-dark"><i class="fa fa-chevron-left"></i> Retour</button>
        </a>
      </div>
  <div class="form-container">
    <h1 class="mb-4 text-center">Modifier un Produit</h1>
    <form action="../traitement/traitproduits.php" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?php echo $id; ?>">
      <div class="form-group">
        <label for="inputNom" class="form-label">Nom :</label>
        <input type="text" class="form-control" id="inputNom" name="nom" placeholder="Entrez le nom du produit" value="<?php echo $results['nom']; ?>" required>
        <div class="invalid-feedback">
          Veuillez entrer le nom du produit.
        </div>
      </div>
      <div class="form-group">
        <label for="inputCategorie" class="form-label">Catégorie :</label>
        <select class="rounded-pill form-control" name="idcategorie">
            <?php foreach($categorie as $value): ?>
              <option value="<?php echo $value['idcategorie']; ?>" <?php if($value['idcategorie'] == $results['idcategorie']) echo 'selected'; ?>><?php echo $value['nomcategorie']; ?></option>
            <?php endforeach; ?>
          </select>
      </div>
      <div class="form-group">
        <label for="inputPrix" class="form-label">Prix :</label>
        <input type="number" class="form-control" id="inputPrix" name="prix" placeholder="Entrez le prix du produit" value="<?php echo $results['prix']; ?>" required>
        <div class="invalid-feedback">
          Veuillez entrer le prix du produit.
        </div>
      </div>
      <div class="form-group">
        <label for="inputQuantite" class="form-label">Quantité :</label>
        <input type="number" class="form-control" id="inputQuantite" name="quantite" placeholder="Entrez la quantité du produit" value="<?php echo $results['quantite']; ?>" required>
        <div class="invalid-feedback">
          Veuillez entrer la quantité du produit.
        </div>
      </div>
      <div class="form-group">
        <label for="inputImage" class="form-label">Image :</label>
        <input type="file" class="form-control" id="inputImage" name="img" accept="image/*">
        <div class="invalid-feedback">
          Veuillez sélectionner une image.
        </div>
      </div>
      <div class="form-group">
        <label for="inputDetails" class="form-label">Détails :</label>
        <textarea class="form-control" id="inputDetails" name="details" placeholder="Entrez les détails du produit" required><?php echo $results['details']; ?></textarea>
        <div class="invalid-feedback">
          Veuillez entrer les détails du produit.
        </div>
      </div>
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
    if(isset($_POST['modifier'])) {
        // Récupération des données du formulaire
        $id = $_POST['id'];
        $nom = $_POST['nom'];
        $idcategorie = $_POST['idcategorie'];
        $prix = $_POST['prix'];
        $quantite = $_POST['quantite'];
        $details = $_POST['details'];

        $update = "UPDATE produits SET nom = :nom, idcategorie = :idcategorie, prix = :prix, quantite = :quantite, details = :details WHERE idp = :id";
        $statement = $connexion->prepare($update);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->bindParam(':nom', $nom, PDO::PARAM_STR);
        $statement->bindParam(':idcategorie', $idcategorie, PDO::PARAM_INT);
        $statement->bindParam(':prix', $prix, PDO::PARAM_INT);
        $statement->bindParam(':quantite', $quantite, PDO::PARAM_INT);
        $statement->bindParam(':details', $details, PDO::PARAM_STR);
        $statement->execute();
        header('location:../affichages/produitsger.php');
    }
?>