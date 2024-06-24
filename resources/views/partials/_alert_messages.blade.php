
{{-- Success Alert --}}
@if($message = Session::get('success'))
  @push('scripts')
    <script>
      var message = '{{ $message }}';
      var type = 'success';
      displayMessage(message, type);
    </script>
  @endpush
@endif

{{-- Warning Alert --}}
@if($message = Session::get('warning'))
@push('scripts')
    <script>
      var message = '{{ $message }}';
      var type = 'error';
      displayMessage(message, type);
    </script>
  @endpush
@endif

@if (Session::get('error') || Session::get('verify'))
  @push('scripts')
    <script>
      var message = '{{ Session::get("error") ? Session::get("error") : Session::get("verify") }}';
      var type = 'error';
      displayMessage(message, type);
    </script>
  @endpush
@endif
