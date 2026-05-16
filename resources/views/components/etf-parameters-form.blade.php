<div class="form-group">
    <x-form-label for="etf-fund-select">Fund Selection</x-form-label>
    <x-form-select id="etf-fund-select">
        <option value="Vanguard S&P 500 ETF (VOO)" selected>Vanguard S&amp;P 500 ETF (VOO)</option>
        <option value="iShares MSCI Emerging (IEMG)">iShares MSCI Emerging (IEMG)</option>
        <option value="SPDR Gold Shares (GLD)">SPDR Gold Shares (GLD)</option>
        <option value="Invesco QQQ Trust (QQQ)">Invesco QQQ Trust (QQQ)</option>
        <option value="iShares Core US Aggregate (AGG)">iShares Core US Aggregate (AGG)</option>
    </x-form-select>
</div>

<div class="form-row">
    <div class="form-group">
        <x-form-label for="etf-amount">Investment Amount ($)</x-form-label>
        <x-form-input id="etf-amount" type="number" value="50000" icon="fa-dollar-sign" />
    </div>
    <div class="form-group">
        <x-form-label for="etf-units">Number of Units</x-form-label>
        <x-form-input id="etf-units" type="number" value="100" />
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <x-form-label for="etf-nav">Current NAV ($)</x-form-label>
        <x-form-input id="etf-nav" type="number" value="512.40" />
    </div>
    <div class="form-group">
        <x-form-label for="etf-price">Market Price ($)</x-form-label>
        <x-form-input id="etf-price" type="number" value="513.10" />
    </div>
</div>

<div class="form-row three">
    <div class="form-group">
        <x-form-label for="etf-ter">TER (%)</x-form-label>
        <x-form-input id="etf-ter" type="number" value="0.03" step="0.01" />
    </div>
    <div class="form-group">
        <x-form-label for="etf-bench">Benchmark Return (%)</x-form-label>
        <x-form-input id="etf-bench" type="number" value="12.5" />
    </div>
    <div class="form-group">
        <x-form-label for="etf-years">Holding Period (yrs)</x-form-label>
        <x-form-input id="etf-years" type="number" value="5" />
    </div>
</div>

<div class="form-group">
    <x-form-label for="etf-dividend-reinvest">Dividend Reinvestment</x-form-label>
    <x-form-select id="etf-dividend-reinvest" name="dividend_reinvestment">
        <option value="Reinvest Dividends (DRIP)" selected>Reinvest Dividends (DRIP)</option>
        <option value="Cash Payout">Cash Payout</option>
        <option value="No Dividends">No Dividends</option>
    </x-form-select>
</div>

<x-form-button icon="fa-calculator" full-width onclick="calcETF()">
    Calculate ETF Metrics
</x-form-button>
