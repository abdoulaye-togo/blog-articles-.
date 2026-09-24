<?php
// 1. Connexion à la base de données
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
$erreur = null;

// 3. Traitement du formulaire lors de la soumission (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $contenu = trim($_POST['contenu'] ?? '');

    if (!empty($titre) && !empty($contenu)) {
        // Requête préparée de mise à jour
        $stmtUpdate = $pdo->prepare("UPDATE articles SET titre = :titre, contenu = :contenu WHERE id = :id");
        $stmtUpdate->execute([
            'titre' => $titre,
            'contenu' => $contenu,
            'id' => $id
        ]);

        // Redirection vers la page d'accueil après modification
        header('Location: index.php');
        exit;
    } else {
        $erreur = "Veuillez remplir tous les champs.";
    }
}

// 4. Récupération des données existantes de l'article pour pré-remplir le formulaire
$stmtSelect = $pdo->prepare("SELECT * FROM articles WHERE id = :id");
$stmtSelect->execute(['id' => $id]);
$article = $stmtSelect->fetch();

// Gestion du cas où l'ID n'existe pas dans la base
if (!$article) {
    die("Erreur : Aucun article ne correspond à cet identifiant.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un article</title>
</head>
<body>
    <h1>Modifier l'article #<?= htmlspecialchars($article['id']) ?></h1>

    <?php if ($erreur): ?>
        <p style="color: red;"><?= htmlspecialchars($erreur) ?></p>
    <?php endif; ?>

    <form method="POST">
        <div>
            <label for="titre">Titre :</label><br>
            <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($article['titre']) ?>" required style="width: 300px;">
        </div>
        <br>
        <div>
            <label for="contenu">Contenu :</label><br>
            <textarea id="contenu" name="contenu" rows="6" cols="40" required><?= htmlspecialchars($article['contenu']) ?></textarea>
        </div>
        <br>
        <button type="submit">Enregistrer les modifications</button>
        <a href="index.php">Annuler</a>
    </form>
</body>
</html>