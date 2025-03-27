<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    
    // Vérification des champs
    if ($password !== $confirm_password) {
        echo "Les mots de passe ne correspondent pas.";
    } else {
        // Ici, vous pouvez ajouter le code pour enregistrer l'utilisateur dans la base de données
        echo "Inscription réussie !";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
</head>
<body>
    <h2>Formulaire d'inscription</h2>
    <form action="register.php" method="POST">
        <label for="username">Nom d'utilisateur :</label>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required><br><br>
        
        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required><br><br>
        
        <label for="confirm_password">Confirmation du mot de passe :</label>
        <input type="password" id="confirm_password" name="confirm_password" required><br><br>
        
        <input type="submit" value="S'inscrire">
    </form>
</body>
</html>
