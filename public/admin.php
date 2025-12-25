<?php
require "../config/auth.php";
require "../config/db.php";

// Fetch all submitted applications, newest first
$stmt = $pdo->query("SELECT * FROM applications ORDER BY created_at DESC");
$apps = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>
<link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="container">
  <h2>Tableau de bord Admin</h2>
  <a href="logout.php">Déconnexion</a>

 <?php foreach($apps as $app): ?>
<div class="card">
    <p><strong><?= htmlspecialchars($app['full_name']) ?></strong> (<?= $app['created_at'] ?>)</p>
    <img src="../uploads/signatures/<?= htmlspecialchars($app['signature_path']) ?>" style="width:300px; border:1px solid #ccc;">

    <?php if(empty($app['personal_note'])): ?>
    <form method="POST" action="generate_pdf.php">
        <!-- Hidden input sends the ID directly from the database -->
        <input type="hidden" name="id" value="<?= $app['id'] ?>">
        <textarea name="personal_note" placeholder="Note personnelle / Montant (facultatif)" required></textarea>
        <button type="submit">Finaliser et générer PDF</button>
    </form>
    <?php else: ?>
        <p>✅ Déjà finalisé</p>
    <?php endif; ?>
</div>
<?php endforeach; ?>
</div>
</body>
</html>
