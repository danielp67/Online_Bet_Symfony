<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Item;
use App\Repository\BetRepository;
use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("app/cart/add/{betId}/{expectedResult}", name="app_cart_add_item")
     */
    public function addItemToCart(int $betId, int $expectedResult, BetRepository $betRepository): Response
    {
        $user = $this->getUser();
        if ($user->getCart() === null) {
            $cart = new Cart();
            $user->setCart($cart);
        }

        $cart = $user->getCart();

        $bet = $betRepository->find($betId);
        $odds = $bet->getListOfOdds()[$expectedResult];

        $item = new Item($bet);
        $item->setExpectedBetResult($expectedResult);
        $item->isModifiedRecordedOdds($odds);
        $item->isModifiedAmount(5);

        $cart->addItem($item);
        $cart->setSum();

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($item);
        $entityManager->persist($cart);
        $entityManager->flush();

        return $this->redirectToRoute('app');
    }

    /**
     * @Route("app/cart/remove/{itemId}", name="app_cart_remove_item")
     */
    public function removeItemFromCart(int $itemId, ItemRepository $itemRepository): Response
    {
        $user = $this->getUser();
        $cart = $user->getCart();

        $item = $itemRepository->find($itemId);
        $cart->removeItem($item);
        $cart->setSum();

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($item);

        if (count($cart->getItems()) === 0) {
            $user->setCart(null);
            $entityManager->remove($cart);
            $entityManager->persist($user);
        } else {
            $entityManager->persist($cart);
        }
        $entityManager->flush();

        return $this->redirectToRoute('app');
    }
}
