{{--
  Settings — Preferences card body.
  Primitives: x-forms.form-toggle-row, x-forms.form-label, x-forms.form-select, x-forms.form-button (same family as report-generate-form).
--}}

<div class="form-toggle-stack">
    <x-forms.form-toggle-row
        title="Email Notifications"
        description="Receive trade alerts via email"
        name="notify_email"
        :checked="true"
    />
    <x-forms.form-toggle-row
        title="Rebalance Alerts"
        description="Alert when drift exceeds 5%"
        name="notify_rebalance"
        :checked="true"
    />
    <x-forms.form-toggle-row
        title="Two-Factor Auth"
        description="TOTP authenticator app"
        name="auth_totp"
        :checked="false"
    />
    <x-forms.form-toggle-row
        title="Dark theme"
        description="Turn off for a light interface"
        name="ui_dark_mode"
        id="ui_dark_mode"
        :checked="true"
    />
</div>

<div class="form-group">
    <x-forms.form-label for="settings-currency">Default Currency</x-forms.form-label>
    <x-forms.form-select id="settings-currency" name="default_currency">
        <option value="USD" selected>USD ($)</option>
        <option value="EUR">EUR (€)</option>
        <option value="GBP">GBP (£)</option>
        <option value="LKR">LKR (₨)</option>
    </x-forms.form-select>
</div>

<div class="form-group">
    <x-forms.form-label for="settings-date-format">Date Format</x-forms.form-label>
    <x-forms.form-select id="settings-date-format" name="date_format">
        <option value="Y-m-d" selected>YYYY-MM-DD</option>
        <option value="d/m/Y">DD/MM/YYYY</option>
        <option value="m/d/Y">MM/DD/YYYY</option>
    </x-forms.form-select>
</div>

<x-forms.form-button type="button" onclick="showToast('success','Settings Saved','Your preferences have been updated.')">
    Save Preferences
</x-forms.form-button>
