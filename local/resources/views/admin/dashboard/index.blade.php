@extends('admin.layout.app')

@section('content')

    <section id="wrapper" class="login-register login-sidebar" style="background-image:url({{asset('admin-panel/images/background/dashboard.jpg')}});margin-top: -30px;margin-right: -30px"></section>
    @if(admin()->can('dashboard'))

    @endif
@endsection
