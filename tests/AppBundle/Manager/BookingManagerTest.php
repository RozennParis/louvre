<?php

namespace Tests\AppBundle\Manager;

use AppBundle\Entity\Booking;
use AppBundle\Manager\BookingManager;
use PHPUnit\Framework\TestCase;

/**
 * Class BookingManagerTest
 * @package Tests\AppBundle\Manager
 */
class BookingManagerTest extends TestCase
{
    /**
     * @dataProvider dataForAdditionOfTickets
     * @param $numberOfTickets
     * @param $expectedNumber
     */
    public function testCompleteInit($numberOfTickets, $expectedNumber)
    {
        $booking = new Booking(); // -> faire setUp()
        $booking->setNumberOfTickets($numberOfTickets);

        /**
         * @var BookingManager $bookingManager
         */
        $bookingManager = $this->getMockBuilder(BookingManager::class)
            ->disableOriginalConstructor()
            ->setMethodsExcept(['completeInit'])
            ->getMock();

        $bookingManager->completeInit($booking);
        $this->assertSame($expectedNumber,$booking->getTickets()->count());
    }

    /**
     * @return array
     */
    public function dataForAdditionOfTickets()
    {
        return [
            [1, 1],
            [5, 5],
            [10, 10],
        ];
    }
}
