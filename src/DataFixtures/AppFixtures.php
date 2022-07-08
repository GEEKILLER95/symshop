<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Produit;
use App\Entity\Categorie;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    protected $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for($j = 0; $j <= 4; $j++) 
        {
            $categorie = new Categorie();
        
            $categorie->setNom($faker->sentence())
                      ->setSlug($this->slugger->slug($categorie->getNom()));

            $manager->persist($categorie);

            for ($i=1; $i <= mt_rand(5, 30); $i++) 
            { 
                $produit = new Produit();

                $produit->setNom("Produit n° $i")
                        ->setDescription("Voici la description du produit $i")
                        ->setPrix(mt_rand(15,89))
                        ->setImage("https://picsum.photos/id/". mt_rand(10, 100) ."/300/160")
                        ->setStock(mt_rand(10,100))
                        ->setCategorie($categorie);

                $manager->persist($produit);
            }
        }

        $manager->flush();
    }
}
