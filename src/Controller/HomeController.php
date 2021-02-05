<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\User;
use App\Repository\BetRepository;
use App\Repository\PaymentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/app", name="app")
     */
    public function homePage(BetRepository $betRepository): Response
    {
        $listOfBet = $betRepository->findAllSimpleBet();

        $user = $this->getUser();
        assert($user instanceof User);

        /** @var Cart|null $cart */
        $cart = $user->getCart();

        if ($cart) {
            $items = $cart->getItems();
        } else {
            $items = null;
        }

        return $this->render('home/home.html.twig', [
            'listOfBet' => $listOfBet,
            'cart' => $cart,
            'items' => $items,

        ]);
    }
}
