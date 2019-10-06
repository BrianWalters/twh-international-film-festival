<?php


namespace App\Service;


class DateProvider
{
    public function getToday(): \DateTime
    {
        return new \DateTime('now');
    }
}