<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;

class LoginController extends AbstractController
{
    /**
     * Formulaire de connexion
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();

        $lastUsername = $utils->getLastUsername(); 

        return $this->render('user/login.html.twig', [
            'error' => $error,
            'last_username' => $lastUsername
        ]);
    }

    /**
     * Deconnexion
     * @Route("/logout", name="logout")
    */
    public function logout()
    {

    }

     /**
     * Formulaire d'inscription
     * 
     * @Route("/register", name="register")
     * 
     * @return Response
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder) {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest(($request));

        if($form->isSubmitted() && $form->isValid()) {
            $password = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword ($password);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre compte à été créé !"
            );

            return $this->redirectToRoute("login");
        }

        return $this->render('user/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
