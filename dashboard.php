<?php
include "layout/header.php";

// 1. SÉCURITÉ : On vérifie que c'est bien l'administration qui essaie d'accéder
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    header("location: /index.php");
    exit;
}

include "tools/db.php";
$db = getDatabaseConnection();

// 2. CALCUL DES STATISTIQUES (Pour les blocs de couleur en haut)
$date_jour = date('Y-m-d'); // La date d'aujourd'hui

// Compter les entrées du jour
$stmt_in = $db->prepare("SELECT COUNT(*) FROM passages WHERE type_mouvement = 'entree' AND DATE(date_heure) = ?");
$stmt_in->bind_param("s", $date_jour);
$stmt_in->execute();
$stmt_in->bind_result($total_entrees);
$stmt_in->fetch();
$stmt_in->close();

// Compter les sorties du jour
$stmt_out = $db->prepare("SELECT COUNT(*) FROM passages WHERE type_mouvement = 'sortie' AND DATE(date_heure) = ?");
$stmt_out->bind_param("s", $date_jour);
$stmt_out->execute();
$stmt_out->bind_result($total_sorties);
$stmt_out->fetch();
$stmt_out->close();

// Calcul simple pour savoir combien de personnes sont "Présentes" à l'intérieur
$presents = $total_entrees - $total_sorties;
if ($presents < 0) $presents = 0; // Petite sécurité logique
?>

<div class="container py-5">
    <h1 class="mb-4">Tableau de Bord 📊</h1>

    <div class="row mb-5">
        <div class="col-md-6">
            <div class="card text-white bg-primary shadow">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-people-fill"></i> Personnes à l'intérieur</h5>
                    <p class="display-4 fw-bold mb-0"><?= $presents ?></p>
                    <small>Aujourd'hui : <?= $total_entrees ?> entrées enregistrées</small>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-dark bg-warning shadow border-0">
                <div class="card-body">
                    <h5 class="card-title">Véhicules sortis</h5>
                    <p class="display-4 fw-bold mb-0"><?= $total_sorties ?></p>
                    <small>Aujourd'hui</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Historique des 50 derniers passages</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Heure</th>
                            <th>Mouvement</th>
                            <th>Prénom & Nom</th>
                            <th>Classe</th>
                            <th>Code Badge</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // 3. LA REQUÊTE JOIN (Le vrai pouvoir des bases de données !)
                        // On demande au PHP de "coller" la table passages et la table users
                        // pour afficher le vrai nom de l'élève au lieu de juste voir son numéro de badge.
                        $query = "SELECT p.date_heure, p.type_mouvement, u.first_name, u.last_name, u.classe, p.code_badge 
                                  FROM passages p 
                                  JOIN users u ON p.code_badge = u.code_badge 
                                  ORDER BY p.date_heure DESC 
                                  LIMIT 50";
                        
                        $result = $db->query($query);

                        // On boucle pour créer une ligne de tableau pour chaque passage
                        while ($row = $result->fetch_assoc()) {
                            
                            // On formate l'heure pour qu'elle soit jolie (ex: 14:30:05)
                            $heure_formatee = date('H:i:s', strtotime($row['date_heure']));
                            
                            // On choisit une couleur selon si c'est une entrée (vert) ou sortie (jaune)
                            $badge_couleur = ($row['type_mouvement'] == 'entree') ? 'bg-success' : 'bg-warning text-dark';
                            $mouvement_texte = ($row['type_mouvement'] == 'entree') ? 'ENTRÉE' : 'SORTIE';
                            
                            echo "<tr>";
                            echo "<td><strong>" . $heure_formatee . "</strong></td>";
                            echo "<td><span class='badge " . $badge_couleur . "'>" . $mouvement_texte . "</span></td>";
                            echo "<td>" . htmlspecialchars($row['first_name'] . " " . $row['last_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['classe']) . "</td>";
                            echo "<td class='text-muted'>" . htmlspecialchars($row['code_badge']) . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
include "layout/footer.php";
?>