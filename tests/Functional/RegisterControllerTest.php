<?php

namespace App\Tests\Functional;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterControllerTest extends WebTestCase
{
/*
    public function testLoginReponse200()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $this->assertResponseStatusCodeSame(200);
    }

    public function testLoginWithAllLabel()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $this->assertSelectorExists('form');
        $this->assertSelectorExists('form button[type=submit]');
        $this->assertCount(1, $crawler->filter('form input[name*="email"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="password"]'));
    }


    public function testLoginSubmitWithSuccess()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->filter('form')->form();

        $form['email'] = 'daniel@test3.cda';
        $form['password'] = 'Wxcv123456';

        $crawler = $client->submit($form);

        $this->assertResponseRedirects('/home');
        $crawler = $client->followRedirect();

        $this->assertSelectorTextContains('h1', 'Le Formulaire  a été validé');
    }


    public function testInvalidLoginSubmit()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->filter('form')->form();

        $crawler = $client->submit($form);

        $this->assertSelectorTextContains('', 'Email vide');
        $this->assertSelectorTextContains('', 'Password vide');
    }
    */
// Test Register
    public function testRegisterResponse200()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');
        $this->assertResponseStatusCodeSame(200);
    }

    public function testRegisterWithAllLabel()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');
        $this->assertSelectorExists('form');
        $this->assertCount(1, $crawler->filter('form input[name*="firstName"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="lastName"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="email"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="password"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="birthDate"]'));
        $this->assertCount(1, $crawler->filter('form input[name*="agreeTerms"]'));
        $this->assertSelectorExists('form button[type=submit]');
    }

    public function testRegisterSubmitWithSuccess()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $form = $crawler->filter('form')->form();

        $form['registration_form[lastName]'] = 'cda';
        $form['registration_form[firstName]'] = 'daniel';
        $form['registration_form[email]'] = 'daniel.cda@phpunit15.com';
        $form['registration_form[password]'] = 'M1cdacda8';
        $form['registration_form[birthDate]'] = '2000-10-02';
        $form['registration_form[agreeTerms]'] = "1";

        $crawler = $client->submit($form);

        $this->assertResponseRedirects('/app');
        $crawler = $client->followRedirect();

        $this->assertSelectorTextContains('', 'Le Formulaire a été validé');
    }


    public function testInvalidRegisterSubmit()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $form = $crawler->filter('form')->form();

        $crawler = $client->submit($form);

        $this->assertSelectorTextContains('', 'Nom vide');
        $this->assertSelectorTextContains('', 'Prénom vide');
        $this->assertSelectorTextContains('', 'Email vide');
        $this->assertSelectorTextContains('', 'Password vide');
        $this->assertSelectorTextContains('', 'Date de naissance vide');
        $this->assertSelectorTextContains('', 'Vous devez accepter les termes du contrat');
    }

    public function testInvalidRegisterSubmitwithEmailAlreadyUse()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $form = $crawler->filter('form')->form();
        $form['registration_form[lastName]'] = 'cda';
        $form['registration_form[firstName]'] = 'daniel';
        $form['registration_form[email]'] = 'daniel.cda@test.com';
        $form['registration_form[password]'] = 'M1cdacda8';
        $form['registration_form[birthDate]'] = '2000-10-02';
        $form['registration_form[agreeTerms]'] = "1";

        $crawler = $client->submit($form);

        $this->assertSelectorTextContains('', 'Il y a déjà un compte avec cet email');
    }


    public function testUserSetOnDb()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);

        $user = $userRepository->findOneBy(['email' => 'daniel.cda@test.com']);

        $this->assertInstanceOf(User::class, $user);
    }
}