<?php
require "../config/db.php";

// Get form fields
$full_name = $_POST['full_name'] ?? '';
$declaration_text = $_POST['declaration_text'] ?? '';
$signature_data = $_POST['signature_data'] ?? '';

if (empty($signature_data)) {
    die("Vous devez signer avant d'envoyer.");
}

// Convert base64 signature to PNG
$signature_data = str_replace('data:image/png;base64,', '', $signature_data);
$signature_data = str_replace(' ', '+', $signature_data);
$imageData = base64_decode($signature_data);

// Save PNG
$fileName = 'sign_' . time() . '.png';
$filePath = '../uploads/signatures/' . $fileName;
file_put_contents($filePath, $imageData);

// Save record in database
$stmt = $pdo->prepare("INSERT INTO applications (full_name, declaration_text, signature_path) VALUES (?, ?, ?)");
$stmt->execute([$full_name, $declaration_text, $fileName]);

// Redirect to generate_pdf.php (HTML “PDF”)
$id = $pdo->lastInsertId();
header("Location: generate_pdf.php?id=" . $id);
exit;
