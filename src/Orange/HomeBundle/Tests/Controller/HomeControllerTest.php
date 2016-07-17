<?php

namespace Orange\HomeBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
    }

    public function testDisplaytypo()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/arbo/{libelle}');
    }

    public function testDisplaysoustypo()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/arbo/{libelle}/{route}');
    }

}
