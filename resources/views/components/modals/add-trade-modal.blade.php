{{--
  New Trade — layout: .trade-form / .trade-field / .trade-control (same label↔control rhythm for input & select).
  Columns match resources/js/app.js → Recent Transactions: Date, Fund / Ticker, Type, Units, Price, Total, Status.
  public/assets/js/modals.js: add-trade-fund-select, add-trade-ticker, add-trade-units, add-trade-price, add-trade-total.
--}}
<div class="modal-overlay" id="add-trade-modal">
    <div class="modal">
        <div class="modal-header">
            <span class="modal-title" id="add-trade-modal-title">
                <i class="fa-solid fa-plus" style="color:var(--accent);margin-right:8px"></i>New Trade
            </span>
            <button type="button" class="modal-close" onclick="closeModal('add-trade-modal')" aria-label="Close">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="trade-form" role="group" aria-labelledby="add-trade-modal-title">
                <div class="trade-field"><x-forms.form-label for="add-trade-date">Date</x-forms.form-label><div class="trade-control"><x-forms.form-input id="add-trade-date" name="add_trade_date" type="date" value="2026-05-02" /></div></div>

                <div class="trade-field"><x-forms.form-label for="add-trade-fund-select">Fund</x-forms.form-label><div class="trade-control"><x-forms.form-select id="add-trade-fund-select" name="add_trade_fund">
                        <option value="Vanguard S&amp;P 500" data-ticker="VOO" selected>Vanguard S&amp;P 500</option>
                        <option value="iShares Core MSCI" data-ticker="IEMG">iShares Core MSCI</option>
                        <option value="Custom Growth Fund" data-ticker="CGF-A">Custom Growth Fund</option>
                        <option value="SPDR Gold ETF" data-ticker="GLD">SPDR Gold ETF</option>
                        <option value="Tactical Bond PTF" data-ticker="TBP-2">Tactical Bond PTF</option>
                    </x-forms.form-select></div></div>

                <div class="trade-field"><x-forms.form-label for="add-trade-ticker">Ticker</x-forms.form-label><div class="trade-control"><x-forms.form-input id="add-trade-ticker" name="add_trade_ticker" type="text" value="VOO" maxlength="12" /></div></div>

                <div class="trade-field"><x-forms.form-label for="add-trade-type">Type</x-forms.form-label><div class="trade-control"><x-forms.form-select id="add-trade-type" name="add_trade_type">
                        <option value="ETF" selected>ETF</option>
                        <option value="PTF">PTF</option>
                    </x-forms.form-select></div></div>

                <div class="trade-row2">
                    <div class="trade-field"><x-forms.form-label for="add-trade-units">Units</x-forms.form-label><div class="trade-control"><x-forms.form-input id="add-trade-units" name="add_trade_units" type="number" value="120" min="1" step="1" /></div></div>
                    <div class="trade-field"><x-forms.form-label for="add-trade-price">Price</x-forms.form-label><div class="trade-control"><x-forms.form-input id="add-trade-price" name="add_trade_price" type="number" value="512.40" min="0" step="0.01" /></div></div>
                </div>

                <div class="trade-field"><x-forms.form-label for="add-trade-total">Total</x-forms.form-label><div class="trade-control"><x-forms.form-input id="add-trade-total" name="add_trade_total" type="text" value="$61,488" readonly title="Units × Price (preview)" style="background:var(--bg3);color:var(--text2)" /></div></div>

                <div class="trade-field"><x-forms.form-label for="add-trade-status">Status</x-forms.form-label><div class="trade-control"><x-forms.form-select id="add-trade-status" name="add_trade_status">
                        <option value="pending">Pending</option>
                        <option value="settled" selected>Settled</option>
                        <option value="cancelled">Cancelled</option>
                    </x-forms.form-select></div></div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline" onclick="closeModal('add-trade-modal')">Cancel</button>
            <x-forms.form-button
                type="button"
                onclick="closeModal('add-trade-modal');showToast('success','Trade Submitted','New trade queued for settlement.')"
            >
                Submit Trade
            </x-forms.form-button>
        </div>
    </div>
</div>
