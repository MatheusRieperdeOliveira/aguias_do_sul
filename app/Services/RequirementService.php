<?php

namespace App\Services;

use App\Models\Requirement;

class RequirementService
{
    public function getRequirements(){
        return Requirement::all();
    }

}
