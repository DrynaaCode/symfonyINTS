<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecipeController extends AbstractController
{
    #[Route('/recettes', name: 'recipe.index',methods:['GET'])]
    public function index(PaginatorInterface $paginator, RecipeRepository $repository, Request $request): Response
    {

        $recipes = $paginator->paginate(
            $repository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('pages/recipe/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }

    
    #[Route('/recette/nouveau', name: 'recipe.new', methods:["GET","POST"])]
    public function new(Request $request, EntityManagerInterface $manager) : Response{

        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $recette = $form->getData();
            $manager->persist($recette);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre recette a été créé avec succés !'
            );

             return $this->redirectToRoute('recipe.index');
            // $this->redirectToRoute('recette_index');
        }

        return $this->render('pages/recipe/new.html.twig',[
            'form' => $form->createView()
        ]);
    }
    #[Route('/recette/edition/{id}', name: 'recipe.edit', methods:["GET","POST"])]
    public function edit(
    Recipe $recette,
    Request $request,
    EntityManagerInterface $manager) : Response
    {
        // $recette = $repository->findOneBy(['id' => $id]);;
        $form = $this->createForm(RecipeType::class, $recette);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $recette = $form->getData();
            $manager->persist($recette);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre recette a été modifié avec succés !'
            );

             return $this->redirectToRoute('recipe.index');
            // $this->redirectToRoute('recette_index');
        }


        return $this->render('pages/recipe/edit.html.twig',[
            'form' => $form->createView() 
        ]);
    }

    #[Route('/recette/suppression/{id}', name: 'recipe.delete', methods:["GET"])]
    public function delete(EntityManagerInterface $manager, Recipe $recette) : Response
    {

    
            $manager->remove($recette);
            $manager->flush();
    
            $this->addFlash(
                'success',
                'Votre ingrédient a été supprimé avec succés !'
            );
    
            return $this->redirectToRoute('recipe.index');
        
    }
}
