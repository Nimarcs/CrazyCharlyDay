<?php
namespace CustomBox\Controllers;

use CustomBox\Models\Produit;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\RequestInterface as Request;
use CustomBox\Views\ViewRender;
use CustomBox\Views\ViewGestionProduits;

/**
 * Classe controlleur produit qui gère les actions sur les produits
 */
class ProduitController extends Controller{

    /**
     * Fonction get et poste qui gère l'affichage pour créer un produit mais aussi la requete pour l'enregitrer dans la bdd
     */
    public function creerProduit(Request $request, Response $response, $parameters):Response //2
    {
        $vue=new ViewGestionProduits($this->container);
        $vueRender = new ViewRender($this->container);
        if ($request->isPost()){

            $titre = filter_var( $request->getParsedBody()['titre'], FILTER_SANITIZE_STRING);
            $description = filter_var( $request->getParsedBody()['description'], FILTER_SANITIZE_STRING);
            $categorie = filter_var( $request->getParsedBody()['request'], FILTER_SANITIZE_STRING);
            $poids = filter_var( $request->getParsedBody()['poids'], FILTER_SANITIZE_NUMBER_FLOAT);

            $parameters["titre"]=$titre;
            $parameters["description"]=$description;
            $parameters["categorie"]=$categorie;
            $parameters["poids"]=$poids;

            $this->ajouterProduitBDD($parameters);
            $response->withRedirect($this->container->router->pathFor('home'));
        } else {
            $response->getBody()->write($vue->render(2, $parameters));
        }
        return $response;
    }

    /**
     * Fonction get et post qui gère l'affichage et l'action de modification d'un produit
     */
    public function modifierProduit(Request $request, Response $response, $parameters) //3
    {
        $vue=new ViewGestionProduits($this->container);
        $vueRender = new ViewRender($this->container);

        if($request->isPost()){
            $titre = filter_var( $request->getParsedBody()['titre'], FILTER_SANITIZE_STRING);
            $description = filter_var( $request->getParsedBody()['description'], FILTER_SANITIZE_STRING);
            $categorie = filter_var( $request->getParsedBody()['categorie'], FILTER_SANITIZE_NUMBER_INT);
            $poids = filter_var( $request->getParsedBody()['poids'], FILTER_SANITIZE_NUMBER_FLOAT);

            $parameters["titre"]=$titre;
            $parameters["description"]=$description;
            $parameters["categorie"]=$categorie;
            $parameters["poids"]=$poids;

            $produit = $this->recupererProduit($parameters["idProduit"]);

            $this->modifierProduitBDD($produit,$parameters);


            $response->withRedirect($this->container->router->pathFor('home'));
        } else {
            $response->getBody()->write($vue->render(3, $parameters));
        }
        return $response;
    }

    /**
     * Fonction qui ajoute un produit dans la bdd
     */
    private function ajouterProduitBDD(array $args) : Produit{
        $p = new Produit();
        $p->titre = $args['titre'];
        $p->description = $args['description'];
        $p->categorie = $args['categorie'];
        $p->poids = $args['poids'];

        $res = $p->save();
        if (!$res){
            throw new \Exception("Sauvegarde de l'item a échoué");
        }
        return $p;
    }

    /**
     * Fonction qui modifie un produit dans la bdd
     */
    private function modifierProduitBDD(Produit $p, array $args):void {
        $p->titre = $args['titre'];
        $p->description = $args['description'];
        $p->categorie = $args['categorie'];
        $p->poids = $args['poids'];

        $res = $p->save();
        if (!$res){
            throw new \Exception("Sauvegarde de l'item a échoué");
        }
    }

    /**
     * Fonction qui recupere un produit selon son id
     */
    private function recupererProduit(int $id): ?Produit{
        try {
            return Produit::query()->where('id', '=', $id)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }


    /**
     * Generere l'affichage du catalogue
     */
    public function affichageCatalogue(Request $rq, Response $rs, array $args): Response
    {
        try {
            $vue = new ViewGestionProduits($this->container);

            //on recupere les items
            $produits = Produit::query()->select('*')->get();

            $rs->getBody()->write($vue->render(1, [$produits]));
        } catch (\Exception $e) {
            $vue = new ViewRender($this->container);
            $rs->getBody()->write($vue->render($vue->afficherErreur("Erreur dans l'affichage du catalogue...".$e->getMessage()."<br>".$e->getTrace())));
        }
        return $rs;
    }


}