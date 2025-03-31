@extends('welcome')

@section('title', 'Modifier le Build')

@section('content')
<div class="container">
    <h1>Modifier le Build</h1>

    <form action="{{ route('builds.update', $build) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="sujet" class="form-label">Sujet *</label>
            <input type="text" name="sujet" id="sujet" class="form-control" value="{{ old('sujet', $build->sujet) }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description *</label>
            <textarea name="description" id="description" class="form-control" rows="5" required>{{ old('description', $build->description) }}</textarea>
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="is_public" id="is_public" value="1"
                {{ $build->is_public ? 'checked' : '' }}>
            <label class="form-check-label" for="is_public">Rendre ce build public</label>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
</div>
@endsection
