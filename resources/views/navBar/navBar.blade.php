<!-- Bootstrap 5.3.2 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="{{ asset('viking.jpg') }}" alt="Viking Logo" style="height: 40px; margin-right: 10px;">
            Viking Violet
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" 
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link @if(Request::route()->getName() == 'app_home') active @endif" 
                       aria-current="page" href="{{ route('app_home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(Request::route()->getName() == 'app_about') active @endif" 
                       aria-current="page" href="{{ route('app_about') }}">About</a>
                </li>
            </ul>

            <div class="btn-group">
            @guest
                <button type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Connexion
                </button>
                    <ul class="dropdown-menu dropdown-menu-end"> <!-- Ajout de dropdown-menu-end -->
                    <li><a class="dropdown-item" href="{{ route('login') }}">Login</a></li>
                    <li><a class="dropdown-item" href="{{ route('register') }}">Register</a></li>
                </ul>
            @endguest

                @auth
                <button type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ Auth::user()->name }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end"> <!-- Ajout de dropdown-menu-end -->
                    <li><a class="dropdown-item" href="{{ route('app_profile') }}">Profile</a></li>
                    <li><a class="dropdown-item" href="{{ route('builds.index') }}">Builds</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="post">
                            @csrf
                            <button type="submit" class="dropdown-item">Déconnexion</button>
                        </form>
                    </li>
                </ul>
                @endauth

            </div>
        </div>
    </div>
</nav>

<!-- Script pour forcer l'initialisation des dropdowns si nécessaire -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'))
        var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
            return new bootstrap.Dropdown(dropdownToggleEl);
        });
    });
</script>