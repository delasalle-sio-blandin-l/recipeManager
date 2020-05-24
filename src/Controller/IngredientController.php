<?php

namespace App\Controller;

use App\Entity\Ingredients;
use App\Form\AddIngredientType;
use App\Repository\IngredientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request; 
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;



class IngredientController extends AbstractController
{
    /**
     * @Route("/ingredient", name="ingredient")
     */
    public function index(IngredientRepository $ingredientRepo)
    {
        $user = $this->getUser();

        $userId = $user->getId();

        /**
         * Tout les ingredients de l'utilisateur connecté
         */
        $allIngredients = $ingredientRepo->findBy([
            'user' => (int) $userId
        ]);

        $ingredients = [];

        /**
         * Ajout des valeurs dans un tableau
         */
        foreach ($allIngredients as $allIngredient) {
            array_push($ingredients, [
                'id' => $allIngredient->getId(),
                'name' => $allIngredient->getName(),
                'price' => $allIngredient->getPrice()
            ]);
        }

        return $this->render('ingredient/ingredient.html.twig', [
            'ingredients' => array_reverse($ingredients)
        ]);
    }

    /**
     * Create ingredient
     * 
     * @Route("/ingredient/add", name="create_ingredient")
     *
    */
    public function createIngredient(Request $request, EntityManagerInterface $manager): Response 
    {
        $user = $this->getUser();

        $ingredient = new Ingredients();

        $form = $this->createForm(AddIngredientType::class, $ingredient);

        $form->handleRequest(($request));

        if($form->isSubmitted() && $form->isValid()) {

            $ingredient->setUser($user);

            $manager->persist($ingredient);
            $manager->flush();

            return new Response('Ingrédient ajouté');
        
        }

        return $this->render('ingredient/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Delete ingredient
     * 
     * @Route("/ingredient/delete", name="delete_ingredient")
     *
    */
    public function deleteIngredient(EntityManagerInterface $manager, IngredientRepository $ingredientRepo, $id): Response
    {
        $user = $this->getUser();

        $ingredient = $ingredientRepo->findBy(['id' => $id]);

        $entityManager->remove($ingredient);
        $entityManager->flush();

        return new Response('Ingrédient avec l\'id '.$ingredient->getId().' à été supprimé');
    }
}
