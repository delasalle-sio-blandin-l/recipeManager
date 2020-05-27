<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UpdatePasswordType;
use App\Form\UpdateUsernameType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request; 
use Doctrine\ORM\EntityManagerInterface;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function index()
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

    /**
     * @Route("/profile/updateUsername", name="profile_update_username")
     */
    public function updateUsername(Request $request, EntityManagerInterface $manager)
    {
        $user = $this->getUser();


        $form = $this->createForm(UpdateUsernameType::class, $user);

        $form->remove('password');
        $form->remove('email');

        $form->handleRequest(($request));

        if($form->isSubmitted() && $form->isValid()) {

            //dd($form->getData()->getUsername());
            $user->setUsername($form->getData()->getUsername());
            

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('profile');
        
        }


        return $this->render('profile/updateUsername.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
