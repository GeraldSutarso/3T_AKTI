@extends('layout.main')

@section('content')
<div class="container mt-5">
    <h2>Enter Verification Code</h2>
    <form action="{{ route('verify.code') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="code" class="form-label">Verification Code</label>
            <input type="text" class="form-control" name="code" required>
        </div>
        <button type="submit" class="btn btn-success">Verify</button>
    </form>
</div>
@endsection
