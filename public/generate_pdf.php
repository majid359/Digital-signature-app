<?php
require "../config/db.php";

$id = $_GET['id'] ?? null;
if (!$id) die("ID manquant");

$stmt = $pdo->prepare("SELECT * FROM applications WHERE id = ?");
$stmt->execute([$id]);
$app = $stmt->fetch();
if (!$app) die("Application introuvable");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Déclaration</title>
<style>
body { font-family: Arial, sans-serif; margin: 40px; }
.paper { max-width: 800px; margin: auto; border: 1px solid #ccc; padding: 30px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
h2 { text-align: center; text-decoration: underline; }
.signature { margin-top: 40px; }
.signature img { border: 1px solid #000; }
button { margin-top: 20px; padding: 10px 20px; font-size: 16px; cursor: pointer; }
@media print { button { display: none; } }
</style>
</head>
<body>

<div class="paper">
<h2>Déclaration sur l'honneur</h2>

<p>Je soussigné(e) <strong><?= htmlspecialchars($app["full_name"]) ?></strong>, déclare que la somme reçue est un don libre sans obligation.</p>

<p>Note personnelle : <?= nl2br(htmlspecialchars($app["declaration_text"])) ?></p>

<div class="signature">
<p>Signature :</p>
<img src="../uploads/signatures/<?= htmlspecialchars($app["signature_path"]) ?>" width="300" alt="Signature">
</div>

<button onclick="window.print()">Imprimer / Sauvegarder en PDF</button>
</div>

</body>
</html>
