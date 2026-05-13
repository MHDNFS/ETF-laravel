{{--
  Top bar page label (next to hamburger). Title comes from each page’s @section('header_title', '…').
  Default is handled in the layout when the section is omitted.
--}}
@props([
    'title' => 'Dashboard',
])

<div class="header-breadcrumb">{{ $title }}</div>
