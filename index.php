<?php
include "layout/header.php";

// On vérifie si la personne connectée est bien un administrateur
$is_admin = (isset($_SESSION["role"]) && $_SESSION["role"] == "admin");

$message = "";
$alerte_couleur = "";

// ⚙️ TRAITEMENT DU SCAN : Si un badge a été envoyé par le formulaire
if ($is_admin && $_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['code_badge'])) {
    
    include "tools/db.php";
    $db = getDatabaseConnection();
    // On nettoie les espaces en trop que la douchette pourrait envoyer
    $scanned_badge = trim($_POST['code_badge']); 

    // 1. Chercher l'élève dans la base
    $stmt = $db->prepare("SELECT first_name, last_name, classe FROM users WHERE code_badge = ?");
    $stmt->bind_param("s", $scanned_badge);
    $stmt->execute();
    $stmt->bind_result($prenom, $nom, $classe);

    // Si le badge existe
    if ($stmt->fetch()) {
        $stmt->close(); // Très important : on ferme la requête avant d'en lancer une autre

        // 2. Chercher le dernier mouvement (Entrée ou Sortie ?)
        $stmt2 = $db->prepare("SELECT type_mouvement FROM passages WHERE code_badge = ? ORDER BY date_heure DESC LIMIT 1");
        $stmt2->bind_param("s", $scanned_badge);
        $stmt2->execute();
        $stmt2->bind_result($dernier_mouvement);

        // Par défaut, s'il n'y a pas d'historique, c'est qu'il entre.
        $nouveau_mouvement = "entree"; 
        
        if ($stmt2->fetch()) {
            if ($dernier_mouvement == "entree") {
                // S'il était déjà à l'intérieur, c'est qu'il sort !
                $nouveau_mouvement = "sortie"; 
            }
        }
        $stmt2->close();

        // 3. Enregistrer ce nouveau passage avec la date et l'heure
        $stmt3 = $db->prepare("INSERT INTO passages (code_badge, type_mouvement) VALUES (?, ?)");
        $stmt3->bind_param("ss", $scanned_badge, $nouveau_mouvement);
        $stmt3->execute();
        $stmt3->close();

        // 4. Préparer le message à afficher sur l'écran du gardien
        if ($nouveau_mouvement == "entree") {
            $alerte_couleur = "success"; // Vert
            $message = "✅ ENTRÉE : $prenom $nom ($classe) vient d'entrer.";
        } else {
            $alerte_couleur = "warning"; // Jaune
            $message = "⬅️ SORTIE : $prenom $nom ($classe) vient de sortir.";
        }

    } else {
        // Le badge n'est pas dans la base de données
        $stmt->close();
        $alerte_couleur = "danger"; // Rouge
        $message = "❌ ALERTE : Ce badge ($scanned_badge) est inconnu !";
    }
}
?>

<?php if ($is_admin) { ?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h1 class="mb-4">Contrôle du Portail 🛡️</h1>

                <?php if (!empty($message)) { ?>
                    <div class="alert alert-<?= $alerte_couleur ?> fs-4 mb-4 fw-bold shadow-sm">
                        <?= $message ?>
                    </div>
                <?php } ?>

                <div class="card shadow p-5 border-primary">
                    <form method="POST" action="index.php">
                        <label class="form-label fs-3 mb-3 text-muted">En attente de scan...</label>
                        
                        <input type="text" name="code_badge" class="form-control form-control-lg text-center fs-2 mb-4" placeholder="Scannez ici..." autofocus autocomplete="off" required>
                        
                        <button type="submit" class="btn btn-primary btn-lg w-100">Enregistrer manuellement</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php } else { ?>

    <div style="background-color: #08618d;">
        <div class="container text-white py-5">
            <div class="row align-items-center g-5">
                <div class="col-md-6">
                    <h1 class="mb-5 display-2"><strong>Une Entrée Et Une Sortie Qui Facilite La Vie</strong></h1>
                    <p>Enregistrer facilement l'entrée et la sortie des véhicules dans notre établissement pour un gain de temps et d'argent.</p>
                </div>
                <div class="col-md-6 text-center">
                    <img src="images/istockphoto-594474448-612x612.jpg" class="img-fluid" alt="hero" />
                </div>
            </div>
        </div>            
    </div> 

<?php } ?>

<?php
include "layout/footer.php";
?>