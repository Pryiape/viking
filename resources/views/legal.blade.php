@extends('layouts.app')

@section('title', 'Mentions légales')

@section('content')
<div class="container mt-5">
    {{-- Titre principal de la page --}}
    <h1 class="mb-4">Mentions légales</h1>

    {{-- Informations sur le site --}}
    <p><strong>Nom du site :</strong> Viking Violet - Hall de la Mode</p>
    <p><strong>Responsable de la publication :</strong> L'équipe Viking Violet</p>
    <p><strong>Hébergement :</strong> Hébergé par votre fournisseur ou votre serveur</p>

    {{-- Section propriété intellectuelle --}}
    <h3>Propriété intellectuelle</h3>
    <p>Le contenu de ce site (textes, images, code, etc.) est protégé par le droit d’auteur. Toute reproduction non autorisée est interdite.</p>

    {{-- Section utilisation des données --}}
    <h3>Utilisation des données</h3>
    <p>Les données personnelles éventuellement collectées sont uniquement utilisées dans le cadre du fonctionnement de l'application (connexion, builds, etc.).</p>

    {{-- Section cookies --}}
    <h3>Cookies</h3>
    <p>Ce site peut utiliser des cookies à des fins de fonctionnement ou de mesure d’audience.</p>

    {{-- Section contact --}}
    <h3>Contact</h3>
    <p>Pour toute question ou réclamation, merci de nous contacter via la page de contact ou à l'adresse e-mail suivante : contact@vikingviolet.com</p>
</div>
@endsection
