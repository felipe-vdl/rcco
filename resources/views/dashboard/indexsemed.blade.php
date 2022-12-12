@extends('gentelella.layouts.app')

@section('content')
<iframe
src="{{$iframeUrl}}"
frameborder="0"
width="100%"
height="1500"
allowtransparency
>
</iframe>
@endsection

@push('scripts')
	
@endpush
