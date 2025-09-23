<?php

namespace App\Traits;

use Illuminate\Validation\ValidationException;

trait WithCustomValidation
{
    public function customValidate(array $extraRules = []): bool
    {
        try {
            $this->validate(array_merge($this->rules(), $extraRules));

            return true;
        } catch (ValidationException $e) {
            $messages = collect($e->validator->errors()->all())->implode("<br>");
            $this->dispatch('swal', title: 'Ошибка', text: $messages);

            return false;
        }
    }
}
