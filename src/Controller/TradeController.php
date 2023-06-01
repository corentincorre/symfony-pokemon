<?php

namespace App\Controller;

use App\Entity\Trade;
use App\Form\AddTradeType;
use App\Repository\TradeRepository;
use App\Repository\UserRepository;
use App\Repository\PokemonRepository;
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
    public function add(UserInterface $user, UserRepository $ur): Response
    {
        return $this->render('trade/add.html.twig', [
            'user' => $user,
            'users' => $ur->findAll(),
            'controller_name' => 'TradeController',
        ]);
    }
    #[Route('/trade/add2', name: 'app_trade_add2')]
    public function add2(UserInterface $user, UserRepository $ur, PokemonRepository $pr, Request $request, EntityManagerInterface $em): Response
    {
        $receiver = $request->request->get('receiver');
        if (!$receiver) return $this->redirectToRoute('app_trade_add');
        $receiver = $ur->findOneBy(['id'=>$receiver]);
        $trade = new Trade();
        $trade->setSender($user);
        $trade->setReceiver($receiver);
        $pkmSender = $request->request->get('pkm-sender');
        $pkmReceiver = $request->request->get('pkm-receiver');
        if ($pkmSender && $pkmReceiver){
            $pkmSender = $pr->findOneBy(['id'=>$pkmSender]);
            $pkmReceiver = $pr->findOneBy(['id'=>$pkmReceiver]);
            $trade->setSenderPokemon($pkmSender);
            $trade->setRecieverPokemon($pkmReceiver);
            $trade->setState('en cours');
            $em->persist($trade);
            $em->flush();
            return $this->redirectToRoute('app_trade');
        }

        return $this->render('trade/add2.html.twig', [
            'user' => $user,
            'receiver' => $receiver,
            'pkmSender' => $pr->findBy(['user' => $user]),
            'pkmReceiver' => $pr->findBy(['user' => $receiver]),
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
        $pkmSender = $trade->getSenderPokemon();
        $pkmReceiver = $trade->getRecieverPokemon();
        $sender = $trade->getSender();
        $receiver= $trade->getReceiver();

        $pkmSender->setUser($receiver);
        $pkmReceiver->setUser($sender);

        $trade->setState('accepté');

        $em->persist($pkmSender);
        $em->persist($pkmReceiver);
        $em->persist($trade);
        $em->flush();
        return $this->redirectToRoute('app_trade');
    }
}
