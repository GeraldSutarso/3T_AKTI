@extends('layout.main')

@section('content')
<div class="container mt-4">
  <div id="app">
    <!-- Row for Charts (Spans full width) -->
    <div class="row mb-4">
      <div class="col-12">
        <chart-component></chart-component>
      </div>
    </div>

    <!-- Row for 3T Forms (Left) and Notifications (Right) -->
    <div class="row">
      <div class="col-md-6">
        <three-t-navigation></three-t-navigation>
      </div>
      <div class="col-md-6">
        <notification-widget></notification-widget>
      </div>
    </div>
  </div>
</div>
@endsection
