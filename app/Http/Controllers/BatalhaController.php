<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Heroi;
use App\Models\Monstro;

use App\Traits\ApiResponseTrait;
use App\Traits\PersonagensTrait;

class BatalhaController extends Controller
{
    use ApiResponseTrait;
    use PersonagensTrait;

    
}
