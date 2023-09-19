<?php
// Inclure la configuration de la base de données et initialiser la connexion PDO
require_once 'config.php';

// Vérifier si l'utilisateur est connecté, sinon rediriger vers la page de connexion
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Récupérer les informations de l'utilisateur depuis la base de données
$stmt = $bdd->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données soumises par le formulaire
    $newName = $_POST["new_name"];
    $newEmail = $_POST["new_email"];

    // Mettre à jour les informations de l'utilisateur dans la base de données
    $updateStmt = $bdd->prepare("UPDATE utilisateurs SET nom = ?, email = ? WHERE id = ?");
    $updateStmt->execute([$newName, $newEmail, $user_id]);

    // Rediriger vers une page de confirmation ou de profil mis à jour
    header("Location: profile.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modifier le profil</title>
</head>
<body>
    <h2>Modifier le profil</h2>
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <label for="new_name">Nouveau nom :</label>
        <input type="text" id="new_name" name="new_name" value="<?php echo $user['nom']; ?>"><br>

        <label for="new_email">Nouvel email :</label>
        <input type="email" id="new_email" name="new_email" value="<?php echo $user['email']; ?>"><br>

        <input type="submit" value="Enregistrer les modifications">
    </form>
</body>
</html>