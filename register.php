<?php
include "layout/header.php";

// Variables pour stocker les saisies de l'utilisateur
$first_name = "";
$last_name = "";
$classe = "";
$marque_vehicule = "";

// Variables pour gérer les messages d'erreur
$first_name_error = "";
$last_name_error = "";
$classe_error = "";
$error = false;

// 1. DÉTECTION DU CLIC : Est-ce que le formulaire a été envoyé ?
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    // On récupère ce que l'utilisateur a tapé
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $classe = $_POST['classe'];
    $marque_vehicule = $_POST['marque_vehicule']; // La marque est optionnelle

    // 2. VÉRIFICATION : Est-ce que les champs obligatoires sont remplis ?
    if (empty($first_name)){
        $first_name_error = "Le prénom est obligatoire.";
        $error = true;
    }
    if (empty($last_name)){
        $last_name_error = "Le nom est obligatoire.";
        $error = true;
    }
    if (empty($classe)){
        $classe_error = "La classe est obligatoire.";
        $error = true;
    }

    // 3. ENREGISTREMENT : S'il n'y a aucune erreur, on enregistre !
    if (!$error) {
        
        // Connexion à la base de données
        include "tools/db.php";
        $dbConnection = getDatabaseConnection();

        // 🌟 GÉNÉRATION DU CODE BADGE UNIQUE
        // On prend l'année (ex: 2024) et un nombre au hasard entre 1000 et 9999
        $annee = date('Y');
        $random_num = rand(1000, 9999);
        $badge_code = "SA-" . $annee . "-" . $random_num; // Résultat : SA-2024-4821

        $created_at = date('Y-m-d H:i:s'); // Date du jour

        // 📥 INSERTION DANS LA BASE DE DONNÉES
        // On prépare la requête avec nos nouvelles colonnes
        $statement = $dbConnection->prepare(
            "INSERT INTO users (first_name, last_name, classe, marque_vehicule, code_badge, created_at) " .
            "VALUES (?, ?, ?, ?, ?, ?)"
        );

        // On associe les 6 variables (6 "s" pour dire que ce sont 6 textes/strings)
        $statement->bind_param('ssssss', $first_name, $last_name, $classe, $marque_vehicule, $badge_code, $created_at);

        // On exécute la requête
        $statement->execute();
        $statement->close();

        // 🎉 SUCCÈS : On sauvegarde le badge dans la session pour l'afficher sur la page suivante
        $_SESSION["badge_code"] = $badge_code;
        $_SESSION["first_name"] = $first_name;

        // Redirection vers une nouvelle page (que l'on va créer ensuite) pour montrer le badge
        header("location: /success.php");
        exit;
    }
}
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-6 mx-auto border shadow p-4">
            <h2 class="text-center mb-4">Inscription School Access</h2>
            <hr />

            <form method="post"> 
                
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label">Prénom*</label>
                    <div class="col-sm-8">
                        <input class="form-control" name="first_name" value="<?= htmlspecialchars($first_name) ?>">
                        <span class="text-danger"><?= $first_name_error ?></span>
                    </div>
                </div>           
                
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label">Nom*</label>
                    <div class="col-sm-8">
                        <input class="form-control" name="last_name" value="<?= htmlspecialchars($last_name) ?>">
                        <span class="text-danger"><?= $last_name_error ?></span>
                    </div>
                </div>        

                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label">Classe*</label>
                    <div class="col-sm-8">
                        <input class="form-control" name="classe" placeholder="ex: IGGLIA 1B" value="<?= htmlspecialchars($classe) ?>">
                        <span class="text-danger"><?= $classe_error ?></span>
                    </div>
                </div>        

                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label">Marque Véhicule</label>
                    <div class="col-sm-8">
                        <input class="form-control" name="marque_vehicule" placeholder="ex: Yamaha (Optionnel)" value="<?= htmlspecialchars($marque_vehicule) ?>">
                    </div>
                </div>  
                
                <div class="row mb-3">
                    <div class="offset-sm-4 col-sm-4 d-grid">
                        <button type="submit" class="btn btn-primary">Générer le Badge</button>
                    </div>
                    <div class="col-sm-4 d-grid">
                        <a href="/index.php" class="btn btn-outline-primary">Annuler</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include "layout/footer.php";
?>