<!DOCTYPE html>

<html>
	<head>
		<title>Le eshop au top</title>
	</head>
	<body>

	<header>
		<h1>Le Eshop au top</h1>
	</header>
	
	<main>
	
	<form method="POST" action="../controller/create-order.php">
    <label for="customerName">Nom du client :</label>
    <input type="text" id="customerName" name="customerName" required minlength="2" maxlength="100" 
           pattern="\S.{1,98}\S" 
           title="Le nom du client doit contenir entre 2 et 100 caractères et ne doit pas être uniquement composé d'espaces." 
           placeholder="Entrez votre nom complet">
    
    <label for="product">Sélectionnez les produits :</label>
    <select id="product" name="products[]" multiple required>
        <option value="tshirt">T-shirt</option>
        <option value="jeans">Jeans</option>
        <option value="shoes">Chaussures</option>
        <option value="short">Short</option>
        <option value="cap">Casquette</option>
        <option value="pull">Pull</option>
    </select>
    
    <button type="submit">Créer la commande</button>
</form>



	</main>

	</body>
</html>