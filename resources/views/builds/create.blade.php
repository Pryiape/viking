@extends('welcome')

@section('title', 'Créer un Build')

@section('content')
    <div class="container">
        <h1 class="text-center">Créer un Build</h1>
        <form action="{{ route('builds.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Nom du Build</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" rows="4"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
    </div>
@endsection
