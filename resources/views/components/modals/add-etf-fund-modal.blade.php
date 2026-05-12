{{-- Fund List row "Add": fields align with DataTable columns (resources/js/app.js → initEtfFundsDataTable). Pre-filled via .js-open-add-etf-fund in public/assets/js/modals.js. --}}
<div class="modal-overlay" id="add-etf-fund-modal">
    <div class="modal" style="max-width: 560px">
        <div class="modal-header">
            <span class="modal-title"><i class="fa-solid fa-layer-group" style="color:var(--accent);margin-right:8px"></i>Add fund</span>
            <button type="button" class="modal-close" onclick="closeModal('add-etf-fund-modal')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <x-form-label for="add-etf-fund-name">Fund Name</x-form-label>
                <x-form-input id="add-etf-fund-name" name="etf_fund_name" type="text" value="" placeholder="e.g. Vanguard S&amp;P 500 ETF" />
            </div>
            <div class="form-row">
                <div class="form-group">
                    <x-form-label for="add-etf-fund-ticker">Ticker</x-form-label>
                    <x-form-input id="add-etf-fund-ticker" name="etf_fund_ticker" type="text" value="" maxlength="12" placeholder="VOO" />
                </div>
                <div class="form-group">
                    <x-form-label for="add-etf-fund-nav">NAV</x-form-label>
                    <x-form-input id="add-etf-fund-nav" name="etf_fund_nav" type="number" value="" placeholder="512.40" min="0" step="0.01" />
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <x-form-label for="add-etf-fund-ret1">1Y Return</x-form-label>
                    <x-form-input id="add-etf-fund-ret1" name="etf_fund_ret1" type="number" value="" placeholder="14.2" step="0.1" />
                </div>
                <div class="form-group">
                    <x-form-label for="add-etf-fund-cagr">3Y CAGR</x-form-label>
                    <x-form-input id="add-etf-fund-cagr" name="etf_fund_cagr" type="number" value="" placeholder="12.8" step="0.1" />
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <x-form-label for="add-etf-fund-aum">AUM</x-form-label>
                    <x-form-input id="add-etf-fund-aum" name="etf_fund_aum" type="text" value="" placeholder="42.1B" />
                </div>
                <div class="form-group">
                    <x-form-label for="add-etf-fund-ter">TER</x-form-label>
                    <x-form-input id="add-etf-fund-ter" name="etf_fund_ter" type="number" value="" placeholder="0.03" min="0" step="0.01" />
                </div>
            </div>
            <div class="form-group">
                <x-form-label for="add-etf-fund-stars">Rating</x-form-label>
                <x-form-select id="add-etf-fund-stars" name="etf_fund_stars">
                    <option value="5">5 stars</option>
                    <option value="4" selected>4 stars</option>
                    <option value="3">3 stars</option>
                    <option value="2">2 stars</option>
                    <option value="1">1 star</option>
                </x-form-select>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline" onclick="closeModal('add-etf-fund-modal')">Cancel</button>
            <x-form-button
                type="button"
                onclick="closeModal('add-etf-fund-modal');showToast('success','Fund saved','Row values captured (demo only).')"
            >
                Save fund
            </x-form-button>
        </div>
    </div>
</div>
