<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
</head>
<body>
    <h2>Formulaire d'inscription</h2>
    <form action="register.php" method="post">
        <label>Nom d'utilisateur :</label><br>
        <input type="text" name="username" required><br>

        <label>Email :</label><br>
        <input type="email" name="email" required><br>

        <label>Mot de passe :</label><br>
        <input type="password" name="password" required><br>

        <input type="submit" value="S'inscrire">
    </form>

    <?php
    // Partie PHP d'inscription
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = htmlspecialchars($_POST["username"]);
        $email = htmlspecialchars($_POST["email"]);
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hash du mot de passe

        try {
            $pdo = new PDO("mysql:host=localhost;dbname=nom_de_la_base", "utilisateur", "motdepasse");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Vérifier si l'email existe déjà
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                echo "⚠️ Cet email est déjà utilisé.";
            } else {
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$username, $email, $password]);
                echo "✅ Inscription réussie !";
            }
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }
    ?>
</body>
</html>
