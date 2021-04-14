<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Grade;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');
        for($i = 0; $i <5; $i++){
            $category = new Category();
            $category->setName($faker->sentence());

            $manager->persist($category);

            for($j = 0; $j <= mt_rand(4,8); $j++){
                $article = new Article();
                $content = '<p>' . join($faker->paragraphs(5), '</p><p>') . '</p>';
                $image = 'http://placeimg.com/640/480/any';
                $article->setName($faker->sentence())
                        ->setDescription($content)
                        ->setImage($image)
                        ->setPrice((floor(mt_rand() / mt_getrandmax()*100000))/100)
                        ->setImageAlt($article->getName())
                        ->setCategory($category);
                $manager->persist($article);

                for($k = 0; $k <= mt_rand(4,10); $k++){
                    $grade = new Grade();
                    $content = '<p>' . join($faker->paragraphs(2), '</p><p>') . '</p>';
                    $grade->setAuthor($faker->name)
                          ->setComment($content)
                          ->setCreatedAt($faker->dateTimeBetween('-6month'))
                          ->setArticle($article)
                          ->setGrade(mt_rand(0,20));

                    $manager->persist($grade);
                }
            }
        }
        
        

        $manager->flush();
    }
}
