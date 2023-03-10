<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class IngredientController extends AbstractController
{


    #[Route('/ingredient', name: 'ingredient.index', methods:["GET"])]

    //Repository : Gere la recuperation de données 
    //Call find all du repo
    
    public function index(IngredientRepository $repository,PaginatorInterface  $paginator, Request $request): Response
    {
        $ingredients = $paginator->paginate(
            $repository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
    
        return $this->render('pages/ingredient/index.html.twig', [
           'ingredients' => $ingredients
        ]);
    }

    //This controller show a form

    #[Route('/ingredient/nouveau', name: 'ingredient.new', methods:["GET","POST"])]
    public function new(Request $request, EntityManagerInterface $manager) : Response{

        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $ingredient = $form->getData();
            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre ingrédient a été créé avec succés !'
            );

             return $this->redirectToRoute('ingredient.index');
            // $this->redirectToRoute('ingredient_index');
        }

        return $this->render('pages/ingredient/new.html.twig',[
            'form' => $form->createView()
        ]);
    }
    #[Route('/ingredient/edition/{id}', name: 'ingredient.edit', methods:["GET","POST"])]
    public function edit(
    Ingredient $ingredient,
    Request $request,
    EntityManagerInterface $manager) : Response
    {
        // $ingredient = $repository->findOneBy(['id' => $id]);;
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $ingredient = $form->getData();
            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre ingrédient a été modifié avec succés !'
            );

             return $this->redirectToRoute('ingredient.index');
            // $this->redirectToRoute('ingredient_index');
        }


        return $this->render('pages/ingredient/edit.html.twig',[
            'form' => $form->createView() 
        ]);
    }

    #[Route('/ingredient/suppression/{id}', name: 'ingredient.delete', methods:["GET"])]
    public function delete(EntityManagerInterface $manager, Ingredient $ingredient) : Response
    {

    
            $manager->remove($ingredient);
            $manager->flush();
    
            $this->addFlash(
                'success',
                'Votre ingrédient a été supprimé avec succés !'
            );
    
            return $this->redirectToRoute('ingredient.index');
        
    }
  
}
