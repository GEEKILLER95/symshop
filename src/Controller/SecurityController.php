<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\InscriptionFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/inscription", name="app_inscription")
     */
    public function inscription(Request $request, UserPasswordHasherInterface $encoder, EntityManagerInterface $manager): Response
    {
        $user = new User;

        $formInscription = $this->createForm(InscriptionFormType::class, $user);

        $formInscription->handleRequest($request);

        if($formInscription->isSubmitted() && $formInscription->isValid()) {

            $passwordHash = $encoder->hashPassword($user, $user->getPassword());

            dump($passwordHash);

            $user->setPassword($passwordHash);

            $user->setRoles(["ROLE_USER"]);

            dump($user);

            $manager->persist($user);

            $manager->flush();

            $this->addFlash("Success", "Félicitation, votre compte est créé, vous pouvez vous connecter");

            return $this->redirectToRoute("app_login");
        }

        //dd($user);

        return $this->render("security/inscription.html.twig", [
            'form' => $formInscription->createView()
        ]);
    }


    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
