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

                    You are logged in!
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="container">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name of Examinee</th>
                            <th>Campus Eligibility</th>
                            <th>School</th>
                            <th>Division</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($passers as $examinee)
                        <tr>
                            <td>{{ $examinee->id }}</td>
                            <td>{{ $examinee->name_of_examinee }}</td>
                            <td>{{ $examinee->campus_eligibility }}</td>
                            <td>{{ $examinee->school }}</td>
                            <td>{{ $examinee->division }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $passers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
