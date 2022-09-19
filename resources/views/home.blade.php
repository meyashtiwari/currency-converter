@extends('layouts.app')

@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-6">
        <currencies></currencies>
      </div>
      <div class="col-6">
        <HistoryChart></HistoryChart>
        <MinMaxAverage class="mt-2"></MinMaxAverage>
      </div>
    </div>
  </div>
@endsection
