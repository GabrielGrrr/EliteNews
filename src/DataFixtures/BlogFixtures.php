<?php

// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use Faker;
use App\Entity\User;
use App\Entity\Forum;
use App\Entity\Thread;
use App\Entity\Article;
use App\Entity\Comment;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Category;



class BlogFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    //php -d memory_limit=500M bin/console doctrine:fixtures:load --dump-sql
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();

        $nbUsers = 50;
        $nbArticles = 150;
        $nbAVGComments = 30;

        $commentators = $this->generateUsers($manager, $faker, $nbUsers); //dernier utilisateur écrit tous les articles
        $manager->flush();
        $forum = $this->generateForums($manager, $faker, 1);
        $manager->flush();
        $categories = $this->generateCategories($manager);
        $manager->flush();
        $this->generateArticles($manager, $faker, $forum, $commentators, $categories, $nbArticles, $nbUsers, $nbAVGComments);
        $manager->flush();

    }

    public function generateUsers(ObjectManager $manager, Faker\Generator $faker, $count)
    {
        $commentators = array();

        for ($i = 0; $i < $count; $i++) {
            $user = new User();
            $user->setLogin($faker->firstName . $faker->unique()->lastName());
            $user->setAvatar('https://randomuser.me/api/portraits/women/' . mt_rand(1, 99) . '.jpg');
            $user->setDateInscription($faker->dateTimeAD('now', 'Europe/Paris'));

            //50% de chance qu'un user souscrive à la newsletter, those chances are good ^^
            if (mt_rand(0, 1)) {
                $user->setDateSubscription($faker->dateTimeAD('now', 'Europe/Paris'));
                $user->setNewsletterSubscriber(1);
            } else $user->setNewsletterSubscriber(0);

            $user->setEmail($faker->unique()->email());
            $user->setLocalisation($faker->country());
            $user->setTermsAccepted(1);
            $user->setModerationStatus(0);
            $user->setIsActive(0);

            $hash = $this->encoder->encodePassword($user, $faker->password());
            $user->setPassword($hash);
            //Le sous-titre de l'user sur la forum view est un nom de compagnie ou une devise de compagnie
            $user->setSubtitle(mt_rand(0, 1) ? $faker->company() : $faker->catchphrase());
            $user->setSignature($faker->Text(200));

            $commentators[] = $user;

            $manager->persist($user);
        }

        return $commentators;
    }

    //Pas de randomisation ici pour des raisons de cohérence
    public function generateCategories(ObjectManager $manager)
    {
        $categories = array();

        $category = new Category();
        $category->setName('Politique');
        $category->setDescription("Actualité politique");
        $category->setImage('<i class="fas fa-user-tie"></i>');
        $category->setIsIcon(1);
        $categories[] = $category;
        $manager->persist($category);

        $category = new Category();
        $category->setName('Monde');
        $category->setDescription("Actualité du monde");
        $category->setImage('<i class="fas fa-globe-americas"></i>');
        $category->setIsIcon(1);
        $categories[] = $category;
        $manager->persist($category);

        $category = new Category();
        $category->setName('Technologies');
        $category->setDescription("Actualité technologique");
        $category->setImage('<i class="fas fa-cogs"></i>');
        $category->setIsIcon(1);
        $categories[] = $category;
        $manager->persist($category);

        $category = new Category();
        $category->setName('Sciences');
        $category->setDescription("Actualité scientifique");
        $category->setImage('<i class="fas fa-flask"></i>');
        $category->setIsIcon(1);
        $categories[] = $category;
        $manager->persist($category);

        $category = new Category();
        $category->setName('Société');
        $category->setDescription("Actualité sociologique");
        $category->setImage('<i class="fas fa-users"></i>');
        $category->setIsIcon(1);
        $categories[] = $category;
        $manager->persist($category);

        $category = new Category();
        $category->setName('Psychologie');
        $category->setDescription("Actualité psychologique");
        $category->setImage('<i class="fas fa-couch"></i>');
        $category->setIsIcon(1);
        $categories[] = $category;
        $manager->persist($category);

        $category = new Category();
        $category->setName('Cinéma');
        $category->setDescription("Actualité du cinquième art");
        $category->setImage('<i class="fas fa-film"></i>');
        $category->setIsIcon(1);
        $categories[] = $category;
        $manager->persist($category);

        $category = new Category();
        $category->setName('Littérature');
        $category->setDescription("Actualité littéraire");
        $category->setImage('<i class="fas fa-book"></i>');
        $category->setIsIcon(1);
        $categories[] = $category;
        $manager->persist($category);

        $category = new Category();
        $category->setName('Business');
        $category->setDescription("Actualité professionnelle");
        $category->setImage('<i class="fas fa-suitcase"></i>');
        $category->setIsIcon(1);
        $categories[] = $category;
        $manager->persist($category);

        $category = new Category();
        $category->setName('Sport');
        $category->setDescription("Actualité sportive");
        $category->setImage('<i class="fas fa-futbol"></i>');
        $category->setIsIcon(1);
        $categories[] = $category;
        $manager->persist($category);

        $category = new Category();
        $category->setName('Santé');
        $category->setDescription("Actualité sanitaire");
        $category->setImage('<i class="fas fa-briefcase-medical"></i>');
        $category->setIsIcon(1);
        $categories[] = $category;
        $manager->persist($category);

        return $categories;
    }

    //Pas besoin de davantage pour l'instant
    public function generateForums(ObjectManager $manager, Faker\Generator $faker, $count)
    {
        $forum2 = new Forum();
        $forum2->setDescription("Forum dédié à regrouper les threads des articles");
        $forum2->setIsNewsForum(1);
        $forum2->setTitre('Actualités');
        $forum2->setIsRootForum(0);
        $manager->persist($forum2);

        $forum1 = new Forum();
        $forum1->setDescription("Forum racine de l'application");
        $forum1->setIsNewsForum(0);
        $forum1->setTitre('Forum racine');
        $forum1->setIsRootForum(1);
        $forum1->setParent($forum1);
        $forum1->addSubforum($forum2);
        $manager->persist($forum1);

        return $forum2;
    }

    public function generateArticles(ObjectManager $manager, Faker\Generator $faker, Forum $forum, $commentators, $categories, $nbArticles, $nbUsers, $nbAVGComments)
    {
        //On génère des images relatives au poids (randomisé) de l'article. Elles sont elles-même randomisées, malheureusement selon un facteur temporel
        for ($i = 0; $i < $nbArticles; $i++) {
            $weight = mt_rand(0, 2);
            $image = "";
            switch ($weight) {
                case 0:
                    $image = "https://picsum.photos/" . (mt_rand(4, 6) * 100) . "/350?random";
                    break;
                case 1:
                    $image = "https://picsum.photos/" . (mt_rand(6, 8) * 100) . "/400?random";
                    break;
                case 2:
                    $image = "https://picsum.photos/" . (mt_rand(9, 11) * 100) . "/150?random";
                    break;
            }

            $content = '';
            $paragraphs = $faker->paragraphs(mt_rand(6, 12));
            foreach ($paragraphs as $paragraph) {
                $content .= '<p>' . $paragraph . '</p>';
            } 

            //L'auteur est un user pris au hasard
            $author = $commentators[array_rand($commentators)];
            $article = new Article();
            $article->setAuthor($author);
            $article->setCategory($categories[array_rand($categories)]);
            $article->setContent($content);
            $article->setDateCreation($faker->dateTimeAD($max = 'now', $timezone = 'Europe/Paris'));
            $article->setImage($image);
            $article->setRemoved(0);
            $article->setThread($this->generateThreads($manager, $faker, $forum, $commentators, $author, $nbUsers, $nbAVGComments, 1, $article));
            $article->setTitre($faker->unique()->Text($maxNbChars = 100));
            $article->setViewcount($faker->randomNumber());
            $article->setWeight($weight);

            $manager->persist($article);
        }

    }

    public function generateThreads(ObjectManager $manager, Faker\Generator $faker, Forum $forum, $commentators, $author, $nbUsers, $nbAVGComments, $count, Article $article = null)
    {

        for ($i = 0; $i < $count; $i++) {
            $thread = new Thread();
            $thread->setTitre($faker->unique()->realText(60));
            $thread->setDateCreation($faker->dateTimeAD('now', 'Europe/Paris'));
            $thread->setForum($forum);
            $thread->setViewcount($faker->randomNumber());
            $thread->setAuthor($author);
            $manager->persist($thread);
            $this->generateComments($manager, $faker, mt_rand(0, $nbAVGComments * 2), $commentators, $nbUsers, $thread);
        }


        if ($count == 1) return $thread;
    }

    public function generateComments(ObjectManager $manager, Faker\Generator $faker, $count, $commentators, $nbUser, Thread $thread)
    {

        for ($i = 0; $i < $count; $i++) {

            $content = '';
            $paragraphs = $faker->paragraphs(mt_rand(3, 5));
            foreach ($paragraphs as $paragraph) {
                $content .= '<p>' . $paragraph . '</p>';
            }

            $comment = new Comment();
            $comment->setAuthor($commentators[array_rand($commentators)]);
            $comment->setThread($thread);
            $comment->setContent($content);
            $comment->setDateCreation($faker->dateTimeAD('now', 'Europe/Paris'));
            $comment->setLikeCounter(mt_rand(0, 800));

            $manager->persist($comment);
        }

    }

}

?>