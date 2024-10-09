<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Définir l'adresse et la méthode de livraison</title>
</head>
<body>
    <header>
        <h1>Le Eshop au top</h1>
    </header>

    <main>
    <form action="../controller/set-shipping-address.php" method="POST">
    <label for="address">Adresse de livraison :</label>
    <input type="text" id="address" name="address" required minlength="2" maxlength="100" 
           pattern="\S.{1,98}\S" 
           title="L'adresse de livraison doit contenir entre 2 et 100 caractères et ne doit pas être uniquement composée d'espaces." 
           placeholder="Entrez votre adresse">
    
    <label for="country">Pays de livraison :</label>
    <select id="country" name="country" required>
        <option value="France">France</option>
        <option value="Belgique">Belgique</option>
        <option value="Luxembourg">Luxembourg</option>
    </select>
    
    <label for="shippingMethod">Méthode de livraison :</label>
    <select id="shippingMethod" name="shippingMethod" required>
        <option value="Chronopost Express">Chronopost Express</option>
        <option value="Point relais">Point relais</option>
        <option value="Domicile">Domicile</option>
    </select>
    
    <button type="submit">Définir l'adresse et la méthode</button>
</form>


    </main>
</body>
</html>
