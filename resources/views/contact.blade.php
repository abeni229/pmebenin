@extends('layouts.app')

@section('title', 'Contact - PME Bénin')
@section('page-class', 'contact-page')

@section('content')
    <section class="hero contact-hero" style="background-image: url('https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=1200&q=80');">
        <div class="page-hero-content">
            <small>Contact</small>
            <h1>Entrer en contact pour lancer ton projet marketplace.</h1>
            <p>Nous sommes disponibles pour discuter de ton idée, des produits locaux à mettre en valeur et de la meilleure expérience client.</p>
        </div>
    </section>

    <section class="section">
        <p class="section-title">Contactez-nous</p>
        <p class="section-subtitle">Nous répondons rapidement aux demandes de création de site, marketplace et accompagnement vendeur.</p>
        <div class="contact-grid">
            <div class="contact-card">
                <h3>Équipe projet</h3>
                <p>Email : contact@pmebenin.bj</p>
                <p>Téléphone : +229 90 00 00 00</p>
            </div>
            <div class="contact-card">
                <h3>Message</h3>
                <p>Décris ton projet et les produits que tu veux vendre, et nous te proposerons une solution adaptée.</p>
            </div>
        </div>
    </section>
@endsection
