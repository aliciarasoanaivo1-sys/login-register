<?php
    include "layout/header.php";
    // 1. INITIALISATION DES VARIABLES
    $first_name = "";
    $last_name = "";
    $classe = "";
    $marque_vehicule = "";
    $error = "";
    // 2. DÉTECTION DU CLIC : Si le formulaire est envoyéif ($_SERVER['REQUEST_METHOD'] == 'POST'){

    

    // Récupération des données du formulaire (camelCase pour le PHP)

    $first_name = $_POST['first_name'];

    $last_name = $_POST['last_name'];

    $classe = $_POST['classe'];

    $marque_vehicule = $_POST['marque_vehicule'];



    // Vérification simple : les champs obligatoires sont-ils remplis ?

    if (empty($first_name) || empty($last_name) || empty($classe) || empty($marque_vehicule)) {

        $error = "Veuillez remplir tous les champs obligatoires (*)";

    } else {

        

        // Connexion à la base de données

        include "tools/db.php";

        $dbConnection = getDatabaseConnection();



        // GÉNÉRATION DU CODE BADGE (Ex: SA-2026-4821)

        
        $annee = date('Y');
        $random_num = rand(1000, 9999);
        $badge_code = "SA-" . $annee . "-" . $random_num; // Résultat : SA-2024-4821

        $created_at = date('Y-m-d H:i:s'); // Date du jour



        // INSERTION SIMPLE DANS LA BASE DE DONNÉES

        // On utilise une requête SQL directe (plus facile à expliquer au jury)

        $sql = "INSERT INTO users (first_name, last_name, classe, marque_vehicule, code_badge) 

                VALUES ('$first_name', '$last_name', '$classe', '$marque_vehicule', '$badge_code')";



        if ($dbConnection->query($sql) === TRUE) {

            // On sauvegarde les infos en session pour la page success.php

            $_SESSION["badge_code"] = $badge_code;

            $_SESSION["first_name"] = $first_name;



            header("location: /success.php");

            exit;

        } else {

            $error = "Erreur lors de l'enregistrement : " . $dbConnection->error;

        }

    }


?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-6 mx-auto border shadow p-4 bg-white" style="border-radius: 8px;">
            <h2 class="text-center mb-4">Inscription School Access</h2>
            <hr />

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="post"> 
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label">Prénom*</label>
                    <div class="col-sm-8">
                        <input class="form-control" name="first_name" required value="<?= htmlspecialchars($firstName) ?>">
                    </div>
                </div>           
                
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label">Nom*</label>
                    <div class="col-sm-8">
                        <input class="form-control" name="last_name" required value="<?= htmlspecialchars($lastName) ?>">
                    </div>
                </div>        

                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label">Classe*</label>
                    <div class="col-sm-8">
                        <input class="form-control" name="classe" placeholder="ex: IGGLIA 1B" required value="<?= htmlspecialchars($classe) ?>">
                    </div>
                </div>        

                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label">Marque Véhicule</label>
                    <div class="col-sm-8">
                        <input class="form-control" name="marque_vehicule" placeholder="ex: Yamaha" value="<?= htmlspecialchars($marque_vehicule) ?>">
                    </div>
                </div>  
                
                <div class="row mb-3 mt-4">
                    <div class="col d-grid">
                        <button type="submit" class="btn btn-primary">Générer le Badge</button>
                    </div>
                    <div class="col d-grid">
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