<?php

namespace App\Service;

use DateTime;
use Faker\Provider\en_US\Text;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


define("TAILLE_MAX", "500");
define("TAILLE_MIN", "300"); //Tailles d'un extrait d'article, destinées à être multipliées par un facteur d'importance de 1 à 3

//Service multitâche qui me sert principalement à manipuler des strings dans twig
class ContentHandler
{
    private $router;

    public function __construct(UrlGeneratorInterface $router = null)
    {
        $this->router = $router;
    }

    public function secureAndParse(string $text)
    {
        return $this->bbCodeParser(htmlspecialchars($text, ENT_QUOTES, 'UTF-8'));
    }
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

    public function formatXHRResponse($articles)
    {
        $response = '';
        foreach ($articles as $article) {
            $response .= '<article class="article-listed article-' . $this->translateWeight($article['weight']) . '">
            <div class="article-category">
                <a href="' . $this->router->generate('article_thorough', ['keyword' => '*', 'categories' => $article['category_id']]) . '" class="url-link">
                    ' . $article['categoryimage'] . '</a>
            </div>
            <div class="article-inside">
                    <a href="' . $this->router->generate('article_read', ['id' => $article['id']]) . '">
                        <div class="article-image">
                    <img src="' . $article['image'] . '"  alt="">

                </div>
                <div class="article">
                    <a href="' . $this->router->generate('article_read', ['id' => $article['id']]) . '">
                    <h1 class="headingband">' . $article['titre'] . '</h1></a>
                    <div class="article-heading">
                        <span class="list-authordate">Écrit le ' . $article['date_creation'] . ', par ' . $article['author'] . ' 
                        </span>
                        <span class="comment-count" href="#">
                            <a href="' . $this->router->generate('browse_comment', ['id' => $article['id']]) . '">' . $article['comment_count'] . '</a>
                        </span>
                    </div>
                    <div class="article-content">' . $this->raccourcir($article['content'], $article['weight']) . '</div>
                    <a href="' . $this->router->generate('article_read', ['id' => $article['id']]) . '"> <div class="bouton article-more">... <span class="hidden">Lire la suite</span></div></a>
                </div>
            </div>
        </article>';
        }
        return $response;
    }


    public function convertArrayOfEnum($tableau)
    {
        $resultat = null;

        if ($tableau['IT']) $resultat[] = 'IT';
        if ($tableau['Neuro']) $resultat[] = 'Neuro';
        if ($tableau['Socio']) $resultat[] = 'Socio';
        if ($tableau['Psycho']) $resultat[] = 'Psycho';
        if ($tableau['cinema']) $resultat[] = 'Cinéma';
        if ($tableau['Autres']) $resultat[] = 'Autres';

        return $resultat;
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

    //this one's not mine, pas l'habitude de copier du code mais j'ai pas pu trouver de package pour du bb avec symfony et j'ai vraiment pas envie
    //de réinventer la roue pour cette fonction
    public function bbcodeParser($string)
    {
        $tags = 'b|i|size|color|center|quote|url|img';
        while (preg_match_all('`\[(' . $tags . ')=?(.*?)\](.+?)\[/\1\]`', $string, $matches)) foreach ($matches[0] as $key => $match) {
            list($tag, $param, $innertext) = array($matches[1][$key], $matches[2][$key], $matches[3][$key]);
            switch ($tag) {
                case 'b':
                    $replacement = "<strong>$innertext</strong>";
                    break;
                case 'i':
                    $replacement = "<em>$innertext</em>";
                    break;
                case 'size':
                    $replacement = "<span style=\"font-size: $param;\">$innertext</span>";
                    break;
                case 'color':
                    $replacement = "<span style=\"color: $param;\">$innertext</span>";
                    break;
                case 'center':
                    $replacement = "<div class=\"centered\">$innertext</div>";
                    break;
                case 'quote':
                    $replacement = "<blockquote>$innertext</blockquote>" . $param ? "<cite>$param</cite>" : '';
                    break;
                case 'url':
                    $replacement = '<a href="' . ($param ? $param : $innertext) . "\">$innertext</a>";
                    break;
                case 'img':
                    list($width, $height) = preg_split('`[Xx]`', $param);
                    $replacement = "<img src=\"$innertext\" " . (is_numeric($width) ? "width=\"$width\" " : '') . (is_numeric($height) ? "height=\"$height\" " : '') . '/>';
                    break;
                case 'video':
                    $videourl = parse_url($innertext);
                    parse_str($videourl['query'], $videoquery);
                    if (strpos($videourl['host'], 'youtube.com') !== false) $replacement = '<embed src="http://www.youtube.com/v/' . $videoquery['v'] . '" type="application/x-shockwave-flash" width="425" height="344"></embed>';
                    if (strpos($videourl['host'], 'google.com') !== false) $replacement = '<embed src="http://video.google.com/googleplayer.swf?docid=' . $videoquery['docid'] . '" width="400" height="326" type="application/x-shockwave-flash"></embed>';
                    break;
            }
            $string = str_replace($match, $replacement, $string);
        }
        return $string;
    }
}


?>