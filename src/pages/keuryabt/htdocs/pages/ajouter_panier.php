<?php 
    require('../bd/connexion.php');

    $carts = [];
    // Vérifier si le cookie "paniers" existe
    if(isset($_COOKIE["paniers"])) {
        $paniers = unserialize($_COOKIE["paniers"]);
        foreach($paniers as $panier){
            $id = $panier["id"];
            $quantite = $panier["qte"];
            // Préparer la requête SQL pour sélectionner les détails du produit
            $select = "SELECT * FROM produits WHERE idp= :id";
            $execut = $connexion->prepare($select);
            $execut->bindParam(':id', $id, PDO::PARAM_INT);
            $execut->execute();
            // Récupérer les détails du produit
            $cart = $execut->fetch(PDO::FETCH_OBJ);
            // Ajouter les informations supplémentaires au panier
            $cart->qte = $quantite;
            $cart->total = intval($quantite) * intval($cart->prix);
            $carts[] = $cart;
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Panier</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- **************** BOOTSTRAP -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../css/panier.css">
</head>
<body style="background-color: #e9ecef;">

<style type="text/css">
    #anene {
        text-align: center; 
        color: #ffffff; 
        border-radius: 20% / 500px; 
        background-color: #af094b; 
        width: 58%; 
        margin-left: 25%; 
        margin-top: 3px;
    }   
</style>
<h1 align="center">LISTE DU PANIER</h1>
<div class="cart_section">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10 offset-lg-1">
                <div class="cart_container">
                    <?php foreach($carts as $value): ?>
                    <div class="cart_title">Panier<small> (1 un article dans votre panier) </small></div>
                    <div class="cart_items">
                        <ul class="cart_list">
                            <li class="cart_item clearfix">
                                <div class="cart_item_image"><img src="../photo/<?php echo $value->photo; ?>" style='width: 200%;'></div>
                                <div class="cart_item_info d-flex flex-md-row flex-column justify-content-between">
                                    <div class="cart_item_name cart_info_col">
                                        <div class="cart_item_title">Nom</div>
                                        <div class="cart_item_text"><?php echo $value->nom; ?></div>
                                    </div>
                                    <div class="cart_item_quantity cart_info_col">
                                        <div class="cart_item_title">Quantite</div>
                                        <!-- Ajout d'un champ de quantité modifiable -->
                                        <input type="number" name="quantite" value="<?php echo $value->qte; ?>" min="1" placeholder="quantite" required>
                                    </div>
                                    <div class="cart_item_price cart_info_col">
                                        <div class="cart_item_title">Prix</div>
                                        <div class="cart_item_text"><?php echo $value->prix; ?></div>
                                    </div>
                                    <div class="cart_item_total cart_info_col">
                                        <div class="cart_item_title">Total</div>
                                        <div class="cart_item_text"><?php echo $value->total; ?></div>
                                    </div>
                                    <div class="cart_item_total cart_info_col">
                                        <div class="cart_item_title">Supprimer</div>
                                        <!-- Ajout d'un bouton pour supprimer le produit -->
                                        <div class="cart_item_text"><button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> </button></div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
