<?php

namespace App\Entity;

use App\Repository\WalletRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=WalletRepository::class)
 */
class Wallet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull(
     *      message="Balance incorrect",
     *      groups={"wallet"}
     * )
     * @Assert\Type(
     *      type="integer",
     *      message="{{ value }} n'est pas du type {{ type }}",
     *      groups={"wallet"}
     * )
     * @Assert\PositiveOrZero(
     *      message="Balance not less than 0",
     *      groups={"wallet"}
     * )
     */
    private int $balance;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull(
     *  message="Limite incorrecte",
     *  groups={"limitAmountPerWeek", "wallet"}
     * )
     * @Assert\PositiveOrZero(
     *  message="Limite incorrecte",
     *  groups={"limitAmountPerWeek", "wallet"}
     * )
     * @Assert\LessThanOrEqual(
     *  value = 10000,
     *  message="Limite maximum 100,00 euros",
     *  groups={"limitAmountPerWeek", "wallet"}
     * )
     */
    private int $limitAmountPerWeek;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotNull(
     *      message="Money incorrect",
     *      groups={"wallet"}
     * )
     */
    private bool $realMoney;


    /**
     * @ORM\OneToMany(targetEntity=Payment::class, mappedBy="wallet")
     * @var Collection<int, Payment>|null
     */
    private ?Collection $payments;

    public function __construct()
    {
        $this->payments = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function getBalance(): float
    {
        return $this->balance / 100;
    }

    /**
     * @return int
     */
    public function getLimitAmountPerWeek(): int
    {
        return $this->limitAmountPerWeek / 100;
    }

    /**
     * @param int $limitAmountPerWeek
     */
    public function setLimitAmountPerWeek(int $limitAmountPerWeek): void
    {
        $this->limitAmountPerWeek = $limitAmountPerWeek * 100;
    }

    /**
     * @return bool
     */
    public function isRealMoney(): bool
    {
        return $this->realMoney;
    }

    /**
     * @param bool $realMoney
     */
    public function setRealMoney(bool $realMoney): void
    {
        $this->realMoney = $realMoney;
    }

    public function initializeWallet(bool $realMoney): void
    {
        if ($realMoney) {
            $this->balance = 0;
        } else {
            $this->balance = 10000;
        }
            $this->realMoney = $realMoney;
            $this->limitAmountPerWeek = 10000;
    }

    public function addMoney(float $amount): bool
    {
        if ($amount <= 0) {
            return false;
        }
        $this->balance += (int) $amount * 100;

        return true;
    }

    public function withdrawMoney(float $amount): bool
    {
        if ($amount <= 0 or $amount > $this->getBalance()) {
            return false;
        }
        $this->balance -= (int) $amount * 100;

        return true;
    }

    public function betPayment(float $amount, int $amountBetPaymentLastWeek): int
    {
        if ($amount <= 0 or $amount > $this->getBalance()) {
            return 0;
        }
        if ($amount > $this->getLimitAmountPerWeek() - $amountBetPaymentLastWeek) {
            return 1;
        }
        $this->balance -= (int) $amount * 100;

        return 2;
    }

    /**
     * @return  Collection<int, Payment>|Payment[]
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(Payment $payment): self
    {
        if (!$this->payments->contains($payment)) {
            $this->payments[] = $payment;
            $payment->setWallet($this);
        }

        return $this;
    }
/* Pas nécessaire
    public function removePayment(Payment $payment): self
    {
        if ($this->payments->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getWallet() === $this) {
                $payment->setWallet(null);
            }
        }

        return $this;
    }*/
}
