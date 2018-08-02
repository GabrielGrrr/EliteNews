<?php

namespace App\Service;

define("TAILLE_MAX", "500");
define("TAILLE_MIN", "300"); //Tailles d'un extrait d'article, destinées à être multipliées par un facteur d'importance de 1 à 3

//Service multitâche qui me sert principalement à manipuler des strings dans twig
class ContentHandler
{
    //Raccourcit un article
    public function raccourcir(string $content, $weight)
    {
        $weight++; //on veut un poids de 1, 2 ou 3
        //Si pas besoin de raccourcir
        if (strlen($content) < TAILLE_MIN * $weight) return $content;
        else //Sinon on cherche une fin de ligne et on on coupe
        if ($i = strpos($content, '.', TAILLE_MIN * $weight))
            return substr($content, 0, $i);
        else if ($i = strpos($content, ' ', TAILLE_MIN * $weight))
            return substr($content, 0, $i);
        else //Sinon, on coupe
        return substr($content, 0, TAILLE_MAX * $weight);
    }

    //Renvoie l'icône correspondante à une catégorie ... Pour accéder / modifier les énums de catégorie, consulter la classe Article
    public function reverseCategory($i)
    {
        $categories = ['IT', 'Neuro', 'Socio', 'Psycho', 'Cinéma', 'Autres'];
        $string = '';
        switch ($i) {
            case  $categories[0] :
                $string = '<i class="fas fa-code"></i>';
                break;
            case $categories[1]:
                $string = '<i class="fas fa-flask"></i>';
                break;
            case $categories[2]:
                $string = '<i class="fas fa-users"></i>';
                break;
            case $categories[3]:
                $string = '<i class="fas fa-couch"></i>';
                break;
            case $categories[4]:
                $string = '<i class="fas fa-film"></i>';
                break;
            case $categories[5] :
                $string = '<i class="fas fa-book"></i>';
                break;
        }

        return $string;
    }

    //Traduit le poids (au sens webdesign du terme) de l'article sur la page, stocké dans la db en numéraire, en fragment de nom de classe css
    public function translateWeight($weight)
    {
        $string = '';
        switch ($weight) {
            case 0:
                $string = 'short';
                break;
            case 1:
                $string = 'long';
                break;
            case 2:
                $string = 'xl';
                break; //A étendre ou réimplémenter en fonction des besoins
        }

        return $string;
    }

    public function convertArrayOfEnum($tableau)
    {
        $resultat = NULL;

        if($tableau['IT']) $resultat[] = 'IT';
        if($tableau['Neuro']) $resultat[] = 'Neuro';
        if($tableau['Socio']) $resultat[] = 'Socio';
        if($tableau['Psycho']) $resultat[] = 'Psycho';
        if($tableau['cinema']) $resultat[] = 'Cinéma';
        if($tableau['Autres']) $resultat[] = 'Autres';

        dump($tableau);

        return $resultat;
    }
}


?>