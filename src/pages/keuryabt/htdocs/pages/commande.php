<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validation de Commande</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <main class="container mt-5">
        <section id="contact">
            <h2>Contact</h2>
            <form>
                <label for="phone">Numéro de portable :</label>
                <input type="tel" id="phone" name="phone" class="form-control" required>
            </form>
        </section>

        <section id="livraison">
            <h2>Livraison</h2>
            <form>
                <label for="country">Pays :</label>
                <select id="country" name="country" class="form-select" required>
                    <option value="" disabled selected>Choisir un pays</option>
                    <!-- Ajoutez ici les options des pays -->
                </select>

                <label for="firstname">Prénom :</label>
                <input type="text" id="firstname" name="firstname" class="form-control" required>

                <label for="lastname">Nom :</label>
                <input type="text" id="lastname" name="lastname" class="form-control" required>

                <label for="address">Adresse :</label>
                <input type="text" id="address" name="address" class="form-control" required>

                <label for="postalcode">Code postal (00000 pour Afrique) :</label>
                <input type="text" id="postalcode" name="postalcode" class="form-control" pattern="[0-9]{5}" required>

                <label for="city">Ville :</label>
                <input type="text" id="city" name="city" class="form-control" required>

                <label for="phone">Téléphone :</label>
                <input type="tel" id="phone" name="phone" class="form-control" required>
            </form>
        </section>

        <section id="mode-expedition">
            <h2>Mode d'expédition</h2>
            <form>
                <label>Choisir les lieux de livraison :</label>
                <input type="checkbox" id="dakar" name="dakar" value="Dakar" class="form-check-input">
                <label for="dakar">Livraison standard Dakar</label><br>
                <!-- Ajoutez ici les autres options de livraison -->
            </form>
        </section>

        <section id="paiement">
            <h2>Paiement</h2>
            <form>
                <input type="radio" id="paiement-livraison" name="paiement" value="Paiement à la livraison" checked class="form-check-input">
                <label for="paiement-livraison">Paiement à la livraison</label><br>
            </form>
        </section>

        <section id="adresse-facturation">
            <h2>Adresse de facturation</h2>
            <form>
                <input type="checkbox" id="adresse-identique" name="adresse-identique" value="adresse-identique" checked class="form-check-input">
                <label for="adresse-identique">Identique à l'adresse de livraison</label><br>
            </form>
        </section>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
