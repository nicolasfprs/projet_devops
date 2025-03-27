<?php
// Inclure le fichier de configuration qui contient déjà session_start()
include('includes/config.php');

// Rediriger si l'utilisateur est déjà connecté
if(isset($_SESSION['alogin']) && $_SESSION['alogin'] != '') {
    header('Location: dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscription</title>
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.png">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css" media="screen">
    <link rel="stylesheet" href="assets/css/animate-css/animate.min.css" media="screen">
    <link rel="stylesheet" href="assets/css/main.css" media="screen">
    <script src="assets/js/modernizr/modernizr.min.js"></script>
    <style>
        .error-message {
            background-color: #fce4e4;
            border: 1px solid #fcc2c3;
            float: left;
            padding: 0px 30px;
            clear: both;
        }
        .success-message {
            background-color: #e4fce4;
            border: 1px solid #c2fcc3;
            float: left;
            padding: 0px 30px;
            clear: both;
        }
    </style>
</head>
<body class="" style="background-image: url(assets/images/back2.jpg);
    background-color: #ffffff;
    background-size: cover;
    height: 100%;">

    <div class="main-wrapper">
        <div class="">
            <div class="row">
                <div class="col-md-offset-7 col-lg-5">
                    <section class="section">
                        <div class="row mt-40">
                            <div class="col-md-offset-2 col-md-10 pt-50">
                                <div class="row mt-30 ">
                                    <div class="col-md-11">
                                        <div class="panel login-box" style="background: #172541;">
                                            <div class="panel-heading">
                                                <div class="text-center"><br>
                                                    <a href="#">
                                                        <img style="height: 70px" src="assets/images/footer-logo.png">
                                                    </a>
                                                    <br>
                                                    <h3 style="color: white;"><strong>Inscription</strong></h3>
                                                </div>
                                            </div>
                                            <?php if (isset($_SESSION['msgErreur'])) { ?>
                                                <p class="error-message"><?php echo htmlspecialchars($_SESSION['msgErreur']); unset($_SESSION['msgErreur']);?></p><br><br>
                                            <?php } ?>
                                            <?php if (isset($_SESSION['msgSuccess'])) { ?>
                                                <p class="success-message"><?php echo htmlspecialchars($_SESSION['msgSuccess']); unset($_SESSION['msgSuccess']);?></p><br><br>
                                            <?php } ?>
                                            <div class="panel-body p-20">
                                                <form action="process_register.php" method="post">
                                                    <!-- Champ CSRF caché -->
                                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                                    
                                                    <div class="form-group">
                                                        <label for="username" class="control-label">Nom d'utilisateur</label>
                                                        <input type="text" name="username" class="form-control" id="username" placeholder="Nom d'utilisateur" required>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label for="email" class="control-label">Email</label>
                                                        <input type="email" name="email" class="form-control" id="email" placeholder="Email" required>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label for="password" class="control-label">Mot de passe</label>
                                                        <input type="password" name="password" class="form-control" id="password" placeholder="Mot de passe" required>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label for="confirm_password" class="control-label">Confirmer le mot de passe</label>
                                                        <input type="password" name="confirm_password" class="form-control" id="confirm_password" placeholder="Confirmer le mot de passe" required>
                                                    </div>
                                                    
                                                    <div class="form-group mt-20">
                                                        <button type="submit" name="register" class="btn login-btn">S'inscrire</button>
                                                    </div>
                                                    
                                                    <div class="col-sm-6">
                                                        <a href="admin-login.php" class="text-white">Déjà inscrit? Se connecter</a>
                                                    </div>
                                                    
                                                    <div class="col-sm-6 text-right">
                                                        <a href="index.php" class="text-white">Retour à l'accueil</a>
                                                    </div>
                                                    <br>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
    
    <script src="assets/js/jquery/jquery-2.2.4.min.js"></script>
    <script src="assets/js/bootstrap/bootstrap.min.js"></script>
    <script src="assets/js/pace/pace.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>