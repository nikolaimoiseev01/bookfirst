<?php

namespace App\Rules;

use App\Models\Participation;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class SameParticipation implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return Participation::where('collection_id', $value)->where('user_id',Auth::user()->id)->count() == 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Вы уже учавствуете в этом сборнике!';
    }
}
