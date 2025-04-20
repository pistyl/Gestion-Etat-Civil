<?php
require_once 'config.php'; // Inclut la connexion


// Traitement du formulaire de soumission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['soumettre'])) {
    $nom = $_POST['nom'];
    $date_naissance = $_POST['date_naissance'];
    $telephone = $_POST['telephone'];
    $type_demande = $_POST['type_demande'];
    $date_demande = $_POST['date_demande'];
    $numero_registre = ($type_demande === 'Extrait de naissance') ? $_POST['numero_registre'] : null;

    $sql = "INSERT INTO demandes (nom, date_naissance, telephone, type_demande, date_demande, numero_registre, statut)
            VALUES (?, ?, ?, ?, ?, ?, 'En attente')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nom, $date_naissance, $telephone, $type_demande, $date_demande, $numero_registre]);

    $success_message = "Demande soumise avec succès !";}

// Récupérer les demandes
$sql = "SELECT * FROM demandes";
$demandes = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

// Modification des demandes
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['modifier'])) {
    $id = $_POST['id'];
    $statut = $_POST['statut'];

    $sql = "UPDATE demandes SET statut = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$statut, $id]);
    $success_message = "Statut mis à jour !";
}

// Suppression des demandes
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['supprimer'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM demandes WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
   $error_message = "Demande supprimée !";
}

// Récupérer les demandes triées par date
$sql = "SELECT * FROM demandes ORDER BY date_demande DESC";
$demandes = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

// Statistiques pour le tableau de bord
$sqlTotal = "SELECT COUNT(*) AS total FROM demandes";
$totalDemandes = $pdo->query($sqlTotal)->fetch(PDO::FETCH_ASSOC)['total'];

$sqlEnAttente = "SELECT COUNT(*) AS en_attente FROM demandes WHERE statut = 'En attente'";
$enAttente = $pdo->query($sqlEnAttente)->fetch(PDO::FETCH_ASSOC)['en_attente'];

$sqlValidee = "SELECT COUNT(*) AS validee FROM demandes WHERE statut = 'Validée'";
$validee = $pdo->query($sqlValidee)->fetch(PDO::FETCH_ASSOC)['validee'];

$sqlRejetee = "SELECT COUNT(*) AS rejetee FROM demandes WHERE statut = 'Rejetée'";
$rejetee = $pdo->query($sqlRejetee)->fetch(PDO::FETCH_ASSOC)['rejetee'];

// Statistiques par type de demande
$sqlTypeDemande = "SELECT type_demande, COUNT(*) AS count FROM demandes GROUP BY type_demande";
$typesDemande = $pdo->query($sqlTypeDemande)->fetchAll(PDO::FETCH_ASSOC);



