@extends('layouts.layout')
@section('title','Settings')
@section('header_title', 'Settings')
@section('content')

    <div class="content page-animate">

      <x-page-header title="Settings" subtitle="Configure system preferences, notifications, and integrations" />
      <div class="card">
        <div class="card-header"><span class="card-title">Preferences</span></div>
        <div class="card-body">
          <x-settings-preferences-form />
        </div>
      </div>
    </div>

@endsection
