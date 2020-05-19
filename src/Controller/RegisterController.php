<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;

class RegisterController extends AbstractController
{
    /**
     * Formulaire d'inscription
     * 
     * @Route("/register", name="register")
     * 
     * @return Response
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder) {
        $user = new User();

        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest(($request));

        if($form->isSubmitted() && $form->isValid()) {
            $password = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword ($password);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre compte est créé !"
            );

            return $this->redirectToRoute("register");
        }

        return $this->render('register/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
