<?php
require "../config/db.php";
session_start();

if($_SERVER["REQUEST_METHOD"]==="POST"){
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username=?");
    $stmt->execute([$_POST['username']]);
    $admin = $stmt->fetch();

    if($admin && password_verify($_POST['password'], $admin['password'])){
        $_SESSION['admin_id'] = $admin['id'];
        header("Location: admin.php");
        exit;
    } else {
        $error="Login incorrect";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login</title>
<link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="container">
  <h2>Connexion Admin</h2>
  <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
  <form method="POST">
      <input type="text" name="username" required placeholder="Nom d'utilisateur">
      <input type="password" name="password" required placeholder="Mot de passe">
      <button type="submit">Se connecter</button>
  </form>
</div>
</body>
</html>
