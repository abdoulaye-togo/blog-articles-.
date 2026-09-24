<?php
// 1. Connexion à la base de données
try {
    $pdo = new PDO('mysql:host=localhost;dbname=blog_stage;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// 2. Récupérer tous les articles du plus récent au plus ancien
$reqSelect = $pdo->query("SELECT * FROM articles ORDER BY id DESC");
$articles = $reqSelect->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Exercice 3.4 - Blog Stage</title>
</head>
<body>

    <h1>Tous les articles (du plus récent au plus ancien)</h1>

    <?php if (empty($articles)): ?>
        <p>Aucun article trouvé. <a href="ajouter.php">Ajoutez-en un !</a></p>
    <?php else: ?>
        <?php foreach ($articles as $article): ?>
            <article style="border-bottom: 1px solid #ccc; padding-bottom: 10px; margin-bottom: 10px;">
                <h2><?= htmlspecialchars($article['titre']) ?></h2>
                <p><?= nl2br(htmlspecialchars($article['contenu'])) ?></p>
                <small>Auteur : <?= htmlspecialchars($article['auteur'] ?? 'Anonyme') ?></small>
                <br><br>
                
                <!-- Liens Modifier et Supprimer spécifiques à CET article -->
                <a href="modifier.php?id=<?= $article['id'] ?>">Modifier</a> | 
                <a href="supprimer.php?id=<?= $article['id'] ?>" 
                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');">Supprimer</a>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>

</body>
</html>