<?php

namespace App\Controller;

use App\Repository\PokemonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home_page')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    #[Route('/capture', name: 'app_capture')]
    public function capture(UserInterface $user, PokemonRepository $pr, EntityManagerInterface $em): Response
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

        return $this->render('home/capture.html.twig', [
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
