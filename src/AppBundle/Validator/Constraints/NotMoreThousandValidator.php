<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class NotMoreThousandValidator
 * @package AppBundle\Validator\Constraints
 */
class NotMoreThousandValidator extends ConstraintValidator
{
    const MAX_TICKET_PER_DAY = 1000;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * NotMoreThousandValidator constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager = $em;
    }

    /**
     * @param mixed $booking
     * @param Constraint $constraint
     */
    public function validate($booking, Constraint $constraint)
    {
        $ticketsPerDay = $this->entityManager
            ->getRepository('AppBundle:Booking')
            ->getNumberOfTicketPerDay($booking->getVisitDate());

        $ticketsLeft = self::MAX_TICKET_PER_DAY - $ticketsPerDay;

        if ($booking->getNumberOfTickets() >= $ticketsLeft)
        {
            $this->context->buildViolation($constraint->message)
                ->atPath('numberOfTickets')
                ->setParameter( '{{ ticketsLeft }}', ($ticketsLeft > 0)? $ticketsLeft: 0)
                ->addViolation();
        }
    }

}
