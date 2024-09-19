<?php
 include('../functions/functionsecure.php');
     authenticate();
if ($_SESSION['user']->typecompte == 'GERENTE') {
include("../bd/connexion.php");
$req = "SELECT * FROM categories";
$result = $connexion->prepare($req);
$result->execute();
$categorie = $result->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>amsik shop | Formulaire d'Ajout de Produit</title>
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
        <a href="../admin/produitsger.php">
          <button type="button" class="btn btn-dark"><i class="fa fa-chevron-left"></i> Retour</button>
        </a>
      </div>
  <div class="form-container">
    <h1 class="mb-4 text-center">Ajouter un Produit</h1>
    <form action="../traitement/traitproduits.php" method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label for="inputNom" class="form-label">Nom :</label>
        <input type="text" class="form-control" id="inputNom" name="nom" placeholder="Entrez le nom du produit" required>
        <div class="invalid-feedback">
          Veuillez entrer le nom du produit.
        </div>
      </div>
      <div class="form-group">
        <label for="inputCategorie" class="form-label">Catégorie :</label>
        <select class="rounded-pill form-control" name="idcategorie">
            <?php foreach($categorie as $value): ?>
              <option value="<?php echo $value['idcategorie']; ?>"><?php echo $value['nomcategorie']; ?></option>
            <?php endforeach; ?>
          </select>
      </div>
      <div class="form-group">
        <label for="inputPrix" class="form-label">Prix :</label>
        <input type="number" class="form-control" id="inputPrix" name="prix" placeholder="Entrez le prix du produit" required>
        <div class="invalid-feedback">
          Veuillez entrer le prix du produit.
        </div>
      </div>
      <div class="form-group">
        <label for="inputQuantite" class="form-label">Quantité :</label>
        <input type="number" class="form-control" id="inputQuantite" name="quantite" placeholder="Entrez la quantité du produit" required>
        <div class="invalid-feedback">
          Veuillez entrer la quantité du produit.
        </div>
      </div>
      <div class="form-group">
        <label for="inputImage" class="form-label">Image :</label>
        <input type="file" class="form-control" id="inputImage" name="img" accept="image/*" required>
        <div class="invalid-feedback">
          Veuillez sélectionner une image.
        </div>
      </div>
      <div class="form-group">
        <label for="inputDetails" class="form-label">Détails :</label>
        <textarea class="form-control" id="inputDetails" name="details" placeholder="Entrez les détails du produit" required></textarea>
        <div class="invalid-feedback">
          Veuillez entrer les détails du produit.
        </div>
      </div>
      <button type="submit" class="btn btn-primary" name="ok">Ajouter</button>
    </form>
  </div>
</div>
<script src="../css/public/js/bootstrap.min.js"></script>
<?php
 } 
?>
</body>
</html>