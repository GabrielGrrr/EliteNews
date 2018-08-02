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



class BlogFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();

        $nbUsers = 30;
        $nbArticles = 50;
        $nbAVGComments = 30;

        $commentators = $this->generateUsers($manager, $faker, $nbUsers); //dernier utilisateur écrit tous les articles
        $forum = $this->generateForums($manager, $faker, 1);
        $this->generateArticles($manager, $faker, $forum, $commentators, $nbArticles, $nbUsers, $nbAVGComments);

        $manager->flush();

    }

    public function generateUsers(ObjectManager $manager, Faker\Generator $faker, $count)
    {
        $commentators = array();

        for ($i = 0; $i < $count; $i++) {
            $user = new User();
            $user->setLogin($faker->firstName . $faker->unique()->lastName());
            $user->setAvatar('https://randomuser.me/api/portraits/women/' . mt_rand(1, 99) . '.jpg'); //Des femmes de partout dans mon site
            $user->setDateInscription($faker->dateTimeAD('now', 'Europe/Paris'));

            //50% de chance qu'un user souscrive à la newsletter, those chances are good ^^
            if (mt_rand(0, 1)) {
                $user->setDateSubscription($faker->dateTimeAD('now', 'Europe/Paris'));
                $user->setNewsletterSubscriber(1);
            } else $user->setNewsletterSubscriber(0);

            $user->setEmail($faker->unique()->email());
            $user->setLocalisation($faker->country());

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

    //Pas besoin de davantage pour l'instant
    public function generateForums(ObjectManager $manager, Faker\Generator $faker, $count)
    {
        $forum2 = new Forum();
        $forum2->setDescription("Forum dédié à regrouper les threads des articles");
        $forum2->setIsNewsForum(1);
        $forum2->setIsRootForum(0);
        $manager->persist($forum2);

        $forum1 = new Forum();
        $forum1->setDescription("Forum racine de l'application");
        $forum1->setIsNewsForum(0);
        $forum1->setIsRootForum(1);
        $forum1->addSubforum($forum2);
        $manager->persist($forum1);

        return $forum2;
    }

    public function generateArticles(ObjectManager $manager, Faker\Generator $faker, Forum $forum, $commentators, $nbArticles, $nbUsers, $nbAVGComments)
    {
        $categories = ['IT', 'Neuro', 'Socio', 'Psycho', 'Cinéma', 'Autres'];
        //On génère des images relatives au poids (randomisé) de l'article. Elles sont elles-même randomisées, malheureusement selon un facteur temporel
        for ($i = 0; $i < $nbArticles; $i++) {
            $weight = mt_rand(0,2);
            $image = "";
            switch ($weight) {
                case 0:
                $image = "https://picsum.photos/".(mt_rand(3,5)*100)."/300?random";
                break;
                case 1:
                $image = "https://picsum.photos/".(mt_rand(6,8)*100)."/350?random";
                break;
                case 2:
                $image = "https://picsum.photos/".(mt_rand(9,11)*100)."/400?random";
                break;
            }

            //L'auteur est un user pris au hasard
            $author = $commentators[array_rand($commentators)];
            $article = new Article();
            $article->setAuthor($author);
            $article->setCategory($categories[array_rand($categories)]);
            $article->setContent($faker->Text($maxNbChars = 3000));
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
            $thread->setArticle($article);
            $thread->setAuthor($author);
            $manager->persist($thread);
            $this->generateComments($manager, $faker, mt_rand(0, $nbAVGComments * 2), $commentators, $nbUsers, $thread);
        }


        if ($count == 1) return $thread;
    }

    public function generateComments(ObjectManager $manager, Faker\Generator $faker, $count, $commentators, $nbUser, Thread $thread)
    {

        for ($i = 0; $i < $count; $i++) {
            $comment = new Comment();
            $comment->setAuthor($commentators[array_rand($commentators)]);
            $comment->setThread($thread);
            $comment->setContent($faker->Text($maxNbChars = 600));
            $comment->setDateCreation($faker->dateTimeAD('now', 'Europe/Paris'));
            $comment->setLikeCounter(mt_rand(0, 800));

            $manager->persist($comment);
        }

    }

}

?>