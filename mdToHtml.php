#!/usr/bin/php
<?php
$alea = rand(0,4);
$couleur = [];
$couleur[0] = "#C3FAC7";
$couleur[1] = "#B1FAF0";
$couleur[2] = "#FAF0B6";
$couleur[3] = "#FA9DC1";
$couleur[4] = "#FABBA1";
if ($argc < 2) {
    echo "Utilisation :";
    echo $argv[0] . "<fichierEntree> (optionnel)<fichierSortiee>\n";
} else {
    echo "Traitement du fichier : ", $argv[1], "\n";
    if (!isset($argv[2])) {
        $nomFichier = explode(".", $argv[1]);
        $outputfile = '/work/tmp/' . $nomFichier[0] . ".html";
    } else {
        $nomFichier = explode(".", $argv[1]);
        $outputfile = '/work/tmp' . $argv[2];
    }
    $md = file("/work/tmp/" . $argv[1]);
    $result = "<html>\n<head>\n\t<title>" . $nomFichier[0] . "</title>\n\t<style>\n\tbody{\n\t\tfont-family: Arial, sans-serif;\n\t\tfont-size: 2em;\n\t\t\n\t\tmargin: 0%;\n\t}\n\tdiv{\n\t\theight: 20cm;\n\t\tpadding: 5% 20%;\n\t\toverflow: hidden;\n\t}\n\th1,h2,h3{\n\t\tmargin-left: -10%;\n\t\tmargin-bottom: 10%;\n\t}@page{\n\t\tsize: landscape;\n\t\tmargin: 0;\n\t}\n</style>\n</head>\n<body>\n\t<div  style=\"background-color:" . $couleur[$alea] . ";\">\n";
    $nousSommesDansUneBaliseCode = false;
    $nousSommesDansUneListe = false;
    $baliseListeOuverte = false;
    $baliseListeAFermer = false;
    foreach ($md as $ligne) {
        $ligne = str_replace("\n", "", $ligne); // remplace les \n par rien 
        $uneLigne = explode(" ", $ligne); // Coupe les lignes par espace


        // Si une liste est terminée
        if ($baliseListeAFermer and $uneLigne[0] != "-") {
            $result .= "\t</ul>\n";
            $baliseListeAFermer = false;
            $baliseListeOuverte = false;
        }

        // Changement de diapo
        if (startWith($uneLigne[0], "---")){
            $alea = rand(0,4);
            $result .= "\t</div>\n\t<div style=\"background-color:" . $couleur[$alea] . ";\">\n";
        }

        // Pour un titre h1
        else if ($uneLigne[0] == "#") {
            $result .= "\t<h1>";
            foreach ($uneLigne as $mot) {
                if ($mot != "#") {
                    $result .= $mot . " ";
                }
            }
            $result .= "</h1>\n";
        }

        // Pour un titre h2
        else if ($uneLigne[0] == "##") {
            $result .= "\t<h2>";
            foreach ($uneLigne as $mot) {
                if ($mot != "##") {
                    $result .= $mot . " ";
                }
            }
            $result .= "</h2>\n";
        }

        // Pour un titre h3
        else if ($uneLigne[0] == "###") {
            $result .= "\t<h3>";
            foreach ($uneLigne as $mot) {
                if ($mot != "###") {
                    $result .= $mot . " ";
                }
            }
            $result .= "</h3>\n";
        }

        // Pour une liste
        else if (startWith($uneLigne[0], "-")) {
            // Si une balise de liste est ouverte, on ajoute l'élément suivant
            if ($baliseListeOuverte) {
                $result .= "\t\t<li>";
                foreach ($uneLigne as $mot) {
                    $mot = str_replace("-", "", $mot);
                    $result .= $mot . " ";
                }
                $result .= "</li>\n";
            } else {
                // Si la balise ul est à ouvrir
                $baliseListeOuverte = true;
                $baliseListeAFermer = true;
                $result .= "\t<ul>\n\t\t<li>";
                foreach ($uneLigne as $mot) {
                    $mot = str_replace("-", "", $mot);
                    $result .= $mot . " ";
                }
                $result .= "</li>\n";
            }
        }


        // Pour le code source
        else if (startWith($uneLigne[0], "```") || endWith($uneLigne[0], "```")) {
            if ($nousSommesDansUneBaliseCode) {
                $nousSommesDansUneBaliseCode = false;
                $result .= "\t</code></pre>\n";
            } else {
                $nousSommesDansUneBaliseCode = true;
                $result .= "\t<pre><code>\n";
            }
        } else { // Texte Brut
            foreach ($uneLigne as $mot) {
                if (startWith($mot, "**") && endWith($mot, "**")) { // Ajout du style gras pour un mot
                    $mot = str_replace("*", "", $mot);
                    $result .= "<strong>" . $mot . "</strong>" . " ";
                } else if (startWith($mot, "_") && endWith($mot, "_")) { // Ajout du style italic pour un mot
                    $mot = str_replace("_", "", $mot);
                    $result .= "<em>" . $mot . "</em>" . " ";
                } else {
                    $result .= $mot . " ";
                }
            }
        }
    }
    $result .= "\t</div>\n</body>\n</html>\n";
    //echo $result;
    echo "Enregistrement dans le dossier : " . $outputfile . "\n";
    $fichierHTML = fopen($outputfile, "w");
    fwrite($fichierHTML, $result);
    fclose($fichierHTML);
}

/**
 * Fonction Custom car pas disponible dans les versions antérieures à PHP8.0
 */
function startWith($ligne, $commence)
{
    return substr($ligne, 0, strlen($commence)) == $commence;
}

function endWith($ligne, $termine)
{
    return substr($ligne, -strlen($termine)) === $termine;
}
?>