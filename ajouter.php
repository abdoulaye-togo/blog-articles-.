<?php
// Connexion à la base de données
try {
    $pdo = new PDO('mysql:host=localhost;dbname=blog_stage;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $contenu = trim($_POST['contenu'] ?? '');
    $auteur = trim($_POST['auteur'] ?? '');

    if (!empty($titre) && !empty($contenu)) {
        $stmt = $pdo->prepare("INSERT INTO articles (titre, contenu, auteur) VALUES (:titre, :contenu, :auteur)");
        $stmt->execute([
            'titre' => $titre,
            'contenu' => $contenu,
            'auteur' => $auteur
        ]);

        header('Location: index.php');
        exit;
    } else {
        $message = "Veuillez remplir le titre et le contenu.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un article</title>
</head>
<body>
    <h1>Ajouter un nouvel article</h1>

    <?php if ($message): ?>
        <p style="color: red;"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST">
        <div>
            <label for="titre">Titre :</label><br>
            <input type="text" id="titre" name="titre" required style="width: 300px;">
        </div>
        <br>
        <div>
            <label for="auteur">Auteur :</label><br>
            <input type="text" id="auteur" name="auteur" style="width: 300px;">
        </div>
        <br>
        <div>
            <label for="contenu">Contenu :</label><br>
            <textarea id="contenu" name="contenu" rows="6" cols="40" required></textarea>
        </div>
        <br>
        <button type="submit">Publier l'article</button>
        <a href="index.php">Annuler</a>
    </form>
</body>
</html>