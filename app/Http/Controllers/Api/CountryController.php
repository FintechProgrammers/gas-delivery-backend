<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CountryResource;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CountryController extends Controller
{
    function index()
    {
        $countries = Country::all()->sortBy('name');

        $countries = CountryResource::collection($countries);

        return $this->sendResponse($countries);
    }

    public function updateCountriesTableWithFlags()
    {
        $filePath = public_path('assets/countries.json');
        $countriesJson = File::get($filePath);
        $countries = json_decode($countriesJson, true);

        foreach ($countries as $countryData) {
            $iso3 = $countryData['cca3'];
            $flagUrl = $countryData['flags']['svg'];

            // Update the 'flag' field in the 'countries' table
            Country::where('iso3', $iso3)->update(['flag' => $flagUrl]);
        }

        echo true;
    }
}
