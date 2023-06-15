<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidBingoLink implements ValidationRule
{
    protected $domains;

    public function __construct(array $domains)
    {
        $this->domains = $domains;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            $fail('The :attribute must be a valid URL.');
        }

        $subdomain = parse_url($value, PHP_URL_HOST);

        if (!in_array($subdomain, $this->domains)) {
            $fail('The :attribute must be a one of the following domains: ' . implode(', ', $this->domains) . '.');
        }
    }
}
