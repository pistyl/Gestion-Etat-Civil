<?php
require_once 'config.php'; // Inclut la connexion

// Traitement du formulaire de soumission
if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {
    $nom = $_POST['nom'];
    $date_naissance = $_POST['date_naissance'];
    $telephone = $_POST['telephone'];
    $type_demande = $_POST['type_demande'];
    $date_demande = $_POST['date_demande'];
    $numero_registre = ($type_demande === 'Extrait de naissance') ? $_POST['numero_registre'] : null;

    if ($type_demande === 'Extrait de naissance') {
        if (!preg_match('/^\d{3}\/\d{4}$/', $numero_registre)) {
            $error_message = "Le numéro de registre doit être au format 123/2023.";
        }
    }

    $sql = "INSERT INTO demandes (nom, date_naissance, telephone, type_demande, date_demande, numero_registre, statut)
            VALUES (?, ?, ?, ?, ?, ?, 'En attente')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nom, $date_naissance, $telephone, $type_demande, $date_demande, $numero_registre]);

    $success_message = "Demande soumise avec succès !";}

  


// Récupération des demandes pour affichage dans un calendrier
$sql = "SELECT * FROM demandes";
$stmt = $pdo->query($sql);
$demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Modification des demandes
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['modifier'])) {
    $id = $_POST['id'];
    $statut = $_POST['statut'];

    $sql = "UPDATE demandes SET statut = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$statut, $id]);
    echo "Statut mis à jour !";
}

// Suppression des demandes
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['supprimer'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM demandes WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    echo "Demande supprimée !";
}

// Récupérer les demandes
$sql = "SELECT * FROM demandes";
$demandes = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des demandes</title>
    <!-- Intégration de Tailwind CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- FullCalendar CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.10.1/main.min.css">
</head>

<body class="bg-gray-100 p-6">
    
    <div class="max-w-4xl mx-auto">
        <!-- Formulaire de demande -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <h2 class="text-2xl font-bold mb-4">Faire une demande administrative</h2>

             <!-- Affichage du message de validation -->
            <?php if (isset($success_message)) : ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>
            
            <form method="post" class="space-y-4">
    <div>
        <label for="nom" class="block text-sm font-medium text-gray-700">Nom :</label>
        <input type="text" name="nom" id="nom" required
               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
    </div>
    <div>
        <label for="date_naissance" class="block text-sm font-medium text-gray-700">Date de naissance :</label>
        <input type="date" name="date_naissance" id="date_naissance" required
               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
    </div>
    <div>
        <label for="telephone" class="block text-sm font-medium text-gray-700">Numéro de téléphone :</label>
        <input type="text" name="telephone" id="telephone" value="+221" required
               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
    </div>
    <div>
        <label for="type_demande" class="block text-sm font-medium text-gray-700">Type de demande :</label>
        <select name="type_demande" id="type_demande"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            <option value="Carte d'identité">Carte d'identité</option>
            <option value="Certificat de résidence">Certificat de résidence</option>
            <option value="Extrait de naissance">Extrait de naissance</option>
            <option value="Copie Litérale">Copie Litérale</option>
        </select>
    </div>
    <div id="registre_annee" style="display: none;">
        <label for="numero_registre" class="block text-sm font-medium text-gray-700">Numéro registre / Année d'enregistrement :</label>
        <input type="text" name="numero_registre" id="numero_registre"
               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
               placeholder="Exemple : 123/2023">
    </div>
    <div>
        <label for="date_demande" class="block text-sm font-medium text-gray-700">Date de demande :</label>
        <input type="date" name="date_demande" id="date_demande" required
               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
    </div>
    <div>
        <input type="submit" value="Soumettre"
               class="w-full bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
    </div>
</form>

<script>
    document.getElementById('type_demande').addEventListener('change', function() {
        var registreAnneeDiv = document.getElementById('registre_annee');
        if (this.value === 'Extrait de naissance') {
            registreAnneeDiv.style.display = 'block';
        } else {
            registreAnneeDiv.style.display = 'none';
        }
    });

    // Fonction pour formater la saisie avec un slash
    document.getElementById('numero_registre').addEventListener('input', function(e) {
        var input = e.target;
        var value = input.value.replace(/\D/g, ''); // Supprime tout ce qui n'est pas un chiffre
        if (value.length > 4) {
            value = value.slice(0, 3) + '/' + value.slice(3, 7); // Ajoute un slash après les 3 premiers chiffres
        }
        input.value = value; // Met à jour la valeur du champ
    });
</script>
        </div>

        <div>
        <div class="bg-white p-6 rounded-lg shadow-md">
    
    <div id="calendar" class="mb-6">
        <h3 class="text-xl font-semibold mb-4">Événements à venir</h3>
        <div class="space-y-4">
            <div class="event bg-gray-50 p-4 rounded-lg shadow-sm">
                <h4 class="text-lg font-medium text-blue-600">Carte d'identité - Mairie Yene</h4>
                <p class="text-sm text-gray-600">Date : 2023-10-15</p>
            </div>
            <div class="event bg-gray-50 p-4 rounded-lg shadow-sm">
                <h4 class="text-lg font-medium text-blue-600">Certificat de résidence - Etat civil</h4>
                <p class="text-sm text-gray-600">Date : 2023-10-20</p>
            </div>
        </div>
    </div>
</div>


    <!-- FullCalendar JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.10.1/main.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: [
                    <?php foreach ($demandes as $demande) { ?>
                        {
                            title: "<?php echo $demande['type_demande']; ?> - <?php echo $demande['nom']; ?>",
                            start: "<?php echo $demande['date_demande']; ?>"
                        },
                    <?php } ?>
                ]
            });
            calendar.render();
        });
    </script>
   
</body>
</html>