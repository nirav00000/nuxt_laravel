<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\ResponseSuccessResource;
use App\Candidacy;

class PositionController extends Controller
{
    //
    public function index()
    {
        $positions = Candidacy::select('position')
            ->distinct()
            ->pluck('position');

        $res = new ResponseSuccessResource(
            $positions,
            "All positions retrived."
        );

        return response($res, Response::HTTP_OK);
    }
}
