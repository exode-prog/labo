<?php
 include('../functions/functionsecure.php');
     authenticate();
if ($_SESSION['user']->typecompte == 'ADMIN') {
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>amsik shop | Ajout d'Utilisateur</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
  <style>
    body {
       font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
    }
    .form-container {
      max-width: 800px;
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
        <a href="../admin/utilisateurs.php">
          <button type="button" class="btn btn-dark"><i class="fa fa-chevron-left"></i> Retour</button>
        </a>
      </div>
  <div class="form-container">
    <h1 class="mb-4 text-center">Ajouter un Utilisateur</h1>
    <form method="POST" action="../traitement/traitutilisateurs.php">
      <div class="row mb-3">
        <div class="col">
          <label for="prenom" class="form-label">Prénom :</label>
          <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Entrez votre prénom" required>
        </div>
        <div class="col">
          <label for="nom" class="form-label">Nom :</label>
          <input type="text" class="form-control" id="nom" name="nom" placeholder="Entrez votre nom" required>
        </div>
      </div>
     <div class="row mb-3">
        <div class="col">
          <label for="sexe" class="form-label">Sexe :</label>
          <select class="form-control" id="sexe_input" name="sexe">
              <option value="choix">--Choisir le sexe--</option>
              <option value="masculin">Masculin</option>
              <option value="feminin">Feminin</option>
          </select>
        </div>
        <div class="col">
          <label for="adresse" class="form-label">Adresse :</label>
          <input type="text" class="form-control" id="adresse" name="adresse" placeholder="Entrez votre adresse" required>
        </div>
      </div>
       <div class="row mb-3">
        <div class="col">
          <label for="telephone" class="form-label">Telephone :</label>
          <input type="text" class="form-control" id="telephone" name="telephone" placeholder="Entrez votre telephone" required>
        </div>
        <div class="col">
          <label for="password" class="form-label">Mot de passe :</label>
          <input type="text" class="form-control" id="password" name="password" placeholder="Entrez votre password" required>
        </div>
      </div>
       <div class="row mb-3">
        <div class="col">
          <label for="statut" class="form-label">Statut :</label>
          <select class="form-control" id="statut_input" name="etat">
              <option value="choix">--Choisir l'etat--</option>
              <option value="OUI">Actif</option>
              <option value="NON">Inactif</option>
          </select>
        </div>
        <div class="col">
          <label for="typecompte">Type de Compte</label>
           <select class="form-control" id="typecompte_input" name="typecompte">
                <option value="choix">--Choisir le typecompte--</option>
                <option value="ADMIN">ADMIN</option>
                <option value="GERENTE">GERENTE</option>
            </select>
        </div>
      </div>
      <button type="submit" class="btn btn-primary" name="ok">Ajouter</button>
    </form>
  </div>
</div>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<?php
} else {
 header('Location: ../admin/pageinterdit.php');
 exit();
}
?>
</body>
</html>