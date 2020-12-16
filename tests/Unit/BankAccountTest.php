<?php

namespace App\Tests\Unit;

use App\Entity\BankAccount;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class BankAccountTest extends KernelTestCase
{
    public function testAssertBankAccountInstance()
    {
        $bankAccount = new BankAccount();
        $this->assertInstanceOf(BankAccount::class, $bankAccount);
        $this->assertClassHasAttribute('id', BankAccount::class);
        $this->assertClassHasAttribute('ibanCode', BankAccount::class);
        $this->assertClassHasAttribute('bicCode', BankAccount::class);
    }


    /************ Kernel ***************/


    public function getKernel(): KernelInterface
    {
        $kernel = self::bootKernel();
        $kernel->boot();

        return $kernel;
    }

    public function numberOfViolations(BankAccount $bankAccount, $groups)
    {
        $kernel = $this->getKernel();

        $validator = $kernel->getContainer()->get('validator');
        $violationList = $validator->validate($bankAccount, null, $groups);

        return count($violationList);
    }


    /**
     * @dataProvider validBankAccountProvider
     */

    public function testValidBankAccount(BankAccount $bankAccount, $groups, $numberOfViolations)
    {
        $this->assertSame($numberOfViolations, $this->numberOfViolations($bankAccount, $groups));
    }

    public function validBankAccountProvider()
    {
        return [
            [BankAccount::build('FR7630006000011234567890189', 'BNPAFRPPTAS'), null, 0],
        ];
    }


    /**
     * @dataProvider invalidBankAccountProvider
     */
    public function testInvalidBankAccount(BankAccount $bankAccount, $groups, $numberOfViolations)
    {
        $this->assertSame($numberOfViolations, $this->numberOfViolations($bankAccount, $groups));
    }

    public function invalidBankAccountProvider()
    {
        return [
            [BankAccount::build('1', '1'), ['bankInfo'], 2],
            [BankAccount::build(null, 'F0rmatB1CInval1d3'), ['bicCode'], 1],
            [BankAccount::build('F0rmat1B4NInval1d3', null), ['ibanCode'], 1]

        ];
    }
}