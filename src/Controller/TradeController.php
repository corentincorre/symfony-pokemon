<?php

namespace App\Controller;

use App\Repository\TradeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class TradeController extends AbstractController
{
    #[Route('/trade', name: 'app_trade')]
    public function index(UserInterface $user, TradeRepository $tr): Response
    {

        return $this->render('trade/index.html.twig', [
            'controller_name' => 'TradeController',
            'trades' => $tr->findBy(['sender']),
        ]);
    }
    #[Route('/trade/new', name: 'app_new_trade')]
    public function add(UserInterface $user): Response
    {
        return $this->render('trade/new.html.twig', [
            'controller_name' => 'TradeController',
        ]);
    }
}
