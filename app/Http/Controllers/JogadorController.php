<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponseTrait;

class JogadorController extends Controller
{
    use ApiResponseTrait;

    public function ranking()
    {
        return $this->jsonResponse([]);
    }
}