?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des demandes</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100">
    <!-- Barre de navigation -->
    <header class="bg-blue-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <div class="logo">
                <h2  ><i class="fas fa-home"></i> MAIRIE CONNECT</h2>
            </div>
            <nav>
            <ul class="flex space-x-4">
            <li><a href="connexion.php" class="hover:text-blue-300 transition-colors duration-200 ease-in-out">
                <i class="fas fa-sign-in-alt"></i> Déconnexion</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <!-- Tableau de bord -->
    <div class="container mx-auto p-4">
    <h2 class="text-3xl font-bold mb-6 text-center">Tableau de Bord</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-blue-500 text-white p-6 rounded-lg shadow-md flex items-center">
            <i class="fas fa-list text-3xl mr-4"></i>
            <div>
                <h3 class="text-lg font-semibold">Total des demandes</h3>
                <p class="text-2xl font-bold"><?php echo $totalDemandes; ?></p>
            </div>
        </div>
        
        <div class="bg-yellow-500 text-white p-6 rounded-lg shadow-md flex items-center">
            <i class="fas fa-hourglass-half text-3xl mr-4"></i>
            <div>
                <h3 class="text-lg font-semibold">Demandes en attente</h3>
                <p class="text-2xl font-bold"><?php echo $enAttente; ?></p>
            </div>
        </div>
        
        <div class="bg-green-500 text-white p-6 rounded-lg shadow-md flex items-center">
            <i class="fas fa-check-circle text-3xl mr-4"></i>
            <div>
                <h3 class="text-lg font-semibold">Demandes validées</h3>
                <p class="text-2xl font-bold"><?php echo $validee; ?></p>
            </div>
        </div>
        
        <div class="bg-red-500 text-white p-6 rounded-lg shadow-md flex items-center">
            <i class="fas fa-times-circle text-3xl mr-4"></i>
            <div>
                <h3 class="text-lg font-semibold">Demandes rejetées</h3>
                <p class="text-2xl font-bold"><?php echo $rejetee; ?></p>
            </div>
        </div>
    </div>

    <h2 class="text-2xl font-bold mt-8 mb-4 text-center">Types de Demande</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($typesDemande as $type) : ?>
            <div class="bg-gray-200 p-6 rounded-lg shadow-md flex items-center">
                <i class="fas fa-file-alt text-3xl mr-4"></i>
                <div>
                    <h3 class="text-lg font-semibold"><?php echo htmlspecialchars($type['type_demande']); ?></h3>
                    <p class="text-2xl font-bold"><?php echo $type['count']; ?></p>
                </div>
            </div>
        <?php endforeach; ?>
        </div>

        <!-- Cartes pour les types de demande -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
            <?php foreach ($typesDemande as $type) : ?>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold"><?= htmlspecialchars($type['type_demande']) ?></h3>
                    <p class="text-2xl font-bold"><?= $type['count'] ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Liste des demandes -->
        <h2 class="text-2xl font-bold mb-4">Liste des demandes</h2>

        <!-- Alert des traitements -->

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

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full">
            <thead class="bg-blue-600 text-white">
        <tr>
            <th class="px-6 py-3">ID</th>
            <th class="px-6 py-3">Nom</th>
            <th class="px-6 py-3">Numéro</th>
            <th class="px-6 py-3">Date de naissance</th>
            <th class="px-6 py-3">Type de demande</th>
            <th class="px-6 py-3">Numéro de registre</th>
            <th class="px-6 py-3">Date de création</th>
            <th class="px-6 py-3">Statut</th>
            <th class="px-6 py-3">Actions</th>
        </tr>
    </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($demandes as $demande) : ?>
                        <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-center"><?= $demande['id'] ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($demande['nom']) ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($demande['telephone']) ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($demande['date_naissance']) ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($demande['type_demande']) ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($demande['numero_registre'] ?? 'N/A') ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($demande['date_demande']) ?></td>
                <td class="px-6 py-4">
                                <form method="post" class="flex items-center">
                                    <input type="hidden" name="id" value="<?= $demande['id'] ?>">
                                    <select name="statut" class="border rounded-lg px-2 py-1">
                                        <option value="En attente" <?= $demande['statut'] === 'En attente' ? 'selected' : '' ?>>En attente</option>
                                        <option value="Validée" <?= $demande['statut'] === 'Validée' ? 'selected' : '' ?>>Validée</option>
                                        <option value="Rejetée" <?= $demande['statut'] === 'Rejetée' ? 'selected' : '' ?>>Rejetée</option>
                                    </select>
                                    <input type="hidden" name="modifier" value="1">
                                    <button type="submit" class="ml-2 bg-blue-600 text-white px-3 py-1 rounded-lg hover:bg-blue-700">Modifier</button>
                                </form>
                            </td>
                            <td class="px-6 py-4">
                                <form method="post" class="flex items-center">
                                    <input type="hidden" name="id" value="<?= $demande['id'] ?>">
                                    <input type="hidden" name="supprimer" value="1">
                                    <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded-lg hover:bg-red-700" onclick="return confirm('Voulez-vous vraiment supprimer cette demande ?');">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-blue-600 text-white text-center py-4 ">
        <p>&copy; 2025 Gestion Administrative. Tous droits réservés.</p>
    </footer>
</body>
</html>