<?php


namespace Tests\AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class BookingControllerTest
 * @package Tests\AppBundle\Controller
 */
class BookingControllerTest extends WebTestCase
{
    /**
     * @param $way
     * @param $address
     * @param $expectedResult
     * @dataProvider findYourWay
     */
    public function testPagesAreUp($way, $address, $expectedResult)
    {
        $client = static::createClient();
        $client->request($way, $address);

        $this->assertSame($expectedResult, $client->getResponse()->getStatusCode());
    }

    /**
     * @return array
     */
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

    /**
     * Functional test from step one to step three, stop before payment
     */
    public function testStepOne()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/fr/');

        $form = $crawler->selectButton('indexValidation')->form();
        $form['booking[visitDate]'] = '01/12/2018';
        $form['booking[typeOfTicket]'] = 0;
        $form['booking[numberOfTickets]'] = 2;
        $form['booking[email][first]'] = 'rozenn.paris@gmail.com';
        $form['booking[email][second]'] = 'rozenn.paris@gmail.com';
        $crawler = $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect()); //possible to put an absolute URL

        $crawler = $client->followRedirect();

        $this->assertCount(2, $crawler->filter('label:contains("Pays")'));

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

        $this->assertCount(18, $crawler->filter('td'));

    }

}
