<?php
require "../config/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $note = $_POST['admin_note'];

    // Update the application with admin note and mark as completed
    $stmt = $pdo->prepare("UPDATE applications SET admin_note=?, status='completed' WHERE id=?");
    $stmt->execute([$note, $id]);

    // Redirect back to dashboard (or generate PDF here)
    header("Location: dashboard.php");
    exit;
}
?>
<?php
require "../config/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Check if ID is sent
    if (empty($_POST['id'])) {
        die("id manquant"); // this fixes your error
    }

    $id = $_POST['id'];
    $admin_note = $_POST['admin_note'] ?? '';

    // Update the application with admin note and mark it as completed
    $stmt = $pdo->prepare("UPDATE applications SET admin_note=?, status='completed' WHERE id=?");
    $stmt->execute([$admin_note, $id]);

    // Fetch the application to generate HTML PDF
    $stmt = $pdo->prepare("SELECT * FROM applications WHERE id=?");
    $stmt->execute([$id]);
    $app = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$app) {
        die("Application introuvable");
    }

    // Generate HTML for PDF / print
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Déclaration</title>
        <style>
            body { font-family: Arial, sans-serif; padding: 20px; }
            h2 { text-align: center; }
            .sig { margin-top: 20px; }
        </style>
    </head>
    <body>
        <h2>Déclaration d'aide</h2>
        <p><strong>Nom :</strong> <?= htmlspecialchars($app['full_name']) ?></p>
        <p><strong>Déclaration :</strong> <?= htmlspecialchars($app['declaration_text']) ?></p>
        <p><strong>Note personnelle / Montant :</strong> <?= htmlspecialchars($app['admin_note']) ?></p>
        <div class="sig">
            <strong>Signature :</strong><br>
            <img src="../uploads/signatures/<?= htmlspecialchars($app['signature_path']) ?>" width="300">
        </div>
        <script>
            // Auto open print dialog
            window.onload = function() {
                window.print();
            }
        </script>
    </body>
    </html>
    <?php
    exit;
}
?>
