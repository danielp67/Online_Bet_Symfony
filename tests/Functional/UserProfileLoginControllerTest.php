<?php

namespace App\Tests\Functional;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserProfileLoginControllerTest extends WebTestCase
{
    public function testUserLoginResponse200(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $client->request('GET', '/app/user/profile/login');
        $this->assertResponseStatusCodeSame(200);
    }

    public function testUserProfileLoginWithAllLabel(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/user/profile/login');

        $this->assertSelectorTextContains('div.main h3', 'Mes identifiants');
        $this->assertCount(1, $crawler->filter('form input[name*="email"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="plainPassword"]'));
    }

    public function testUserProfileEditMail(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/user/profile/login');
        $this->assertResponseStatusCodeSame(200);

        $link = $crawler
            ->filter('div.main a')
            ->eq(0)
            ->link()
        ;

        $crawler = $client->click($link);

        $this->assertSelectorTextContains('div.main h3', 'Mes identifiants');
        $this->assertCount(1, $crawler->filter('form input[name*="edit_email[oldEmail]"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="edit_email[email]"]'));
        $this->assertSelectorExists('form button[type="submit"]');
    }

    public function testUserProfileEditMailSuccess(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/user/profile/edit/mail');
        $this->assertResponseStatusCodeSame(200);

        $form = $crawler
            ->filter('form')
            ->eq(0)
            ->form();

        $form['edit_email[email]'] = 'ladji.cda@test.com';

        $crawler = $client->submit($form);

        $this->assertResponseRedirects('/app/user/profile/login');
    }


    public function testUserProfileEditMailFail(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/user/profile/edit/mail');
        $this->assertResponseStatusCodeSame(200);

        $form = $crawler
            ->filter('form')
            ->eq(0)
            ->form();

        $form['edit_email[email]'] = 'ladji.cdatestcom';

        $crawler = $client->submit($form);

        $this->assertSelectorTextContains('', 'Format email incorrect');
    }


    public function testUserProfileEditPassword(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/user/profile/login');
        $this->assertResponseStatusCodeSame(200);

        $link = $crawler
            ->filter('div.main a')
            ->eq(1)
            ->link()
        ;

        $crawler = $client->click($link);

        $this->assertSelectorTextContains('div.main h3', 'Mes identifiants');
        $this->assertCount(1, $crawler->filter('form input[name*="edit_password[oldPassword]"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="edit_password[plainPassword][first]"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="edit_password[plainPassword][second]"]'));

        $this->assertSelectorExists('form button[type="submit"]');
    }

    public function testUserProfileEditPasswordSuccess(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/user/profile/edit/password');
        $this->assertResponseStatusCodeSame(200);

        $form = $crawler
            ->filter('form')
            ->eq(1)
            ->form();

        $form['edit_password[oldPassword]'] = 'M1cdacda8';
        $form['edit_password[plainPassword][first]'] = 'M1cdacda8';
        $form['edit_password[plainPassword][second]'] = 'M1cdacda8';

        $crawler = $client->submit($form);

        $this->assertResponseRedirects('/app/user/profile/login');
    }

    public function testUserProfileEditPasswordFailOldPassword(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/user/profile/edit/password');
        $this->assertResponseStatusCodeSame(200);

        $form = $crawler
            ->filter('form')
            ->eq(1)
            ->form();

        $form['edit_password[oldPassword]'] = 'M1cdacda10';
        $form['edit_password[plainPassword][first]'] = 'M1cdacda10';
        $form['edit_password[plainPassword][second]'] = 'M1cdacda10';

        $client->submit($form);

        $this->assertSelectorTextContains('', 'Ancien mot de passe incorrect');
    }

    public function testUserProfileEditPasswordFailNewPassword(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('ladji.cda@test.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/app/user/profile/edit/password');
        $this->assertResponseStatusCodeSame(200);

        $form = $crawler
            ->filter('form')
            ->eq(1)
            ->form();

        $form['edit_password[oldPassword]'] = 'M1cdacda8';
        $form['edit_password[plainPassword][first]'] = 'M1cdacda10';
        $form['edit_password[plainPassword][second]'] = 'M1cdacda11';

        $client->submit($form);

        $this->assertSelectorTextContains('', 'Les deux mots de passe doivent être identiques');
    }
}
