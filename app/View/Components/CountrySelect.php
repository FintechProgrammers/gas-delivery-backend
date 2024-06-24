<?php

namespace App\View\Components;

use App\Models\Country;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CountrySelect extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public $value = null)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $data['countries'] = Country::get();

        return view('components.country-select',$data);
    }
}
