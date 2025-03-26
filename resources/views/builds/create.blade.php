@extends('welcome')

@section('title', 'Créer un Build')

@section('content')
    <div class="container">
        <h1 class="text-center">Créer un Build</h1>
        <form action="{{ route('builds.store') }}" method="POST">
            @csrf
            <div class="form-group">
                  <label for="sujet">Sujet *</label>
                  <input type="text" name="sujet" id="sujet" class="form-control" required>
            </div>

        <div class="form-group">
              <label for="description">Description *</label>
              <textarea name="description" id="description" class="form-control" rows="5" required></textarea>
        </div>
        <div class="form-check mb-3">
             <input class="form-check-input" type="checkbox" name="is_public" id="is_public" value="1">
              <label class="form-check-label" for="is_public">Rendre ce build public</label>
        </div>

            <button type="submit" class="btn btn-primary">Créer</button>
        </form>
    </div>
@endsection
