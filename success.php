<?php
// On démarre la session en TOUT PREMIER pour pouvoir lire les informations
session_start();

// SÉCURITÉ : Si quelqu'un essaie d'aller sur success.php sans s'être inscrit,
// (donc s'il n'a pas de badge en mémoire), on le renvoie à l'accueil.
if (!isset($_SESSION["badge_code"])) {
    header("location: /index.php");
    exit;
}

// On récupère les informations sauvegardées lors de l'inscription
$badge_code = $_SESSION["badge_code"];
$first_name = $_SESSION["first_name"];

include "layout/header.php";
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            
            <div class="alert alert-success mb-4">
                🎉 Félicitations <strong><?= htmlspecialchars($first_name) ?></strong> ! Ton inscription est réussie.
            </div>

            <div class="card shadow-lg border-primary mb-4">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">🛡️ School Access - Laissez-passer</h3>
                </div>
                <div class="card-body py-5">
                    <p class="text-muted">Voici ton numéro d'identification unique :</p>
                    
                    <h1 class="display-4 fw-bold text-primary mb-4"><?= $badge_code ?></h1>
                    
                    <p class="mb-4">Conserve ce numéro précieusement. Tu devras le saisir au portail à chaque entrée et sortie.</p>
                    
                    <div class="text-muted" style="font-family: 'Courier New', monospace; font-size: 28px; letter-spacing: 2px;">
                        || ||| | ||| || || | |||
                    </div>
                </div>
            </div>

            <a href="/index.php" class="btn btn-primary btn-lg">Retour à l'accueil</a>

        </div>
    </div>
</div>

<?php
// On efface le badge de la mémoire (session) maintenant qu'il a été affiché.
// Comme ça, si l'élève rafraîchit la page, ça ne crée pas de bug.
unset($_SESSION["badge_code"]);
unset($_SESSION["first_name"]);

include "layout/footer.php";
?>