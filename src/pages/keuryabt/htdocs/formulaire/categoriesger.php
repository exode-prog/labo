<?php
 include('../functions/functionsecure.php');
     authenticate();
if ($_SESSION['user']->typecompte == 'GERENTE') {
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>amsik shop | Formulaire d'Ajout de categorie</title>
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
        <a href="../admin/categoriesger.php">
          <button type="button" class="btn btn-dark"><i class="fa fa-chevron-left"></i> Retour</button>
        </a>
      </div>
  <div class="form-container">
    <h1 class="mb-4 text-center">Ajouter une Categorie</h1>
    <form method="POST" action="../traitement/traitcategorie.php">
      <div class="row mb-3">
        <div class="col">
          <label for="nomcategorie" class="form-label fw-bold">Nom :</label>
          <input type="text" class="form-control" id="nomcategorie" placeholder="Entrez le nom" name="nomcategorie" required>
        </div>
        <div class="col">
          <label for="description" class="form-label fw-bold">Description :</label>
          <textarea class="form-control" name="description"></textarea>
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