@extends('welcome')

@section('title', 'Mes Builds')

@section('content')
    <div class="container">
        <h1 class="text-center">Mes Builds</h1>
        <a href="{{ route('builds.create') }}" class="btn btn-success">Créer un Nouveau Build</a>
        <table class="table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($builds as $build)
                    <tr>
                        <td>{{ $build->name }}</td>
                        <td>{{ $build->description }}</td>
                        <td>
                            <a href="{{ route('builds.show', $build) }}" class="btn btn-info">Voir</a>
                            <a href="{{ route('builds.edit', $build) }}" class="btn btn-warning">Modifier</a>
                            <form action="{{ route('builds.destroy', $build) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
