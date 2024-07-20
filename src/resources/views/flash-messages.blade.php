@session('success')
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ $value }}
</div>
@endsession

@session('error')
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ $value }}
</div>
@endsession

@session('warning')
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    {{ $value }}
</div>
@endsession

@session('info')
<div class="alert alert-info alert-dismissible fade show" role="alert">
    {{ $value }}
</div>
@endsession

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Please check the form below for errors</strong>
    </div>
@endif
