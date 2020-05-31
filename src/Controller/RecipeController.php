<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\AddRecipeType;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request; 
use Doctrine\ORM\EntityManagerInterface;

class RecipeController extends AbstractController
{

    
    /**
     * @Route("/recipe", name="recipe")
     * 
     */
    public function index(RecipeRepository $recipeRepo )
    {

        $user = $this->getUser();

        $userId = $user->getId();

        /**
         * Tout les recettes de l'utilisateur connecté
         */
        $allRecipes = $recipeRepo->findBy([
            'user' => (int) $userId
        ]);

        $recipes = [];

        /**
         * Ajout des valeurs dans un tableau
         */
        foreach ($allRecipes as $allRecipe) {
            array_push($recipes, [
                'id' => $allRecipe->getId(),
                'name' => $allRecipe->getName(),
                'description' => $allRecipe->getDescription(),
                'instruction' => $allRecipe->getInstruction(),
                'preparation_time' => $allRecipe->getPreparationTime(),
                'level' => $allRecipe->getLevel(),
                'pictures' => $allRecipe->getPictures(),
                'totalPrice' => $allRecipe->getTotalPrice(),
                'isShare' => $allRecipe->getIsShare(),
                
            ]);
        }

        return $this->render('recipe/recipe.html.twig', [
            'recipe' => array_reverse($recipes)
        ]);


    }


     /**
     * Create recipe
     * 
     * @Route("/recipe/add", name="create_recipe")
     *
    */
    public function createRecipe(Request $request, EntityManagerInterface $manager) 
    {
        $user = $this->getUser();

        $recipe = new Recipe();

        $form = $this->createForm(AddRecipeType::class, $recipe);

        $form->handleRequest(($request));

        if($form->isSubmitted() && $form->isValid()) {

            $recipe->setUser($user);

            $manager->persist($recipe);
            $manager->flush();

            return $this->redirectToRoute('recipe');
        
        }

        return $this->render('recipe/add.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * Delete recipe
     * 
     * @Route("/recipe/delete/{id}", name="delete_recipe")
     *
     * *
     * @param [int] $id
     * @param EntityManagerInterface $manager
     * @param RecipeRepository $recipeRepo
     * *
     * 
    */
    public function deleteRecipe($id, EntityManagerInterface $manager, RecipeRepository $recipeRepo)
    {
        $user = $this->getUser();

        $recipe = $recipeRepo->find(['id' => $id]);

        $manager->remove($recipe);
        $manager->flush();

        return $this->redirectToRoute('recipe');
    }

    

    /**
     * @Route("/recipe/share/{id}", name="recipe_update_share")
     * 
     *  * *
     * @param [int] $id
     * @param EntityManagerInterface $manager
     * @param RecipeRepository $recipeRepo
     * *
     */
    public function updateShare($id, EntityManagerInterface $manager, RecipeRepository $recipeRepo)
    {

        $recipe = $recipeRepo->find(['id' => $id]);

        $recipe->setIsShare(1);
            
        $manager->flush();

        return $this->redirectToRoute('recipe');
        
        

    }


}
