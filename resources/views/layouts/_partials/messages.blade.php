@if($message =Session::get('success'))
<div style="padding:10px; background-color:green; color:white;">
    <p>{{ $message }}</p>
</div>
@endif
@if($message =Session::get('danger'))
<div style="padding:10px; background-color:red; color:white;">
    <p>{{ $message }}</p>
</div>
@endif
