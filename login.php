<?php
include "layout/header.php";

// Si déjà connecté, on va à l'accueil
if (isset($_SESSION["email"])) {
    header("location: /index.php");
    exit;
}

$email = "";
$error = "";

// Traitement du formulaire quand on clique sur "Se connecter"
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "L'email et le mot de passe sont requis.";
    } else {
        include "tools/db.php";
        $dbConnection = getDatabaseConnection();

        // On cherche l'email dans la table des administrateurs
        $statement = $dbConnection->prepare(
            "SELECT id, email, password FROM administrateurs WHERE email = ?"
        );

        $statement->bind_param('s', $email);
        $statement->execute();
        
        // On récupère le résultat
        $statement->bind_result($id, $db_email, $stored_password);

        if ($statement->fetch()) {
            // On vérifie le mot de passe
            if (password_verify($password, $stored_password)) {
                
                // Connexion réussie ! On crée les sessions
                $_SESSION["admin_id"] = $id;
                $_SESSION["email"] = $db_email;
                $_SESSION["role"] = "admin";

                // Redirection vers l'accueil (le portail)
                header("location: /index.php");
                exit;
            } else {
                $error = "Mot de passe incorrect.";
            }
        } else {
            $error = "Aucun compte administrateur trouvé avec cet email.";
        }
        $statement->close();
    }
}
?>

<div class="container py-5">
    <div class="mx-auto border shadow p-4" style="width: 400px; background-color: white; border-radius: 8px;">
        <h2 class="text-center mb-4">Administration</h2>
        <hr />

        <?php if (!empty($error)) { ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><?= $error ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php } ?>
        
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($email) ?>" />
            </div>

            <div class="mb-3">
                <label class="form-label">Mot de passe</label>
                <input type="password" class="form-control" name="password" />
            </div>
            
            <div class="row mb-3 mt-4">
                <div class="col d-grid">
                    <button type="submit" class="btn btn-primary">Se connecter</button>
                </div>
                <div class="col d-grid">
                    <a href="/index.php" class="btn btn-outline-primary">Annuler</a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
include "layout/footer.php";
?>