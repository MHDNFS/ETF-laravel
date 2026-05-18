<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company' => 'ABS',
            'name' => fake()->name(),
            'nic' => fake()->optional()->numerify('#########V'),
            'epf_no' => fake()->optional()->numerify('##'),
            'branch' => 'ABS',
            'designation' => null,
            'bank_account' => null,
            'status' => 'active',
        ];
    }
}
