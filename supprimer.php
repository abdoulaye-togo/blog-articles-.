<?php
// 1. Connexion à la base de données blog_stage
try {
    $pdo = new PDO('mysql:host=localhost;dbname=blog_stage;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// 2. Vérification et récupération de l'ID passé dans l'URL
if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Erreur : Identifiant d'article invalide ou manquant.");
}

$id = (int) $_GET['id'];

// 3. Vérification de l'existence de l'article avant suppression
$stmtCheck = $pdo->prepare("SELECT id FROM articles WHERE id = :id");
$stmtCheck->execute(['id' => $id]);

if (!$stmtCheck->fetch()) {
    die("Erreur : Aucun article ne correspond à cet identifiant.");
}

// 4. Requête préparée pour supprimer l'article
$stmtDelete = $pdo->prepare("DELETE FROM articles WHERE id = :id");
$stmtDelete->execute(['id' => $id]);

// 5. Redirection automatique vers la liste des articles
header('Location: index.php');
exit;