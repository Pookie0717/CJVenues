<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;

class AtLeastOneCapacity implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $hasCapacity = collect(request()->only([
            'capacity_noseating',
            'capacity_seatingrows',
            'capacity_seatingtables',
        ]))->filter()->count() > 0;

        return $hasCapacity;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'At least one of the capacity fields must be filled.';
    }
}
