<?php

namespace App\Models;

use Database\Factories\EmployeeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
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

    /**
     * @param  Builder<Employee>  $query
     * @return Builder<Employee>
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if ($term === null || trim($term) === '') {
            return $query;
        }

        $like = '%'.trim($term).'%';

        return $query->where(function (Builder $q) use ($like) {
            $q->where('name', 'like', $like)
                ->orWhere('nic', 'like', $like)
                ->orWhere('epf_no', 'like', $like)
                ->orWhere('designation', 'like', $like)
                ->orWhere('bank_account', 'like', $like);
        });
    }

    /**
     * @param  Builder<Employee>  $query
     * @return Builder<Employee>
     */
    public function scopeFilterCompany(Builder $query, ?string $company): Builder
    {
        if ($company === null || trim($company) === '') {
            return $query;
        }

        return $query->where('company', trim($company));
    }

    /**
     * @param  Builder<Employee>  $query
     * @return Builder<Employee>
     */
    public function scopeFilterBranch(Builder $query, ?string $branch): Builder
    {
        if ($branch === null || trim($branch) === '') {
            return $query;
        }

        return $query->where('branch', trim($branch));
    }

    /**
     * @param  Builder<Employee>  $query
     * @return Builder<Employee>
     */
    public function scopeFilterStatus(Builder $query, ?string $status): Builder
    {
        if ($status === null || trim($status) === '') {
            return $query;
        }

        return $query->where('status', trim($status));
    }
}
