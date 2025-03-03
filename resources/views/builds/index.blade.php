@extends('welcome')

@section('title', 'Mes Builds')

@section('content')
    <div class="container">
        <h1 class="text-center">Mes Builds</h1>
        <a href="{{ route('builds.create') }}" class="btn btn-primary mb-3">Créer un Build</a>
        <div class="row">
            @foreach ($builds as $build)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $build->name }}</h5>
                            <p class="card-text">{{ $build->description }}</p>
                            <a href="{{ route('builds.show', $build) }}" class="btn btn-info">Voir</a>
                            <a href="{{ route('builds.edit', $build) }}" class="btn btn-warning">Modifier</a>
                            <form action="{{ route('builds.destroy', $build) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Supprimer</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
