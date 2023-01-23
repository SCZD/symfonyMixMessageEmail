<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

use App\Entity\Message;
use App\Form\Message_form;
use App\Form\Message_form_reply;

class MessageController extends AbstractController
{
    public function viewServices(ManagerRegistry $doctrine): Response {
        return $this->render('message/viewService.html.twig');
    }

    public function inbox(ManagerRegistry $doctrine, UserInterface $user): Response {

        $message_repo = $doctrine->getManager()->getRepository(Message::class);
        $qb =  $message_repo->createQueryBuilder('m')
                            ->andWhere("m.toemail = :email")
                            ->setParameter('email', $user->getEmail())
                            ->orderBy('m.sent', 'DESC')
                            ->getQuery();
        $resulSet = $qb->execute();

        return $this->render('message/inbox.html.twig',[
            'messages' => $resulSet
        ]);
    }

    public function send(ManagerRegistry $doctrine, UserInterface $user): Response {
        return $this->render('message/send.html.twig',[
            'messages' => $user->getMessages()
        ]);
    }

    public function create(ManagerRegistry $doctrine,Request $request, UserInterface $user): Response {

        $message = new Message();
        $form = $this->createForm(Message_form::class, $message);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $message->setSent(new \DateTime('now'));
            $message->setUser($user);

            $entityManager = $doctrine->getManager();
            $entityManager->persist($message);
            $entityManager->flush();
            return $this->redirect(
                $this->generateUrl('mail')
            );
        }

        return $this->render('message/creation.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function view(ManagerRegistry $doctrine, Request $request,  UserInterface $user): Response {

        $idMessage = $request->attributes->get('id');
        $message_repo = $doctrine->getManager()->getRepository(Message::class);
        $message = $message_repo->find($idMessage);

        if($message->getToEmail() != $user->getEmail()){
            return $this->redirect(
                $this->generateUrl('mail')
            );
        }

        $message->setViewed(1);
        $message_manager = $doctrine->getManager();
        $message_manager->persist($message);
        $message_manager->flush();

        $message_reply = new message();
        $form = $this->createForm(Message_form_reply::class, $message_reply);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $message_reply->setSent(new \DateTime('now'));
            $message_reply->setUser($user);
            $message_reply->setToEmail($message->getUser()->getEmail());

            $entityManager = $doctrine->getManager();
            $entityManager->persist($message_reply);
            $entityManager->flush();
            return $this->redirect(
                $this->generateUrl('mail')
            );
        }
        return $this->render('message/view.html.twig', [
            'message' => $message,
            'form' => $form->createView()
        ]);
    }

    public function delete(ManagerRegistry $doctrine, Request $request,  UserInterface $user): Response {
        $idMessage = $request->attributes->get('id');
        $message_repo = $doctrine->getManager()->getRepository(Message::class);
        $message = $message_repo->find($idMessage);

        if($message->getToEmail() != $user->getEmail()){
            return $this->redirect(
                $this->generateUrl('mail')
            );
        }

        $message_manager = $doctrine->getManager();
        $message_manager->remove($message);
        $message_manager->flush();

        return $this->redirect(
            $this->generateUrl('inbox')
        );

    }
}
