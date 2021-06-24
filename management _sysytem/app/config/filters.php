<?php

return [

    /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        |
        | Use to filter records from database. Add model, column name and filter sign. This value is used by ApiFilter to    | apply where/like conditions and get records from database.
        |
    */
        'feedback' => [
            'feedback'      => '=',
            'user_id'       => '=',
            'description'   => 'like',
        ],


        'candidacy'     => [
        'final_status' => '=',
        'position'     => 'like',
        ],
        'candidacy_has' => [
        'candidate.name' => [
            'param'   => 'candidate_name',
            'operand' => 'like',
        ],
        ],






];
