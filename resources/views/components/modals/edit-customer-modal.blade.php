{{--
  Edit Customer — same layout as add-customer-modal; populated from DataTable row (resources/js/app.js).
--}}
<div class="modal-overlay" id="edit-customer-modal">
    <div class="modal" style="max-width: 560px">
        <div class="modal-header">
            <span class="modal-title" id="edit-customer-modal-title">
                <i class="fa-solid fa-pen-to-square" style="color:var(--accent);margin-right:8px"></i>Edit Customer
            </span>
            <button type="button" class="modal-close" onclick="closeModal('edit-customer-modal')" aria-label="Close">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="modal-body">
            <x-forms.form-input id="edit-customer-id" name="edit_customer_id" type="hidden" value="" />

            <div class="trade-form" role="group" aria-labelledby="edit-customer-modal-title">
                <div class="trade-field">
                    <x-forms.form-label for="edit-customer-name">Name</x-forms.form-label>
                    <div class="trade-control">
                        <x-forms.form-input id="edit-customer-name" name="edit_customer_name" type="text" placeholder="Full name" />
                    </div>
                </div>

                <div class="trade-row2">
                    <div class="trade-field">
                        <x-forms.form-label for="edit-customer-email">Email</x-forms.form-label>
                        <div class="trade-control">
                            <x-forms.form-input id="edit-customer-email" name="edit_customer_email" type="email" placeholder="name@example.com" />
                        </div>
                    </div>
                    <div class="trade-field">
                        <x-forms.form-label for="edit-customer-phone">Phone</x-forms.form-label>
                        <div class="trade-control">
                            <x-forms.form-input id="edit-customer-phone" name="edit_customer_phone" type="text" placeholder="0770000000" />
                        </div>
                    </div>
                </div>

                <div class="trade-field">
                    <x-forms.form-label for="edit-customer-address">Address</x-forms.form-label>
                    <div class="trade-control">
                        <x-forms.form-input id="edit-customer-address" name="edit_customer_address" type="text" placeholder="Street, city" />
                    </div>
                </div>

                <div class="trade-row2">
                    <div class="trade-field">
                        <x-forms.form-label for="edit-customer-balance">Outstanding balance</x-forms.form-label>
                        <div class="trade-control">
                            <x-forms.form-input id="edit-customer-balance" name="edit_customer_balance" type="text" placeholder="Rs. 0" />
                        </div>
                    </div>
                    <div class="trade-field">
                        <x-forms.form-label for="edit-customer-last-transaction">Last transaction</x-forms.form-label>
                        <div class="trade-control">
                            <x-forms.form-input id="edit-customer-last-transaction" name="edit_customer_last_transaction" type="text" placeholder="N/A or date" />
                        </div>
                    </div>
                </div>

                <div class="trade-field">
                    <x-forms.form-label for="edit-customer-vehicles">Vehicles</x-forms.form-label>
                    <div class="trade-control">
                        <x-forms.form-select id="edit-customer-vehicles" name="edit_customer_vehicles">
                            <option value="No Tracking">No Tracking</option>
                            <option value="1 Vehicle(s)">1 Vehicle(s)</option>
                            <option value="2 Vehicle(s)">2 Vehicle(s)</option>
                            <option value="3+ Vehicle(s)">3+ Vehicle(s)</option>
                        </x-forms.form-select>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline" onclick="closeModal('edit-customer-modal')">Cancel</button>
            <x-forms.form-button type="button" id="edit-customer-save">Update customer</x-forms.form-button>
        </div>
    </div>
</div>
