<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/', name: 'app_test')]
    public function test(): Response
    {
        return $this->render('base.html.twig', [
            'controller_name' => 'Home',
        ]);
    }

    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new User();
        $form_register = $this->createForm(RegisterType::class, $user, ['attr' => ['class' => 'form']]);
        $form_register->handleRequest($request);

        if ($form_register->isSubmitted() && $form_register->isValid()) {
            $user = $form_register->getData();
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form_register->get('password')->getData()
                )
            );
            $this->em->persist($user);
            $this->em->flush($user);
        }

        return $this->render('register/index.html.twig', [
            'controller_name' => 'Inscription',
            'form_register' => $form_register->createView(),
        ]);
    }
}
