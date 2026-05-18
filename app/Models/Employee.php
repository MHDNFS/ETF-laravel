<?php

namespace App\Models;

use Database\Factories\EmployeeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'company',
    'name',
    'nic',
    'epf_no',
    'branch',
    'designation',
    'bank_account',
    'status',
])]
class Employee extends Model
{
    /** @use HasFactory<EmployeeFactory> */
    use HasFactory;

    public function displayValue(?string $value): string
    {
        $trimmed = trim((string) $value);

        return $trimmed === '' ? '—' : $trimmed;
    }
}
