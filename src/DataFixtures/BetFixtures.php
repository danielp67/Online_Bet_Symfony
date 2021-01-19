<?php

namespace App\DataFixtures;

use App\Entity\Bet;
use DateInterval;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BetFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $typeOfBet1 = $this->getReference(TypeOfBetFixtures::TYPE_OF_BET_1);
        $typeOfBet2 = $this->getReference(TypeOfBetFixtures::TYPE_OF_BET_2);


        $bet = new Bet();
        $bet->setBetLimitTime((new DateTime())->add(new DateInterval('P2D')));
        $bet->setListOfOdds([2.2, 1.5, 1.1]);
        $bet->setTypeOfBet($typeOfBet1);
        $bet->openBet();
        $manager->persist($bet);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            TypeOfBetFixtures::class,
        ];
    }
}
