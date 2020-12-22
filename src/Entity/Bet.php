<?php

namespace App\Entity;

use App\Repository\BetRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BetRepository::class)
 */
class Bet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(
     *  message="Date limite vide",
     *  groups={"limitTime", "bet"}
     * )
     * @Assert\GreaterThan(value="+1 hours",
     *      message="Date limite incorrecte",
     *      groups={"limitTime", "bet"}
     * )
     */
    private DateTimeInterface $betLimitTime;

    /**
     * @ORM\Column(type="array")
     * @Assert\NotBlank(
     *      message="Liste vide",
     *      groups={"bet"}
     * )
     */
    private array $listOfOdds = [];

    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotBlank(
     *      message="Status du pari vide",
     *      groups={"bet", "betStatus"}
     * )
     */
    private bool $betOpened;

    /**
     * @ORM\ManyToOne(targetEntity=TypeOfBet::class)
     * @Assert\NotNull(
     *      message="Type of Bet is empty",
     *     groups={"bet"}
     * )
     * @Assert\Valid
     */
    private TypeOfBet $typeOfBet;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getListOfOdds(): ?array
    {
        return $this->listOfOdds;
    }

    public function setListOfOdds(array $listOfOdds): self
    {
        $this->listOfOdds = $listOfOdds;

        return $this;
    }

    public function getBetLimitTime(): ?DateTimeInterface
    {
        return $this->betLimitTime;
    }

    public function setBetLimitTime(DateTimeInterface $betLimitTime): self
    {
        $this->betLimitTime = $betLimitTime;

        return $this;
    }

    public function isOpen(): ?bool
    {
        return $this->betOpened;
    }

    public function openBet(): void
    {
        $this->betOpened = true;
    }

    public function closeBet(): void
    {
        $this->betOpened = false;
    }

    /**
     * Entity builder with the requested parameters
     *
     * @param string|null $betLimitTime
     * @param array|null $listOfOdds
     * @param int|null $typeOfBetId
     * @return  self
     */
    public static function build(
        ?string $betLimitTime,
        ?array $listOfOdds,
        ?int $typeOfBetId
    ): Bet {
        $bet = new Bet();
        $betLimitTime ? $bet->setBetLimitTime(DateTime::createFromFormat('Y-m-d', $betLimitTime)) : null ;
        $listOfOdds ? $bet->setListOfOdds($listOfOdds) : null ;
        $typeOfBetId !== null ? $bet->setTypeOfBet(new TypeOfBet()) : null ;

        $bet->openBet();

        return $bet;
    }

    public function getTypeOfBet(): ?TypeOfBet
    {
        return $this->typeOfBet;
    }

    public function setTypeOfBet(TypeOfBet $typeOfBet): self
    {
        $this->typeOfBet = $typeOfBet;

        return $this;
    }
}
