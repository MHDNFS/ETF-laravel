{{-- Add Trade: Blade + x-form-* so Laravel compiles the same field components as ETF / funds forms. Open/close logic stays in public/assets/js/modals.js. --}}
<div class="modal-overlay" id="add-trade-modal">
    <div class="modal">
        <div class="modal-header">
            <span class="modal-title"><i class="fa-solid fa-plus" style="color:var(--accent);margin-right:8px"></i>New Trade</span>
            <button type="button" class="modal-close" onclick="closeModal('add-trade-modal')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <x-form-label for="add-trade-date">Date</x-form-label>
                <x-form-input id="add-trade-date" name="add_trade_date" type="date" value="2026-05-12" />
            </div>
            <div class="form-row">
                <div class="form-group">
                    <x-form-label for="add-trade-fund-select">Fund name</x-form-label>
                    <x-form-select id="add-trade-fund-select" name="add_trade_fund">
                        <option value="Vanguard S&amp;P 500 ETF" data-ticker="VOO" selected>Vanguard S&amp;P 500 ETF</option>
                        <option value="iShares Core MSCI" data-ticker="IEMG">iShares Core MSCI</option>
                        <option value="Custom Growth Fund" data-ticker="CGF-A">Custom Growth Fund</option>
                        <option value="SPDR Gold ETF" data-ticker="GLD">SPDR Gold ETF</option>
                        <option value="Tactical Bond PTF" data-ticker="TBP-2">Tactical Bond PTF</option>
                    </x-form-select>
                </div>
                <div class="form-group">
                    <x-form-label for="add-trade-ticker">Ticker</x-form-label>
                    <x-form-input id="add-trade-ticker" name="add_trade_ticker" type="text" value="VOO" maxlength="12" />
                </div>
            </div>
            <div class="form-group">
                <x-form-label for="add-trade-type">Type</x-form-label>
                <x-form-select id="add-trade-type" name="add_trade_type">
                    <option value="ETF" selected>ETF</option>
                    <option value="PTF">PTF</option>
                </x-form-select>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <x-form-label for="add-trade-units">Units</x-form-label>
                    <x-form-input id="add-trade-units" name="add_trade_units" type="number" value="100" placeholder="100" min="1" step="1" />
                </div>
                <div class="form-group">
                    <x-form-label for="add-trade-price">Price ($)</x-form-label>
                    <x-form-input id="add-trade-price" name="add_trade_price" type="number" value="512.40" placeholder="512.40" min="0" step="0.01" />
                </div>
            </div>
            <div class="form-group">
                <x-form-label for="add-trade-total">Total</x-form-label>
                <x-form-input
                    id="add-trade-total"
                    name="add_trade_total"
                    type="text"
                    value="$51,240"
                    readonly
                    title="Units × Price (preview)"
                    style="background:var(--bg3);color:var(--text2)"
                />
            </div>
            <div class="form-group">
                <x-form-label for="add-trade-status">Status</x-form-label>
                <x-form-select id="add-trade-status" name="add_trade_status">
                    <option value="pending" selected>Pending</option>
                    <option value="settled">Settled</option>
                    <option value="cancelled">Cancelled</option>
                </x-form-select>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline" onclick="closeModal('add-trade-modal')">Cancel</button>
            <x-form-button
                type="button"
                onclick="closeModal('add-trade-modal');showToast('success','Trade Submitted','New trade queued for settlement.')"
            >
                Submit Trade
            </x-form-button>
        </div>
    </div>
</div>
