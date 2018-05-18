<?php


namespace Tests\AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookingControllerTest extends WebTestCase
{
    /**
     * @dataProvider findYourWay
     */
    public function testPagesAreUp($way, $address, $expectedResult)
    {
        $client = static::createClient();
        $client->request($way, $address);

        $this->assertSame($expectedResult, $client->getResponse()->getStatusCode());
    }

    public function findYourWay()
    {
        return [
            ['GET', '/', 302],
            ['GET', '/fr/', 200],
            ['GET', '/ticket', 404],
            ['GET', '/summary', 404],
            ['POST', '/final-summary/15', 404]
        ];
    }


    public function testStepOne()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/fr/');

        $form = $crawler->selectButton('indexValidation')->form();
        $form['booking[visitDate]'] = '2018-06-01';
        $form['booking[typeOfTicket]'] = 0;
        $form['booking[numberOfTickets]'] = 2;
        $form['booking[email]'] = 'rozenn.paris@gmail.com';
        $crawler = $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect()); //possible to put an absolute URL

        $crawler = $client->followRedirect();

        $this->assertCount(2, $crawler->filter('#tickets_tickets > div'));

        $formTicket = $crawler->selectButton('ticketValidation')->form();
        $formTicket['tickets[tickets][0][lastName]'] = 'Rozenn';
        $formTicket['tickets[tickets][0][firstName]'] = 'Paris';
        $formTicket['tickets[tickets][0][birthDate]'] = '1980-06-01';
        $formTicket['tickets[tickets][0][country]'] = 'FR';
        $formTicket['tickets[tickets][0][reduceRate]'] = 1;

        $formTicket['tickets[tickets][1][lastName]'] = 'Morgan';
        $formTicket['tickets[tickets][1][firstName]'] = 'Fautrel';
        $formTicket['tickets[tickets][1][birthDate]'] = '2005-09-16';
        $formTicket['tickets[tickets][1][country]'] = 'FR';

        $crawler = $client->submit($formTicket);

        $this->assertTrue($client->getResponse()->isRedirect());

        $crawler = $client->followRedirect();

        $this->assertCount(8, $crawler->filter('td'));

    }

}
