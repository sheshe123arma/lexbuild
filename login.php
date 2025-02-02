<?php
include "include/connect.php";
session_start();
if(isset($_SESSION["name"])){
 header("Location: index.php");
}else{
?>
<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Login</title>

    <!-- Bootstrap core CSS-->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin.css" rel="stylesheet">

  </head>
        <body style="background-color: gray">

   <div class="container">
      <div class="card card-login mx-auto mt-5">

        <div class="card-header">ADMIN LOG IN</div>
        <div class="card-body">
           <center><h1><i class="fas fa-lock"></i></h1></center><br><br>
          <form method="POST" action="login1.php">
            <div class="form-group">
              <div>
                <input type="text" id="inputEmail" class="form-control" placeholder="Username" name="user" required="required" autofocus="autofocus">
              </div>
            </div>
            <div class="form-group">
              <div>
                <input type="password" id="inputPassword" class="form-control" placeholder="Password" name="pass" required="required">
              </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block fas fa-key">Login</button>
          </form>
        </div>
      </div>
    </div>

<!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  </body>

</html>
<?php
}
?>