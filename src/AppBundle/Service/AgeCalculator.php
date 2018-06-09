<?php

namespace AppBundle\Service;

/**
 * Class AgeCalculator
 * @package AppBundle\Service
 */
class AgeCalculator
{
    /**
     * @param \DateTimeInterface $visitDate
     * @param \DateTimeInterface $birthDate
     * @return int
     */
    public function ageCalcul(\DateTimeInterface $visitDate, \DateTimeInterface $birthDate)
    {
        return date_diff($visitDate, $birthDate)->y;
    }
}
