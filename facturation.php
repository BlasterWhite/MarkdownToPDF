#!/usr/bin/php
<?php 
    $prix_par_md = 0.1;
    $nomFic = "/facturation/facturation.txt";
    if(file_exists($nomFic)) {
        $factu_file = file($nomFic);
        foreach ($factu_file as $ligne) {
            $ligne = str_replace("\n", "", $ligne); // remplace les \n par rien 
            $uneLigne = explode(" ", $ligne); // Coupe les lignes par espace
            if(startWith($ligne, "nombre fichier traité :")) {
                $nombretraite = $uneLigne[4]+1;
                $new_factu = "nombre fichier traité : " . $nombretraite . "\nprix : " . $nombretraite*$prix_par_md . "€";
                $fichier = fopen($nomFic, "w");
                fwrite($fichier, $new_factu);
                fclose($fichier);
            }
        }
    } else {
        $new_factu = "nombre fichier traité : 1\nprix : " . 1*$prix_par_md;
        $fichier = fopen($nomFic, "w");
        fwrite($fichier, $new_factu);
        fclose($fichier);
    }

    function startWith($ligne, $commence)
    {
        return substr($ligne, 0, strlen($commence)) == $commence;
    }
?>