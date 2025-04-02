@extends('welcome')

@section('title', 'Mes Builds')

@section('content')
<div class="container">
    <h1 class="text-center mb-4">Mes Builds</h1>

    <a href="{{ route('builds.create') }}" class="btn btn-success mb-4">Créer un Nouveau Build</a>

    <div class="row">
        @forelse($myBuilds as $build)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $build->sujet }}</h5>
                        <p class="card-text">{{ $build->description }}</p>

                        <div class="mb-2">
                            @if($build->is_public)
                                <span class="badge bg-success">Public</span>
                            @else
                                <span class="badge bg-secondary">Privé</span>
                            @endif
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('builds.edit', $build) }}" class="btn btn-sm btn-primary">Modifier</a>

                            <form action="{{ route('builds.destroy', $build) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce build ?')">Supprimer</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">Aucun build pour l’instant.</p>
        @endforelse
    </div>
</div>
@endsection
