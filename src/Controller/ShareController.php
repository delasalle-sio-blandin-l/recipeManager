<?php

namespace App\Controller;


use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request; 
use Doctrine\ORM\EntityManagerInterface;


class ShareController extends AbstractController
{
    /**
     * @Route("/share", name="share")
     */
    public function index(RecipeRepository $recipeRepo )
    {


        
        /**
         * Tout les recettes de partagées
         */
        $allRecipes = $recipeRepo->findBy([
            'isShare' => 1
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
                
            ]);
        }

       

        return $this->render('share/share.html.twig', [
            'share' => array_reverse($recipes)
        ]);
    }
}
