@extends('layouts.app')

@section('title', 'register')

@section('content')
    <div class="container">
        <h1 class="text-center text-muted mb-3 mt-5">Création de compte</h1>
        <p class="text-center text-muted mb-5">Créez un compte si vous n'en avez pas.</p>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('register') }}" method="POST" id="form-register" novalidate>
            @csrf

            <!-- Nom d'utilisateur -->
            <div class="col-md-6 mx-auto">
                <label for="inputUsername" class="form-label">Nom d'utilisateur</label>
                <input type="text" class="form-control @error('Username') is-invalid @enderror" id="inputUsername" name="Username" required value="{{ old('Username') }}">
                @error('Username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email -->
            <div class="col-md-6 mx-auto mt-3">
                <label for="inputEmail4" class="form-label">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="inputEmail4" name="email" required autocomplete="email" value="{{ old('email') }}"
                data-url-exist-email="{{ route('app_existEmail') }}" data-token="{{ csrf_token() }}">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Mot de passe -->
            <div class="col-md-6 mx-auto mt-3">
                <label for="inputPassword4" class="form-label">Mot de passe</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="inputPassword4" name="password" required autocomplete="new-password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirmation du mot de passe -->
            <div class="col-md-6 mx-auto mt-3">
                <label for="inputPasswordConfirmation" class="form-label">Confirmez le mot de passe</label>
                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="inputPasswordConfirmation" name="password_confirmation" required autocomplete="new-password">
                @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Checkbox des conditions -->
            <div class="col-md-6 mx-auto mt-3">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                    <label class="form-check-label" for="terms">
                        J'accepte les <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">termes d'utilisation</a>
                    </label>
                </div>
            </div>

            <!-- Bouton d'inscription -->
            <div class="col-md-6 mx-auto mt-3">
                    <button type="submit" class="btn btn-primary w-100" onclick="console.log('Form submitted')">Créer un compte</button>
            </div>

            <!-- Lien vers la connexion -->
            <div class="col-md-6 mx-auto mt-3 text-center">
                <p class="text-muted">J'ai déjà un compte ? <a href="{{ route('login') }}">Se connecter</a></p>
            </div>
        </form>
    </div>

    <!-- Modal des termes d'utilisation -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Termes d'utilisation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p>Les termes d'utilisation incluent les règles et les politiques que vous devez suivre pour utiliser ce site.</p>
                    <img src="{{ asset('jdg-joueur-du-grenier.gif') }}" alt="GIF animé" class="img-fluid">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/main/user/user.js') }}" type="module"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endsection
