<?php

namespace App\Controller;

use App\Entity\Trade;
use App\Form\AddTradeType;
use App\Repository\TradeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityManagerInterface;

class TradeController extends AbstractController
{
    #[Route('/trade', name: 'app_trade')]
    public function index(UserInterface $user, TradeRepository $tr): Response
    {

        return $this->render('trade/index.html.twig', [
            'controller_name' => 'TradeController',
            'user' => $user,
            'trades' => $tr->findBy(['sender'=>$user, 'state'=> 'en cours']),
        ]);
    }

    #[Route('/trade/history', name: 'app_trade_history')]
    public function history(UserInterface $user, TradeRepository $tr): Response
    {

        return $this->render('trade/history.html.twig', [
            'controller_name' => 'TradeController',
            'user' => $user,
            'trades' => $tr->findBy(['sender'=>$user, 'state'=> ['accepté', 'annulé']]),
        ]);
    }

    #[Route('/trade/add', name: 'app_trade_add')]
    public function add(UserInterface $user, Request $request, EntityManagerInterface $em): Response
    {
        $trade = new Trade();
        $trade->setSender($user);
        $form = $this->createForm(AddTradeType::class, $trade);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trade = $form->getData();
            $trade->setState('en cours');
            $em->persist($trade);
            $em->flush();
            return $this->redirectToRoute('app_trade');
        }

        return $this->render('trade/add.html.twig', [
            'user' => $user,
            'receiver' => $form->getData(),
            'form' => $form,
            'controller_name' => 'TradeController',
        ]);
    }

    #[Route('/trade/cancel/{id}', name: 'app_trade_cancel')]
    public function cancel(EntityManagerInterface $em, TradeRepository $tr, $id): Response
    {
        $trade = $tr->findOneBy(['id'=>$id]);
        $trade->setState('annulé');
        $em->persist($trade);
        $em->flush();
        return $this->redirectToRoute('app_trade');
    }

    #[Route('/trade/accept/{id}', name: 'app_trade_accept')]
    public function accept(EntityManagerInterface $em, TradeRepository $tr, $id): Response
    {
        $trade = $tr->findOneBy(['id'=>$id]);
        $trade->setState('annulé');
        $em->persist($trade);
        $em->flush();
        return $this->redirectToRoute('app_trade');
    }
}
