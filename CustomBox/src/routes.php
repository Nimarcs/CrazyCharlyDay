<?php

/**
 * Home
 */


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->post('/', CustomBox\Controllers\HomeController::class . ':index')->setName('home');
$app->get('/', \CustomBox\Controllers\ProduitController::class . ':affichageCatalogue')->setName('home');

$app->get('/creationProduit', CustomBox\Controllers\ProduitController::class.':creerProduit')->setName("creationProduit");

$app->get('/modificationProduit', CustomBox\Controllers\ProduitController::class.':modifierProduit')->setName("modifierProduit");
$app->post('/modificationProduit', CustomBox\Controllers\ProduitController::class.':modifierProduit')->setName("modifierProduit");


$app->get('/creationBoite', \CustomBox\Controllers\BoiteController::class.':creerBoite')->setName("creationBoite");
$app->get('/modificationBoite', CustomBox\Controllers\BoiteController::class.':modifierBoite')->setName("modifierBoite");
$app->post('/modificationBoite', CustomBox\Controllers\BoiteController::class.':modifierBoite');

$app->get('/signin', CustomBox\Controllers\Authentification::class . ':getSignIn')->setName('signin');
$app->post('/signin', CustomBox\Controllers\Authentification::class . ':postSignIn');

$app->get('/signup', CustomBox\Controllers\Authentification::class . ':getSignUp')->setName('signup');
$app->post('/signup', CustomBox\Controllers\Authentification::class . ':postSignUp');

$app->get('/signout', CustomBox\Controllers\Authentification::class . ':getSignOut')->setName('signout');

$app->get('/editCompte', CustomBox\Controllers\Authentification::class . ':getEditCompte')->setName('editCompte');
$app->post('/editCompte', CustomBox\Controllers\Authentification::class . ':postEditCompte');

$app->get('/affichageUser', CustomBox\Controllers\AffichageUsers::class . ':getAffichage')->setName('affichageUser');


$app->get('/panier', CustomBox\Controllers\GestionCommandeController::class.':afficherPanier')->setName("panier");
$app->get('/ajouterPanier/{id}', CustomBox\Controllers\GestionCommandeController::class.':ajouterPanier')->setName("ajouterPanier");
$app->post('/validerCommande', CustomBox\Controllers\GestionCommandeController::class.':validerCommande')->setName("validerCommande");

$app->get('/test', function (Request $rq, Response $rs, array $args): Response {
    $rs->getBody()->write("test");
    return $rs;
})->setName('afficherListe');

