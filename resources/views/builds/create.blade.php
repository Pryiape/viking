@extends('welcome')

@section('title', 'Créer un Build')

@section('content')
    <div class="container">
        <h1 class="text-center">Créer un Build</h1>
        <form action="{{ route('builds.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Nom du Build</label>
                <input type="text" class="form-control" id="inputUsername" name="name" required autocomplete="username">

            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="inputDescription" name="description" autocomplete="off"></textarea>

            </div>
            <button type="submit" class="btn btn-primary">Créer</button>
        </form>
    </div>
@endsection
