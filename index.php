<?php
include "layout/header.php";

// On vérifie si la personne connectée est bien un administrateur
$is_admin = (isset($_SESSION["role"]) && $_SESSION["role"] == "admin");

$message = "";
$alerte_couleur = "";
?>

<?php if ($is_admin)  { ?>

    <!-- Le container crée de l'espace sur les côtés -->
    <div class="container py-5"> 
        <!-- Le row et justify-content-center centrent le contenu -->
        <div class="row justify-content-center"> 
            <!-- Le col-md-8 limite la largeur à 8 colonnes sur 12 -->
            <div class="col-md-8 text-center"> 
                
                <h1 class="mb-4">Contrôle du Portail 🛡️</h1>

                <div class="card shadow p-5 border-primary">
                    <form onsubmit="showSimulatedMessage(); return false;">
                        <label class="form-label fs-3 mb-3 text-muted">En attente de scan...</label>
                        
                        <input type="text" id="badgeInput" class="form-control form-control-lg text-center fs-2 mb-4" 
                               placeholder="Scannez ici..." autofocus autocomplete="off">
                        
                        <button type="submit" class="btn btn-primary btn-lg w-100">Valider le Passage</button>
                    </form>
                    
                    <div id="simulationMessage" class="mt-4" style="display:none;">
                        <div class="alert alert-success fs-4 fw-bold shadow-sm">
                            ✅ PASSAGE ENREGISTRÉ AVEC SUCCÈS
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
    function showSimulatedMessage() {
        document.getElementById('simulationMessage').style.display = 'block';
        document.getElementById('badgeInput').value = ''; 
        setTimeout(function() {
            document.getElementById('simulationMessage').style.display = 'none';
        }, 3000);
    }
    </script>

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