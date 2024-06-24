<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\Country;

class CountryController extends Controller
{
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
