<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ParticipationLessPrice implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    private $paidAmount;
    public $currentPriceWithPrint;

    public function __construct($paidAmount, $currentPriceWithPrint)
    {
        $this->paidAmount = $paidAmount;
        $this->currentPriceWithPrint = $currentPriceWithPrint;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->paidAmount > $this->currentPriceWithPrint) {
            $fail('Новая цена не может быть меньше той, которую вы уже оплатили');
        }
    }
}
