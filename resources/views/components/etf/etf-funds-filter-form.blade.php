{{--
  ETF Fund Explorer — filter row.
  Uses x-forms.form-label, x-forms.form-select, x-forms.form-button.
--}}
<div class="form-row etf-funds-filter-row">
    <div class="form-group">
        <x-forms.form-label for="etf-fund-filter-asset">Asset Class</x-forms.form-label>
        <x-forms.form-select id="etf-fund-filter-asset" name="asset_class">
            <option value="All Classes" selected>All Classes</option>
            <option value="Equity">Equity</option>
            <option value="Fixed Income">Fixed Income</option>
            <option value="Commodity">Commodity</option>
            <option value="Real Estate">Real Estate</option>
        </x-forms.form-select>
    </div>
    <div class="form-group">
        <x-forms.form-label for="etf-fund-filter-region">Region</x-forms.form-label>
        <x-forms.form-select id="etf-fund-filter-region" name="region">
            <option value="Global" selected>Global</option>
            <option value="North America">North America</option>
            <option value="Europe">Europe</option>
            <option value="Asia Pacific">Asia Pacific</option>
            <option value="Emerging Markets">Emerging Markets</option>
        </x-forms.form-select>
    </div>
    <div class="form-group">
        <x-forms.form-label for="etf-fund-filter-ter">TER Range</x-forms.form-label>
        <x-forms.form-select id="etf-fund-filter-ter" name="ter_range">
            <option value="Any" selected>Any</option>
            <option value="< 0.10%">&lt; 0.10%</option>
            <option value="0.10% – 0.50%">0.10% – 0.50%</option>
            <option value="> 0.50%">&gt; 0.50%</option>
        </x-forms.form-select>
    </div>
    <div class="form-group">
        <x-forms.form-label for="etf-fund-filter-sort">Sort By</x-forms.form-label>
        <x-forms.form-select id="etf-fund-filter-sort" name="sort_by">
            <option value="AUM (High–Low)" selected>AUM (High–Low)</option>
            <option value="Return 1Y">Return 1Y</option>
            <option value="TER (Low–High)">TER (Low–High)</option>
        </x-forms.form-select>
    </div>
    <div class="etf-funds-filter-apply">
        <x-forms.form-button type="button" icon="fa-filter" :full-width="true">
            Apply
        </x-forms.form-button>
    </div>
</div>

