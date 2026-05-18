<?php

namespace App\Http\Resources;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Employee */
class EmployeeResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'company' => $this->company,
            'name' => $this->name,
            'nic' => $this->displayValue($this->nic),
            'epf_no' => $this->displayValue($this->epf_no),
            'branch' => $this->branch,
            'designation' => $this->displayValue($this->designation),
            'bank_account' => $this->displayValue($this->bank_account),
            'status' => $this->status,
        ];
    }
}
