{{-- Edit Employee — same fields as add; PUT /api/employees/{id} (resources/js/app.js). --}}
<div class="modal-overlay" id="edit-employee-modal">
    <div class="modal modal--employee">
        <div class="modal-header">
            <span class="modal-title" id="edit-employee-modal-title">
                <i class="fa-solid fa-pen-to-square" style="color:var(--accent);margin-right:8px"></i>Edit Employee
            </span>
            <button type="button" class="modal-close" onclick="closeModal('edit-employee-modal')" aria-label="Close">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="modal-body">
            <div id="edit-employee-form-error" class="login-alert login-alert--error" role="alert" hidden></div>

            <x-forms.form-input id="edit-employee-id" name="edit_employee_id" type="hidden" value="" />

            <div class="employee-form trade-form" role="group" aria-labelledby="edit-employee-modal-title">
                <div class="trade-row2">
                    <div class="trade-field">
                        <x-forms.form-label for="edit-employee-company">Company</x-forms.form-label>
                        <div class="trade-control">
                            <x-forms.form-select id="edit-employee-company" name="company" :searchable="false">
                                <option value="ABS">ABS</option>
                            </x-forms.form-select>
                        </div>
                    </div>
                    <div class="trade-field">
                        <x-forms.form-label for="edit-employee-branch">Branch</x-forms.form-label>
                        <div class="trade-control">
                            <x-forms.form-select id="edit-employee-branch" name="branch" :searchable="false">
                                <option value="ABS">ABS</option>
                                <option value="Colombo">Colombo</option>
                                <option value="Kandy">Kandy</option>
                                <option value="Galle">Galle</option>
                            </x-forms.form-select>
                        </div>
                    </div>
                </div>

                <div class="trade-field">
                    <x-forms.form-label for="edit-employee-name">Employee name</x-forms.form-label>
                    <div class="trade-control">
                        <x-forms.form-input id="edit-employee-name" name="name" type="text" placeholder="Full name" required />
                    </div>
                </div>

                <div class="trade-row2">
                    <div class="trade-field">
                        <x-forms.form-label for="edit-employee-nic">NIC</x-forms.form-label>
                        <div class="trade-control">
                            <x-forms.form-input id="edit-employee-nic" name="nic" type="text" placeholder="541682864V" />
                        </div>
                    </div>
                    <div class="trade-field">
                        <x-forms.form-label for="edit-employee-epf-no">EPF No</x-forms.form-label>
                        <div class="trade-control">
                            <x-forms.form-input id="edit-employee-epf-no" name="epf_no" type="text" placeholder="15" />
                        </div>
                    </div>
                </div>

                <div class="trade-field">
                    <x-forms.form-label for="edit-employee-designation">Designation</x-forms.form-label>
                    <div class="trade-control">
                        <x-forms.form-input id="edit-employee-designation" name="designation" type="text" placeholder="Senior Portfolio Manager" />
                    </div>
                </div>

                <div class="trade-row2">
                    <div class="trade-field">
                        <x-forms.form-label for="edit-employee-bank-account">Bank account</x-forms.form-label>
                        <div class="trade-control">
                            <x-forms.form-input id="edit-employee-bank-account" name="bank_account" type="text" placeholder="801234567890" />
                        </div>
                    </div>
                    <div class="trade-field">
                        <x-forms.form-label for="edit-employee-status">Status</x-forms.form-label>
                        <div class="trade-control">
                            <x-forms.form-select id="edit-employee-status" name="status" :searchable="false">
                                <option value="active">Active</option>
                                <option value="inactive">Deactive</option>
                            </x-forms.form-select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline" onclick="closeModal('edit-employee-modal')">Cancel</button>
            <x-forms.form-button type="button" id="edit-employee-save">Update Employee</x-forms.form-button>
        </div>
    </div>
</div>
