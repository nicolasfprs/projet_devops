<?php
   session_start();
   error_reporting(1);
   include('includes/config.php');
   
   if($_SESSION['alogin']!=''){
      $_SESSION['alogin']='';
   }
   
   if(isset($_POST['login'])) {
      try {
         // Clean and validate input
         $uname = trim($_POST['username']);
         $password = $_POST['password'];
         
         if(empty($uname) || empty($password)) {
            $_SESSION['msgErreur'] = "Veuillez remplir tous les champs.";
         } else {
            // Use prepared statement with PDO
            $stmt = $dbh->prepare("SELECT UserName, Password, is_admin FROM users WHERE UserName = :username");
            $stmt->bindParam(':username', $uname, PDO::PARAM_STR);
            $stmt->execute();
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($user) {
               // For existing users with MD5 passwords (legacy)
               if(strlen($user['Password']) == 32 && $user['Password'] === md5($password)) {
                  // Upgrade to password_hash if using old MD5
                  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                  $updateStmt = $dbh->prepare("UPDATE users SET Password = :password WHERE UserName = :username");
                  $updateStmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
                  $updateStmt->bindParam(':username', $uname, PDO::PARAM_STR);
                  $updateStmt->execute();
                  
                  // Login the user
                  $_SESSION['alogin'] = $user['UserName'];
                  $_SESSION['is_admin'] = $user['is_admin'];
                  header('Location: dashboard.php');
                  exit();
               } 
               // For users with bcrypt passwords (new secure system)
               else if(password_verify($password, $user['Password'])) {
                  $_SESSION['alogin'] = $user['UserName'];
                  $_SESSION['is_admin'] = $user['is_admin'];
                  header('Location: dashboard.php');
                  exit();
               } else {
                  $_SESSION['msgErreur'] = "Mauvais identifiant / mot de passe.";
               }
            } else {
               $_SESSION['msgErreur'] = "Mauvais identifiant / mot de passe.";
            }
         }
      } catch(PDOException $e) {
         // Log error for administrators but show generic message to users
         error_log("Authentication error: " . $e->getMessage());
         $_SESSION['msgErreur'] = "Une erreur s'est produite. Veuillez réessayer plus tard.";
      }
   }
?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Login</title>
      <link rel="icon" type="image/x-icon" href="assets/images/favicon.png">
      <link rel="stylesheet" href="assets/css/bootstrap.min.css" media="screen" >
      <link rel="stylesheet" href="assets/css/font-awesome.min.css" media="screen" >
      <link rel="stylesheet" href="assets/css/animate-css/animate.min.css" media="screen" >
      <link rel="stylesheet" href="assets/css/prism/prism.css" media="screen" >

      <link rel="stylesheet" href="assets/css/main.css" media="screen" >
      <script src="assets/js/modernizr/modernizr.min.js"></script>
	  <style>
	  .error-message {
		  background-color: #fce4e4;
		  border: 1px solid #fcc2c3;
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
                        <div class="col-md-offset-2 col-md-10  pt-50">
                           <div class="row mt-30 ">
                              <div class="col-md-11">
                                <div class="panel login-box" style="    background: #172541;">
                                    <div class="panel-heading">

                                       <div class="text-center"><br>
                                          <a href="#">
                    <img style="height: 70px" src="assets/images/footer-logo.png"></a>
                    <br>
                                          <h3 style="color: white;"> <strong>Login</strong></h3>
                                       </div>
                                    </div>
									<?php if (isset($_SESSION['msgErreur'])) { ?>
									<p class="error-message"><?php echo htmlspecialchars($_SESSION['msgErreur']); unset($_SESSION['msgErreur']);?> </p><br><br>
									<?php } ?>
                                    <div class="panel-body p-20">
                                       <form class="admin-login" method="post">
                                          <div class="form-group">
                                             <label for="inputEmail3" class="control-label">Identifiant</label>
                                             <input type="text" name="username" class="form-control" id="inputEmail3" placeholder="Identifiant">
                                          </div>
                                          <div class="form-group">
                                             <label for="inputPassword3" class="control-label">Mot de passe</label>
                                             <input type="password" name="password" class="form-control" id="inputPassword3" placeholder="Mot de passe">
                                          </div><br>
                                          <div class="form-group mt-20">
                                                <button type="submit" name="login" class="btn login-btn">Se Connecter</button>

                                          </div>
										  <div class="col-sm-6">
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
      <script src="assets/js/jquery-ui/jquery-ui.min.js"></script>
      <script src="assets/js/bootstrap/bootstrap.min.js"></script>
      <script src="assets/js/pace/pace.min.js"></script>
      <script src="assets/js/lobipanel/lobipanel.min.js"></script>
      <script src="assets/js/iscroll/iscroll.js"></script>
      <script src="assets/js/main.js"></script>
      <script>
         $(function(){
         
         });
      </script>
   </body>
</html>