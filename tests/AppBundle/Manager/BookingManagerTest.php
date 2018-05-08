<?php
/**
 * Created by PhpStorm.
 * User: rozenn
 * Date: 07/05/18
 * Time: 15:32
 */

namespace Tests\AppBundle\Manager;

use AppBundle\Entity\Booking;
use AppBundle\Entity\Ticket;
use AppBundle\Manager\BookingManager;
use PHPUnit\Framework\TestCase;

class BookingManagerTest extends TestCase
{
    /**
     * @dataProvider dataForAdditionOfTickets
     * @param $numberOfTickets
     * @param $expectedNumber
     */
    public function testCompleteInit($numberOfTickets, $expectedNumber)
    {
        $booking = new Booking();
        $booking->setNumberOfTickets($numberOfTickets);

        while (count($booking->getTickets()) !== $booking->getNumberOfTickets())
        {
            if (count($booking->getTickets()) > $booking->getNumberOfTickets()) {
                $booking->removeTicket($booking->getTickets()->last());
            } else {
                $booking->addTicket(new Ticket());
            }
        }

        $this->assertSame($expectedNumber,$booking->getTickets()->count());
    }

    public function dataForAdditionOfTickets()
    {
        return [
            [1, 1],
            [5, 5],
            [10, 10],
            [3, 2] //failed test
        ];
    }
}
