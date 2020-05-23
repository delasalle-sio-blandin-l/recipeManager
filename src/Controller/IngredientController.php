<?php

namespace App\Controller;

use App\Entity\Ingredients;
use App\Form\AddIngredientType;
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
    public function index()
    {
        return $this->render('ingredient/ingredient.html.twig', [
            'controller_name' => 'IngredientController',
        ]);
    }

    /**
     * Create ingredient
     * 
     * @Route("/ingredient/add", name="create_ingredient")
     *
    */
    public function createIngredient(Request $request, EntityManagerInterface $manager): Response {

        $ingredient = new Ingredients();

        $form = $this->createForm(AddIngredientType::class, $ingredient);

        $form->handleRequest(($request));

        if($form->isSubmitted() && $form->isValid()) {

            $manager->persist($ingredient);
            $manager->flush();

            return new Response('Ingrédient ajouté');

            return $this->redirectToRoute("ingredient");
        }

        return $this->render('ingredient/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Delete ingredient
     * 
     * @Route("/ingredient/delete/{id}", name="delete_ingredient")
     *
    */
    public function deleteIngredient(): Response
    {
        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to the action: createIngredient(EntityManagerInterface $entityManager)
        $entityManager = $this->getDoctrine()->getManager();

        $ingredient = $ingredientRepo->findOneBy(['id' => $id]);

        $entityManager->remove($product);
        $entityManager->flush();

        return new Response('Ingrédient avec l\'id '.$ingredient->getId().' à été supprimé');
    }
}
