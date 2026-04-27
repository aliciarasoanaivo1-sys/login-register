<?php
// On initialise la session
session_start();

// On vérifie si l'utilisateur connecté est un administrateur
$is_admin = false;
if (isset($_SESSION["role"]) && $_SESSION["role"] == "admin") {
    $is_admin = true;
}
?>

<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>School Access</title>
    <link rel="icon" href="images/fed61408-756e-4d02-8830-0ce6147c8073.jpeg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body>

    <nav class="navbar navbar-expand-lg bg-body-tertiary border-bottom shadow-sm">
         <div class="container">
            <a class="navbar-brand" href="/index.php">
                <img src="images/fed61408-756e-4d02-8830-0ce6147c8073.jpeg" width="30" height="30" class="d-inline-block align-top" alt="">   School Access
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                 <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="/index.php">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-primary" href="/register.php">Obtenir un Badge</a>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    <?php if ($is_admin) { ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-dark fw-bold" href="#" role="button" data-bs-toggle="dropdown">
                                 Menu Portail (Admin)
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/index.php">Scanner un Badge</a></li>
                                <li><a class="dropdown-item" href="/dashboard.php">Tableau de bord</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="/logout.php">Déconnexion</a></li>
                            </ul>
                        </li>
                    <?php } else { ?>
                        <li class="nav-item">
                            <a href="/login.php" class="btn btn-sm btn-outline-secondary">Accès Personnel</a>
                        </li>
                    <?php } ?>
                </ul>

            </div>
        </div>
    </nav>