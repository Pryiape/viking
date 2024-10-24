@extends('welcome')

@section('title', 'register')

@section('content')
    <div class="container">
        <h1 class="text-center text-muted mb-3 mt-5">Création de compte</h1>
        <p class="text-center text-muted mb-5">Créez un compte si vous n'en avez pas.</p>

        <form action="{{ route('register') }}" method="POST" id="form-register">
            @csrf
            <div class="col-md-6 mx-auto">
                <label for="inputUsername" class="form-label">Nom d'utilisateur</label>
                <input type="text" class="form-control" id="inputUsername" name="username" required>
            </div>
            <div class="col-md-6 mx-auto mt-3">
                <label for="inputEmail4" class="form-label">Email</label>
                <input type="email" class="form-control" id="inputEmail4" name="email" required autocomplete="email" url-existEmail="{{route('app_existEmail'}}"token="{{csrf_token()}}">
            </div>
            <div class="col-md-6 mx-auto mt-3">
                <label for="inputPassword4" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="inputPassword4" name="password" required>
            </div>
            <div class="col-md-6 mx-auto mt-3">
                <label for="inputPasswordConfirmation" class="form-label">Confirmez le mot de passe</label>
                <input type="password" class="form-control" id="inputPasswordConfirmation" name="password_confirmation" required>
            </div>
            <div class="col-md-6 mx-auto mt-3">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                    <label class="form-check-label" for="terms">
                        J'accepte les <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">termes d'utilisation</a>
                    </label>
                </div>
            </div>
            <div class="col-md-6 mx-auto mt-3">
                <button type="submit" class="btn btn-primary">Créer un compte</button>
            </div>
            <div class="col-md-6 mx-auto mt-3 text-center">
                <p class="text-muted">J'ai déjà un compte ? <a href="{{ route('login') }}">Se connecter</a></p>
            </div>
        </form>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Termes d'utilisation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Les termes d'utilisation incluent les règles et les politiques que vous devez suivre pour utiliser ce site.</p>
                    <!-- Ajoutez ici le texte complet des termes d'utilisation -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });
    </script>
@endsection
