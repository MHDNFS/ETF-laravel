{{--
  Add Customer — same grid rhythm as New Trade (.trade-form / .trade-field / .trade-control).
  Column fields align with resources/js/app.js → initCustomerManagementDataTable.
--}}
<div class="modal-overlay" id="add-customer-modal">
    <div class="modal" style="max-width: 560px">
        <div class="modal-header">
            <span class="modal-title" id="add-customer-modal-title">
                <i class="fa-solid fa-user-plus" style="color:var(--accent);margin-right:8px"></i>Add Customer
            </span>
            <button type="button" class="modal-close" onclick="closeModal('add-customer-modal')" aria-label="Close">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="trade-form" role="group" aria-labelledby="add-customer-modal-title">
                <div class="trade-field">
                    <x-form-label for="add-customer-name">Name</x-form-label>
                    <div class="trade-control">
                        <x-form-input id="add-customer-name" name="add_customer_name" type="text" placeholder="Full name" />
                    </div>
                </div>

                <div class="trade-row2">
                    <div class="trade-field">
                        <x-form-label for="add-customer-email">Email</x-form-label>
                        <div class="trade-control">
                            <x-form-input id="add-customer-email" name="add_customer_email" type="email" placeholder="name@example.com" />
                        </div>
                    </div>
                    <div class="trade-field">
                        <x-form-label for="add-customer-phone">Phone</x-form-label>
                        <div class="trade-control">
                            <x-form-input id="add-customer-phone" name="add_customer_phone" type="text" placeholder="0770000000" />
                        </div>
                    </div>
                </div>

                <div class="trade-field">
                    <x-form-label for="add-customer-address">Address</x-form-label>
                    <div class="trade-control">
                        <x-form-input id="add-customer-address" name="add_customer_address" type="text" placeholder="Street, city" />
                    </div>
                </div>

                <div class="trade-row2">
                    <div class="trade-field">
                        <x-form-label for="add-customer-balance">Outstanding balance</x-form-label>
                        <div class="trade-control">
                            <x-form-input id="add-customer-balance" name="add_customer_balance" type="text" placeholder="Rs. 0" />
                        </div>
                    </div>
                    <div class="trade-field">
                        <x-form-label for="add-customer-last-transaction">Last transaction</x-form-label>
                        <div class="trade-control">
                            <x-form-input id="add-customer-last-transaction" name="add_customer_last_transaction" type="text" placeholder="N/A or date" />
                        </div>
                    </div>
                </div>

                <div class="trade-field">
                    <x-form-label for="add-customer-vehicles">Vehicles</x-form-label>
                    <div class="trade-control">
                        <x-form-select id="add-customer-vehicles" name="add_customer_vehicles">
                            <option value="No Tracking" selected>No Tracking</option>
                            <option value="1 Vehicle(s)">1 Vehicle(s)</option>
                            <option value="2 Vehicle(s)">2 Vehicle(s)</option>
                            <option value="3+ Vehicle(s)">3+ Vehicle(s)</option>
                        </x-form-select>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline" onclick="closeModal('add-customer-modal')">Cancel</button>
            <x-form-button type="button" id="add-customer-save">Save customer</x-form-button>
        </div>
    </div>
</div>
