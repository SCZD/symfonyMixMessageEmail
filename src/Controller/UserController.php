<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;

use App\Entity\User;
use App\Form\Register_form;

class UserController extends AbstractController {

    public function register(Request $request, ManagerRegistry $doctrine){

        // #1 crear
        $user = new User();
        $form = $this->createForm(Register_form::class, $user);
        $form->handleRequest($request);
        // #2 guardar
        if($form->isSubmitted() && $form->isValid()){
            $entityManager = $doctrine->getManager();
            $existingUser = $entityManager->getRepository(User::class)->findOneBy(
                ['email' => $user->getEmail()]
            );
            if(!$existingUser){
                $entityManager->persist($user);
                $entityManager->flush();
            } else {
               echo 'Ya esta registrado';
            }
        }
        
        return $this->render('user/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function login(AuthenticationUtils $authUtils){

        $err = $authUtils->getLastAuthenticationError();
        $emailFailAuth = $authUtils->getLastUsername();

        if($err != null){
            return $this->redirect(
                $this->generateUrl('mail')
            );
        }
        return $this->render('user/login.html.twig',[
            'error' => $err,
            'emailFail' => $emailFailAuth
        ]);

    }
}
