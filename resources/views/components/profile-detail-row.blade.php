{{-- One Account Details row: label left, value right. CSS: .metric-row in public/assets/css/app.css --}}



@props([
    'label',
    'value',
])

<!-- metric-row is a custom class that we defined in app.css -->
<!-- metric-lbl is a custom class that we defined in app.css -->
<!-- metric-val is a custom class that we defined in app.css -->
<!-- label is the label of the row -->
<!-- value is the value of the row -->
<!-- we use the span tag to wrap the label and value so that we can style them separately -->
<div class="metric-row">
    <span class="metric-lbl">{{ $label }}</span>
    <span class="metric-val">{{ $value }}</span>
</div>