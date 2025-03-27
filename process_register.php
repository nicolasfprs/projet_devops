<?php
// Inclure le fichier de configuration qui contient déjà session_start()
include('includes/config.php');

// Vérifier si le formulaire a été soumis
if (isset($_POST['register'])) {
    // Vérifier le jeton CSRF
    if (!verifyCsrfToken()) {
        $_SESSION['msgErreur'] = "Erreur de sécurité. Veuillez réessayer.";
        header('Location: register.php');
        exit();
    }

    try {
        // Nettoyer et valider les entrées
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];

        // Validation des entrées
        $errors = [];

        // Validation du nom d'utilisateur (alphanumerique, 3-20 caractères)
        if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
            $errors[] = "Le nom d'utilisateur doit contenir entre 3 et 20 caractères alphanumériques.";
        }

        // Validation de l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Adresse email invalide.";
        }

        // Validation du mot de passe (minimum 8 caractères, au moins une lettre et un chiffre)
        if (strlen($password) < 8 || !preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
            $errors[] = "Le mot de passe doit contenir au moins 8 caractères dont au moins une lettre et un chiffre.";
        }

        // Vérifier que les mots de passe correspondent
        if ($password !== $confirmPassword) {
            $errors[] = "Les mots de passe ne correspondent pas.";
        }

        // Vérifier si le nom d'utilisateur existe déjà
        $stmt = $dbh->prepare("SELECT 1 FROM users WHERE UserName = :username");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->fetchColumn()) {
            $errors[] = "Ce nom d'utilisateur est déjà utilisé.";
        }

        // Vérifier si l'email existe déjà
        $stmt = $dbh->prepare("SELECT 1 FROM users WHERE Email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->fetchColumn()) {
            $errors[] = "Cette adresse email est déjà utilisée.";
        }

        // S'il y a des erreurs, les retourner
        if (!empty($errors)) {
            $_SESSION['msgErreur'] = implode("<br>", $errors);
            header('Location: register.php');
            exit();
        }

        // Hachage du mot de passe avec bcrypt
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insertion dans la base de données
        $stmt = $dbh->prepare("INSERT INTO users (UserName, Email, Password, is_admin, updationDate) VALUES (:username, :email, :password, 0, NOW())");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        $stmt->execute();

        // Redirection avec message de succès
        $_SESSION['msgSuccess'] = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
        header('Location: admin-login.php');
        exit();

    } catch (PDOException $e) {
        // Journal des erreurs pour les administrateurs, mais affichage d'un message générique aux utilisateurs
        error_log("Registration error: " . $e->getMessage());
        $_SESSION['msgErreur'] = "Une erreur s'est produite lors de l'inscription. Veuillez réessayer plus tard.";
        header('Location: register.php');
        exit();
    }
} else {
    // Si quelqu'un accède directement à ce script sans passer par le formulaire, rediriger
    header('Location: register.php');
    exit();
}