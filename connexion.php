<?php
require_once 'config.php'; // Inclut la connexion

// Gestion de la connexion
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Vérification des champs vides
    if (empty($email) || empty($password)) {
        $error_message = "Veuillez remplir tous les champs.";
    } else {
        // Recherche de l'utilisateur dans la base de données
        $sql = "SELECT * FROM connexion WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérification du mot de passe
        if ($user && password_verify($password, $user['password'])) {
            // Connexion réussie
            $_SESSION['user'] = $user;
            $success_message = "Connexion réussie !";
            // Redirection vers une autre page après connexion réussie
            header("Location: listedd.php");

              // Redirection en fonction du rôle
            //  if ($role == 'citoyen') {
               // header("Location: demande.php");
           // } elseif ($role == 'admin') {
           //     header("Location: listedd.php");
           // }

            exit(); // Assurez-vous de terminer le script après la redirection
        } else {
            $error_message = "Email ou mot de passe incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100">
    <!-- Barre de navigation -->
    <header class="bg-blue-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <div class="logo">
            <div class="logo">
                <h2  ><i class="fas fa-home"></i> MAIRIE CONNECT</h2>
            </div>
            </div>
            <nav>
            <ul class="flex space-x-4">
            <li><a href="index.html" class="hover:text-blue-300 transition-colors duration-200 ease-in-out">
                <i class="fas fa-sign-in-alt"></i> Déconnexion</a></li>
            </nav>
        </div>
    </header>

    <!-- Section de connexion -->
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
            <h2 class="text-2xl font-bold mb-6 text-center">Connexion</h2>
            <?php if (isset($error_message)) : ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            <?php if (isset($success_message)) : ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>
            <form method="post">
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">Email :</label>
                    <input type="email" name="email" id="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" required>
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-gray-700">Mot de passe :</label>
                    <input type="password" name="password" id="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" required>
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600">
                        Se connecter
                    </button>
                    <a href="inscription.php" class="text-blue-600 hover:text-blue-800">S'inscrire</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-blue-600 text-white text-center py-4">
        <p>&copy; 2025 Gestion Administrative. Tous droits réservés.</p>
    </footer>
</body>
</html>