@extends('gentelella.layouts.app')


@section('content')
  <div id="home-container"></div>
@endsection

@push('scripts')
  <script src="{{ asset('react/main.js') }}"></script>
@endpush