<?php

namespace App\Controller;

use App\Entity\Pokemon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class GameController extends AbstractController
{
    #[Route('/game', name: 'app_game')]
    public function index(): Response
    {
        return $this->render('game/index.html.twig', [
            'controller_name' => 'GameController',
        ]);
    }
    #[Route('/capture', name: 'app_capture')]
    public function capture(UserInterface $user, EntityManagerInterface $em): Response
    {
        $randomPkm = $this->spin();
        if(!$randomPkm || ($user->getLastGame() && $user->getLastGame() > date('Y-m-d',strtotime('-2 minutes')))){
            $this->addFlash('error', 'Aucun pokemon n\'a été trouvé');
            return $this->redirect('app_home_page');
        }
        $pokemon = new Pokemon();
        $pokemon->setUser($user);
        $pokemon->setPokemonName($randomPkm->name);
        $pokemon->setPokemonImage($randomPkm->sprites->front_default);
        $user->setLastGame(date('Y-m-d'));
        $em->persist($pokemon);
        $em->persist($user);
        $em->flush();

        return $this->render('pokemon/capture.html.twig', [
            'controller_name' => 'HomeController',
            'name' => $pokemon->getPokemonName(),
            'img' => $pokemon->getPokemonImage(),
        ]);
    }

    private function spin() {
        $randomNumber = rand(1, 151 );
        return $this->getPokemon($randomNumber);
    }

    private function getPokemon($id) {
        $url = "https://pokeapi.co/api/v2/pokemon/".$id;
        $response = file_get_contents($url);
        return json_decode($response);
    }
}
