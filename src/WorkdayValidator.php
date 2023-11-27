<?php

class WorkdayValidator implements ValidatorInterface
{

    public function validate($value): bool
    {
        try {
        $date = new DateTime($value);
        } catch (Exception $e) {
            return false;
        }
        $dateOfWeek = $date->format('N');
        return $dateOfWeek >= 1 && $dateOfWeek <= 5;
    }
}
