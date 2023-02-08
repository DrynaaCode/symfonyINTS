<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private Generator $faker;
    public function __construct()
    {   
        $this->faker = Factory::create("fr_FR");
       
    }

    public function load(ObjectManager $manager ): void
    {
        //Ingredients
        $ingredients=[];
        for ($i=1; $i < 50 ; $i++) { 
            $ingredient = new Ingredient();
            $ingredient->setName($this->faker->word());
            $ingredient->setPrice(mt_rand(0,100));
            $ingredients[] = $ingredient;
            $manager->persist($ingredient);
        }

        //Recipes
        for ($j=1; $j < 50 ; $j++) {
            $recipe = new Recipe();
            $recipe->setName($this->faker->word())
            ->setTime(mt_rand(0,1) == 1 ? mt_rand(1,1440) : null)
            ->setNbPeople(mt_rand(0,1) == 1 ? mt_rand(1,50) : null)
            ->setDifficulty(mt_rand(0,1) == 1 ? mt_rand(1,5) : null)
            ->setDescription($this->faker->text(300))
            ->setPrice(mt_rand(0,1) == 1 ? mt_rand(1,1000) : null)
            ->setIsFavorite(mt_rand(0,1) == 1 ? true : false);
            
            //On ajoute des ingrédients a la recette
            for ($k=0; $k <mt_rand(5,15) ; $k++) { 
                $recipe->addIngredient($ingredients[mt_rand(0, count($ingredients)-1)]);
            }
            $manager->persist($recipe);
        } 

        //Users
        for ($l=0; $l <50 ; $l++) { 
            $user = new User();
            $user->setEmail($this->faker->email())
            ->setFullname($this->faker->name())
            ->setPseudo($this->faker->firstName())
            ->setRoles(['ROLE_USER'])
            ->setPlainPassword('password');
            // $hashedPassword = $this->passwordHasher->hashPassword(
            //     $user,
            //     'password'
            // );
            // $user->setPassword($hashedPassword);

            
            $manager->persist($user);
        }

 
        $manager->flush();
    }
}
