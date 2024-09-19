<?php
include('../functions/functionsecure.php');
     authenticate();
if ($_SESSION['user']->typecompte == 'GERENTE') {
include("../bd/connexion.php");
$req = "SELECT * FROM categories";
$result = $connexion->prepare($req);
$result->execute();
$categories = $result->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>amsik shop | categories</title>
  <link rel="stylesheet" type="text/css" href="../css/webfonts/all.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.3.0/css/buttons.dataTables.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
  <link rel="shortcut icon" type="image/png" href="../css/image/WhatsApp Image 2024-05-19 at 11.25.42 (1).jpeg">
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
        <a href="../admin/categoriesger.php">
          <button type="button" class="btn btn-dark"><i class="fa fa-chevron-left"></i> Retour</button>
        </a>
      </div>
  <h1 class="mb-4 text-center">Tableau des Categories</h1>
  <table id="tableUtilisateurs" class="table table-striped table-bordered">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Nom categorie</th>
        <th>Description</th>
        <th>Modi</th>
        <th>Supp</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($categories as $value): ?>
      <tr>
        <td><?php echo htmlspecialchars($value['idcategorie']); ?></td>
        <td><?php echo htmlspecialchars($value['nomcategorie']); ?></td>
        <td><?php echo htmlspecialchars($value['description']); ?></td>
        <td>
          <a href="../formulaire/modicategoriesger.php?id=<?php echo $value ['idcategorie']; ?>">
          <button class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></button></a></td>
        <td> <a href="../traitement/suppcateger.php?id=<?php echo $value['idcategorie']; ?>" onclick="return confirm('Etes vous sûre de supprimer?')">
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
    // Initialiser DataTables sur votre tableau avec les boutons d'exportation CSV et PDF
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