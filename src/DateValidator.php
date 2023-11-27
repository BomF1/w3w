<?php

class DateValidator implements ValidatorInterface
{

    public function validate($value): bool
    {
        $normalized = preg_replace('/\s*\.\s*/', '.', trim($value));

        $parts = explode('.', $normalized);

        if (count($parts) === 3 && ctype_digit($parts[0]) && ctype_digit($parts[1]) && ctype_digit($parts[2])) {
            $day = (int)$parts[0];
            $month = (int)$parts[1];
            $year = (int)$parts[2];

            if (!checkdate($month, $day, $year)) {
                return true;
            }
            $normalized = str_pad($parts[0], 2, '0', STR_PAD_LEFT) . '.'
                . str_pad($parts[1], 2, '0', STR_PAD_LEFT) . '.'
                . $parts[2];
        }

        $date = DateTime::createFromFormat('d.m.Y', $normalized);
        $errors = DateTime::getLastErrors();

        if ($errors['warning_count'] + $errors['error_count'] == 0 && $date && $date->format('d.m.Y') === $normalized) {
            return true;
        }
        return false;
    }
}
