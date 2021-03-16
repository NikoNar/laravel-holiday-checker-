@extends('layouts.app')
@section('styles') <link rel="stylesheet" href="{{URL::asset('assets/css/styles.css')}}"> @endsection
@section('content')
  <div class="wrapper fadeInDown">
    <div id="formContent">
      <!-- Tabs Titles -->

      <!-- Icon -->
      <div class="fadeIn first">
        
      </div>

      <form class="container p-5" id="form" action="{{route('compare')}}" method="POST">
        @if(isset($current_datetime))
          {{$current_datetime}}
        @endif
          @csrf
        <input type="date" id="date_input" class="fadeIn second form-control" name="date">
        <input type="submit" id="check_button" class="fadeIn fourth mt-3" value="Check">
      </form>

    </div>
  </div>

  <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-autohide='false' style="position: absolute;top:20px; right:20px;min-width: 250px;">
    <div class="toast-header text-white">
      <strong class="mr-auto">Response</strong>
      <small class="text-white">just now</small>
      <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
        <span aria-hidden="true" class="text-white">&times;</span>
      </button>
    </div>
    <div class="toast-body">
      
    </div>
  </div>      
@endsection

@section('scripts')
  <script src="{{asset('assets/js/scripts.js')}}"></script>
@endsection