<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AgePreference;
use App\Models\PreferedPropertyType;
use App\Models\ChooseYourIdentity;
use App\Models\Budget;
use App\Traits\ApiResponse;

class DynamicInputController extends Controller
{
    use ApiResponse;

    public function age_preferences()
    {
        $age_preferences = AgePreference::all();

        if ($age_preferences->isEmpty()) {
            return $this->error([], 'No age preferences data found', 404);
        }

        return $this->success($age_preferences, 'All data fetched successfully', 200);
    }


    public function prefered_property_types()
    {
        $prefered_property_types = PreferedPropertyType::all();

        if ($prefered_property_types->isEmpty()) {
            return $this->error([], 'No preferred property types data found', 404);
        }

        return $this->success($prefered_property_types, 'All data fetched successfully', 200);
    }


    public function choose_your_identities()
    {
        $choose_your_identities = ChooseYourIdentity::all();

        if ($choose_your_identities->isEmpty()) {
            return $this->error([], 'No identity options data found', 404);
        }

        return $this->success($choose_your_identities, 'All data fetched successfully', 200);
    }


    public function budgets()
    {
        $budgets = Budget::all();

        if ($budgets->isEmpty()) {
            return $this->error([], 'No budget data found', 404);
        }

        return $this->success($budgets, 'All data fetched successfully', 200);
    }
}
