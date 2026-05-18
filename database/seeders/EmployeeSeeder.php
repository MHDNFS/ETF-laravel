<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public static function records(): array
    {
        return [
            [
                'company' => 'ABS',
                'name' => 'M M A SALAM',
                'nic' => '541682864V',
                'epf_no' => '15',
                'branch' => 'ABS',
                'designation' => 'Senior Portfolio Manager',
                'bank_account' => '801234567890',
                'status' => 'active',
            ],
            [
                'company' => 'ABS',
                'name' => 'Nabeel un',
                'nic' => '901234567V',
                'epf_no' => '22',
                'branch' => 'ABS',
                'designation' => 'Fund Operations Executive',
                'bank_account' => '772345678901',
                'status' => 'active',
            ],
            [
                'company' => 'ABS',
                'name' => 'Nabeel ko',
                'nic' => '882345678V',
                'epf_no' => '23',
                'branch' => 'Colombo',
                'designation' => 'Investment Analyst',
                'bank_account' => '883456789012',
                'status' => 'active',
            ],
            [
                'company' => 'ABS',
                'name' => 'Habeeb',
                'nic' => '773456789V',
                'epf_no' => '31',
                'branch' => 'ABS',
                'designation' => 'Compliance Officer',
                'bank_account' => '994567890123',
                'status' => 'active',
            ],
            [
                'company' => 'ABS',
                'name' => 'Naseer tec',
                'nic' => '664567890V',
                'epf_no' => '38',
                'branch' => 'Kandy',
                'designation' => 'IT Systems Administrator',
                'bank_account' => '705678901234',
                'status' => 'active',
            ],
            [
                'company' => 'ABS',
                'name' => 'Nawaar',
                'nic' => '955678901V',
                'epf_no' => '41',
                'branch' => 'ABS',
                'designation' => 'Client Relations Manager',
                'bank_account' => '816789012345',
                'status' => 'active',
            ],
            [
                'company' => 'ABS',
                'name' => 'Nizar',
                'nic' => '846789012V',
                'epf_no' => '44',
                'branch' => 'Galle',
                'designation' => 'Treasury Assistant',
                'bank_account' => '927890123456',
                'status' => 'active',
            ],
            [
                'company' => 'ABS',
                'name' => 'Rimaas',
                'nic' => '737890123V',
                'epf_no' => '47',
                'branch' => 'ABS',
                'designation' => 'Research Associate',
                'bank_account' => '638901234567',
                'status' => 'active',
            ],
            [
                'company' => 'ABS',
                'name' => 'Himy mow',
                'nic' => '928901234V',
                'epf_no' => '52',
                'branch' => 'Colombo',
                'designation' => 'Risk Analyst',
                'bank_account' => '749012345678',
                'status' => 'active',
            ],
            [
                'company' => 'ABS',
                'name' => 'Fawstheen Yaasir',
                'nic' => '819012345V',
                'epf_no' => '56',
                'branch' => 'ABS',
                'designation' => 'Junior ETF Specialist',
                'bank_account' => '850123456789',
                'status' => 'active',
            ],
        ];
    }

    public function run(): void
    {
        foreach (self::records() as $record) {
            Employee::query()->updateOrCreate(
                [
                    'company' => $record['company'],
                    'name' => $record['name'],
                ],
                $record,
            );
        }
    }
}
