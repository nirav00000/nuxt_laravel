<?php

namespace App\Services;

class EmailRedundancy
{
    public function filterEmail($email)
    {
        //dd('dfdf');
        $filteredEmail = [];
        $hrEmail = config('filters.hr');

        if ($email != $hrEmail[0]) {
            array_push($filteredEmail, $hrEmail[0]);
        }

        array_push($filteredEmail, $email);

        return $filteredEmail;
    }
}
