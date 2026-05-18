{{-- Add Employee — fields match employees table + POST /api/employees (resources/js/app.js). --}}
<div class="modal-overlay" id="add-employee-modal">
    <div class="modal modal--employee">
        <div class="modal-header">
            <span class="modal-title" id="add-employee-modal-title">
                <i class="fa-solid fa-user-plus" style="color:var(--accent);margin-right:8px"></i>Add Employee
            </span>
            <button type="button" class="modal-close" onclick="closeModal('add-employee-modal')" aria-label="Close">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="modal-body">
            <div id="add-employee-form-error" class="login-alert login-alert--error" role="alert" hidden></div>

            <div class="employee-form trade-form" role="group" aria-labelledby="add-employee-modal-title">
                <div class="trade-row2">
                    <div class="trade-field">
                        <x-forms.form-label for="add-employee-company">Company</x-forms.form-label>
                        <div class="trade-control">
                            <x-forms.form-select id="add-employee-company" name="company" :searchable="false">
                                <option value="ABS" selected>ABS</option>
                            </x-forms.form-select>
                        </div>
                    </div>
                    <div class="trade-field">
                        <x-forms.form-label for="add-employee-branch">Branch</x-forms.form-label>
                        <div class="trade-control">
                            <x-forms.form-select id="add-employee-branch" name="branch" :searchable="false">
                                <option value="ABS" selected>ABS</option>
                                <option value="Colombo">Colombo</option>
                                <option value="Kandy">Kandy</option>
                                <option value="Galle">Galle</option>
                            </x-forms.form-select>
                        </div>
                    </div>
                </div>

                <div class="trade-field">
                    <x-forms.form-label for="add-employee-name">Employee name</x-forms.form-label>
                    <div class="trade-control">
                        <x-forms.form-input id="add-employee-name" name="name" type="text" placeholder="Full name" required />
                    </div>
                </div>

                <div class="trade-row2">
                    <div class="trade-field">
                        <x-forms.form-label for="add-employee-nic">NIC</x-forms.form-label>
                        <div class="trade-control">
                            <x-forms.form-input id="add-employee-nic" name="nic" type="text" placeholder="541682864V" />
                        </div>
                    </div>
                    <div class="trade-field">
                        <x-forms.form-label for="add-employee-epf-no">EPF No</x-forms.form-label>
                        <div class="trade-control">
                            <x-forms.form-input id="add-employee-epf-no" name="epf_no" type="text" placeholder="15" />
                        </div>
                    </div>
                </div>

                <div class="trade-field">
                    <x-forms.form-label for="add-employee-designation">Designation</x-forms.form-label>
                    <div class="trade-control">
                        <x-forms.form-input id="add-employee-designation" name="designation" type="text" placeholder="Senior Portfolio Manager" />
                    </div>
                </div>

                <div class="trade-row2">
                    <div class="trade-field">
                        <x-forms.form-label for="add-employee-bank-account">Bank account</x-forms.form-label>
                        <div class="trade-control">
                            <x-forms.form-input id="add-employee-bank-account" name="bank_account" type="text" placeholder="801234567890" />
                        </div>
                    </div>
                    <div class="trade-field">
                        <x-forms.form-label for="add-employee-status">Status</x-forms.form-label>
                        <div class="trade-control">
                            <x-forms.form-select id="add-employee-status" name="status" :searchable="false">
                                <option value="active" selected>Active</option>
                                <option value="inactive">Deactive</option>
                            </x-forms.form-select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline" onclick="closeModal('add-employee-modal')">Cancel</button>
            <x-forms.form-button type="button" id="add-employee-save">Save Employee</x-forms.form-button>
        </div>
    </div>
</div>
