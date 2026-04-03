@extends('layouts.app')
@section('content')
<div>
    <div>
        <div>
            <div>Login Aplikasi</div>
            <div class="card-body">
                <form action="{{ url('login') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="">Email</label>
                        <input type="email" name="email" id="" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="">Password</label>
                        <input type="password" name="password" id="" class="form-control" required>
                    </div>
                    <button>Masuk</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
