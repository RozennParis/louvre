<?php

namespace AppBundle\Service;

class AgeCalculator
{
    public function ageCalcul(\DateTimeInterface $visitDate, \DateTimeInterface $birthDate)
    {
        return date_diff($visitDate, $birthDate)->y;
    }
}
