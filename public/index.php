<?php
require "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['full_name'];
    $declaration_text = "Aide informelle et gratuite sans obligation.";
    $sig_data = $_POST['signature_data'];

    $sig_file = uniqid() . ".png";
    file_put_contents("../uploads/signatures/".$sig_file, base64_decode(preg_replace('#^data:image/\w+;base64,#i','',$sig_data)));

    $stmt = $pdo->prepare("INSERT INTO applications (full_name, declaration_text, signature_path) VALUES (?, ?, ?)");
    $stmt->execute([$name, $declaration_text, $sig_file]);

    header("Location: success.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Déclaration d'aide</title>
<link rel="stylesheet" href="../assets/style.css">
<script src="../assets/signature.js"></script>
</head>
<body>
<div class="container">
  <h2>Formulaire d'aide</h2>
  <form id="declarationForm" method="POST" action="save_application.php">
  <input type="text" name="full_name" placeholder="Nom complet" required>
  <p class="disclaimer">
    Aide informelle et gratuite. Aucune rémunération demandée. Toute somme éventuelle est facultative et à titre d’appréciation personnelle.
    </p>
  <div class="sig-wrapper">
      <canvas id="signature"></canvas>
  </div>
  <input type="hidden" name="signature_data" id="signature_data">
  
  <button type="submit">Enregistrer la déclaration</button>
</form>

</div>
<script >
    document.addEventListener("DOMContentLoaded", function() {
    const canvas = document.getElementById("signature");
    const ctx = canvas.getContext("2d");
    const hiddenInput = document.getElementById("signature_data");
    const form = document.getElementById("declarationForm");

    let drawing = false;

    // Resize canvas to fit container
    function resizeCanvas() {
        canvas.width = canvas.offsetWidth;
        canvas.height = canvas.offsetHeight;
    }
    window.addEventListener("resize", resizeCanvas);
    resizeCanvas();

    function getMousePos(e) {
        const rect = canvas.getBoundingClientRect();
        return { x: e.clientX - rect.left, y: e.clientY - rect.top };
    }
    function getTouchPos(e) {
        const rect = canvas.getBoundingClientRect();
        return { x: e.touches[0].clientX - rect.left, y: e.touches[0].clientY - rect.top };
    }

    // Mouse events
    canvas.addEventListener("mousedown", e => {
        drawing = true;
        const pos = getMousePos(e);
        ctx.beginPath();
        ctx.moveTo(pos.x, pos.y);
    });
    canvas.addEventListener("mousemove", e => {
        if (!drawing) return;
        const pos = getMousePos(e);
        ctx.lineTo(pos.x, pos.y);
        ctx.strokeStyle = "black";
        ctx.lineWidth = 2;
        ctx.lineCap = "round";
        ctx.stroke();
        ctx.beginPath();
        ctx.moveTo(pos.x, pos.y);
    });
    canvas.addEventListener("mouseup", () => { drawing = false; });
    canvas.addEventListener("mouseout", () => { drawing = false; });

    // Touch events
    canvas.addEventListener("touchstart", e => {
        drawing = true;
        const pos = getTouchPos(e);
        ctx.beginPath();
        ctx.moveTo(pos.x, pos.y);
    });
    canvas.addEventListener("touchmove", e => {
        if (!drawing) return;
        const pos = getTouchPos(e);
        ctx.lineTo(pos.x, pos.y);
        ctx.strokeStyle = "black";
        ctx.lineWidth = 2;
        ctx.lineCap = "round";
        ctx.stroke();
        ctx.beginPath();
        ctx.moveTo(pos.x, pos.y);
        e.preventDefault();
    });
    canvas.addEventListener("touchend", () => { drawing = false; });

    // **Critical fix**: update hidden input on submit
    form.addEventListener("submit", function(e) {
        if (ctx.getImageData(0,0,canvas.width,canvas.height).data.every(v => v === 0)) {
            e.preventDefault();
            alert("Vous devez signer avant d'envoyer.");
            return;
        }
        hiddenInput.value = canvas.toDataURL("image/png");
    });
});

</script>
</body>
</html>
