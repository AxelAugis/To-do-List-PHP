<?php

// Démarrage de la session 

session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="liste.css">
    <title>Document</title>
</head>
<body>

<!-- Formulaire pour les tâches -->

    <h1> Entrez votre tâche : </h1>
        <section id="form_container">
            <form action="#" method="POST">
                <br>
                <label for="Titre">Titre de la tâche : </label>
                <input type="text" placeholder="ex : Frigo" name ="titre" class="inputText">
                <br><br>
                <label for="desc">Description de la tâche : </label>
                <input type="text" placeholder="ex : Nettoyer le frigo " name="descrip" class="inputText">
                <br>
                <input type="submit" value="Envoyer" name="Envoyer" class = 'submit'>
                <br><br>
                <label for="Supprimer">Souhaitez-vous tout supprimer ? </label>
                <br>
                <input type="submit" value = "Tout supprimer" name="Supprimer" class = 'submit'>
                <br><br>
                <label for="Supprimer">Souhaitez-vous supprimer une tâche ? </label>
                <input type="text" placeholder="ex : Frigo" name="TachSupp" class="inputText">
                <br>
                <input type="submit" value = "Supprimer une tâche" name="SuppUneTache" class = 'submit'>
                <br><br>
                <label>Appuyer sur le bouton ci-dessous pour afficher le résumé mis à jour</label>
                <br>
                <input type="submit" value = "Afficher le résumé" name="Afficher" class = 'submit'>
                <br><br>
                <label for="Déplacer">Si une tâche est finie, entrez son nom et cliquez sur "Oui" : </label>
                <input type="text" placeholder="ex : Frigo" name="deplacer" class="inputText">
                <br>
                <input type="submit" value = "Oui" name="tache_finie" class = 'submit'>
                <br>
                <br>
                <label for="">Date de fin: <input type="date"  name="date_expire" value="2022-12-15" min="2020-01-01" max="2100-12-31"></label>
                <br>
                <input type="text" placeholder="ex : Frigo" name="end" class="inputText">
                <br>
                <input type="submit" value ="update" name = "update" class = "submit">

                <br><br>

            </form>
        </section>
        

</body>
</html>

<?php 

// Ajout d'une tâche au tableau si une requête POST est envoyée ou création du tableau sinon

    if(isset($_POST['Envoyer'])){

        // Ajout d'une tâche au tableau

        if(isset($_SESSION['tableau'])) {             
            $_SESSION['tableau'][$_POST['titre']] = $_POST['descrip'];

    // Création tableau sinon

            }  else { 

                $_SESSION['tableau']=[];
        }

        // Ajout d'un tableau avec les dates 
        
        if(isset($_SESSION['date'])) {

            $_SESSION['date'][$_POST['titre']] = $_POST["date_expire"];
    
        }
            else {
    
                $_SESSION['date']=[];
    
            }
       
    }





// Renvoie le tableau des tâches à faire après l'envoi d'une rêquete *_POST['Envoyer'] sous forme "array" et sous forme "texte"

    if(isset($_POST['Envoyer'])) {
        echo "<pre>"; var_dump($_SESSION['tableau']); echo "</pre>";
        echo "<h2> Voici le résumé de vos tâches à faire </h2>";
        foreach ($_SESSION['tableau'] as $key => $value) {
            echo " <div id = 'resume_tache'> Tâche : $key. La description de la tâche : $value. <br>  </div>";
        }
            
        
    }

// Suppression de la session si on clique sur le bouton "Tout supprimer"

    if(isset($_POST['Supprimer'])) {
        unset($_SESSION['tableau']);
    }


// Suppresion d'une tâche en particulier en appuyant sur le bouton "Supprimer une tâche"

    if(isset($_POST['SuppUneTache'])) {
        foreach ($_SESSION['tableau'] as $key => $value) {
            if ($key == $_POST['TachSupp']) {
            unset($_SESSION['tableau'][$key]);

            }
        }   
    }


// Permet d'afficher uniquement le résumé

    if(isset($_POST['Afficher'])) {
        echo "<h2> Voici le résumé de vos tâches à faire :</h2>";
        foreach ($_SESSION['tableau'] as $key => $value) {
            echo " <div id = 'resume_tache'> Tâche : $key. La description de la tâche : $value. <br>  </div>";
        }
    }


// Stocker une tâche ailleurs si elle est finie 

    if(isset($_POST['tache_finie'])) {
        foreach($_SESSION['tableau'] as $key => $value) {
            if ($key == $_POST ['deplacer']) {
                file_put_contents('tache_finie.txt', print_r($key, true). "\n", FILE_APPEND);
                unset($_SESSION['tableau'][$key]);
                header("Refresh:0");
            }
        }
    }

// Stock la tâche ailleurs si la date de la tâche est expirée 

    //  Stocke la tâche ailleurs

    if(isset($_POST['update'])) {

        $today = new DateTime();
        $date = $today->format("Y-m-d");

        foreach ($_SESSION['date'] as $key => $value) {
            if ($value < $date) {
                file_put_contents('archivage_taches.txt', print_r($key,true). " ".($value). "\n", FILE_APPEND);
                unset($_SESSION['date'][$key]);
                unset($_SESSION['tableau'][$key]);
                header("Refresh:0");
            }
            
        }

    // Ajoute la date à laquelle la tâche est archivée dans le fichier archivage_taches.txt

        foreach ($_SESSION['date'] as $key => $value) {
            if ($key == $_POST['end']) {
                file_put_contents('archivage_taches.txt', print_r($value,true). "\n", FILE_APPEND);
                unset($_SESSION['date'][$key]);
                header("Refresh:0");
            }
        }
    }
    

// CSS pour un affichage lisible du tableau sous forme de résumé à défaut d'avoir réussi à l'encoder dans le fichier "liste.css"

echo " <style>
            #resume_tache { 
                    color: #006a4e;
                    width : 30%;
                    margin-left: 60px;
                    margin-top: 2px;
                    padding: 20px 20px 20px 20px; 
                    background-color : #ACB6E5;
            }

            h2 { 
                color: #006a4e;
                margin-left: 80px;

            }

        </style>";

?>
