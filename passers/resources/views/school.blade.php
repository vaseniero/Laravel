@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h3>List of Schools and Number of Passers</h3>
                    Click <a href="{{ url('/home') }}">here</a> for Home
                </div>
            </div>
        </div>
    </div>
</div>
<div id="app" style="padding-top:25px;">
	<div class="container">
        {{-- <example-component></example-component> --}}

		<data-table-school
			fetch-url="{{ route('schools.table') }}"
            :columns="['school', 'passers']"></data-table-school>
    </div>
</div>
@endsection
