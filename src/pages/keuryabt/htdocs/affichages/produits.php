<?php
include('../functions/functionsecure.php');
     authenticate();
if ($_SESSION['user']->typecompte == 'ADMIN') {
include("../bd/connexion.php");
$req = "SELECT * FROM produits";
$result = $connexion->prepare($req);
$result->execute();
$produits = $result->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tableau des Utilisateurs</title>
  <link rel="stylesheet" type="text/css" href="../css/webfonts/all.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.3.0/css/buttons.dataTables.min.css">
  <link rel="shortcut icon" type="image/png" href="../css/image/WhatsApp Image 2024-05-19 at 11.25.42 (1).jpeg">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
  <style type="text/css">
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
    }
  </style>
</head>
<body>
<div class="container mt-5">
   <div class="col-md-2">
        <a href="../admin/produits.php">
          <button type="button" class="btn btn-dark"><i class="fa fa-chevron-left"></i> Retour</button>
        </a>
      </div>
  <h1 class="mb-4 text-center">Tableau des produits</h1>
  <table id="tableUtilisateurs" class="table table-striped table-bordered">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Photo</th>
        <th>Prix</th>
        <th>Quantite</th>
        <th>Details</th>
        <th>Date ajout</th>
        <th>Modi</th>
        <th>Supp</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($produits as $value): ?>
      <tr>
        <td><?php echo htmlspecialchars($value['idp']); ?></td>
        <td><?php echo htmlspecialchars($value['nom']); ?></td>
        <td><img src="../photo/<?php echo htmlspecialchars($value['photo']); ?>" style='width: 55px;'></td>
        <td><?php echo htmlspecialchars($value['prix']); ?></td>
        <td><?php echo htmlspecialchars($value['quantite']); ?></td>
        <td><?php echo htmlspecialchars($value['details']); ?></td>
        <td><?php echo htmlspecialchars($value['dateajout']); ?></td>
        <td><a href="../formulaire/modiproduits.php?id=<?php echo $value ['idp']; ?>">
          <button class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></button></a></td>
        <td>
          <a href="../traitement/suppproduit.php?id=<?php echo $value['idp']; ?>" onclick="return confirm('Etes vous sûre de supprimer?')">
          <button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button></a></td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.0/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.33/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.33/vfs_fonts.js"></script>
<script>
  $(document).ready( function () {
    $('#tableUtilisateurs').DataTable({
      // Activer les boutons d'exportation CSV et PDF
      buttons: [
        'csv', 'pdf'
      ],
      // Activer la pagination, la barre de recherche et afficher 10 entrées par page
      paging: true,
      searching: true,
      pageLength: 10
    });
  });
</script>
<?php
 } 
?>
</body>
</html>