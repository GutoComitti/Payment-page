<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CPF implements ValidationRule
{

  public function validate(string $attribute, mixed $value, Closure $fail): void
  {
    $errorMessage = "Invalid CPF.";
    // Extracting only numbers since the value can be using a mask
    $cpf = preg_replace('/[^0-9]/is', '', $value);
    // Check size number
    if (strlen($cpf) !== 11)
    {
      $fail($errorMessage);
    }
    // Avoiding repeated digits. (111.111.111-11)
    if (preg_match('/(\d)\1{10}/', $cpf))
    {
        $fail($errorMessage);
    }
    // Validating based on the calculation of the CPF
    for ($t = 9; $t < 11; $t++)
    {
      for ($d = 0, $c = 0; $c < $t; $c++)
      {
        $d += $cpf[$c] * (($t + 1) - $c);
      }
      $d = ((10 * $d) % 11) % 10;
      if ($cpf[$c] != $d)
      {
        $fail($errorMessage);
      }
    }
  }
}
