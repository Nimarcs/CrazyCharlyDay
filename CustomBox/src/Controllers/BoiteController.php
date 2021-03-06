<?php
namespace CustomBox\Controllers;

use CustomBox\Models\Boite;
use CustomBox\Views\ViewRender;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use CustomBox\Views\ViewGestionBoite;

class BoiteController extends Controller{
    public function creerBoite(Request $request, Response $response, $parameters):Response{
        $vue=new ViewGestionBoite($this->container);
        $vueRender = new ViewRender($this->container);
        if ($request->isPost()){
            $taille = filter_var( $request->getParam('taille'), FILTER_SANITIZE_STRING);
            $poidsmax = filter_var( $request->getParam('poidsmax'), FILTER_SANITIZE_NUMBER_FLOAT);

            $parameters['taille']=$taille;
            $parameters['poidsmax']=$poidsmax;
            $this->ajouterBoiteBDD($parameters);
            $response->getBody()->write($vueRender->afficherMessage("Votre boite a bien été créé"));
        } else {
            $response->getBody()->write($vue->render(1, $parameters));
        }
        return $response;
    }

    public function modifierBoite(Request $request, Response $response, $parameters):Response{
        $vue=new ViewGestionBoite($this->container);
        $vueRender = new ViewRender($this->container);
        if ($request->isPost()){
            $taille = filter_var( $request->getParam('taille'), FILTER_SANITIZE_STRING);
            $poidsmax = filter_var( $request->getParam('poidsmax'), FILTER_SANITIZE_NUMBER_FLOAT);

            $parameters['taille']=$taille;
            $parameters['poidsmax']=$poidsmax;

            $boite = $this->recupererBoite($parameters["idBoite"]);
            $this->modifierBoiteBDD($boite, $parameters);
            $response->withRedirect($this->container->router->pathFor('home'));
        } else {
            $response->getBody()->write($vue->render(1,$parameters));
        }
        return $response;
    }

    private function ajouterBoiteBDD(array $args){
        $b = new Boite();
        $b->taille = $args["taille"];
        $b->poidsmax = $args["poidsmax"];
        $res=$b->save();
        if (!$res){
            throw new \Exception("Sauvegarde de l'item a échoué");
        }
        return $b;
    }

    private function modifierBoiteBDD(Boite $b, array $args){
        $b->taille = $args["taille"];
        $b->poidsmax = $args["poidsmax"];
        $res=$b->save();
        if (!$res){
            throw new \Exception("Sauvegarde de l'item a échoué");
        }
    }
    private function recupererBoite(int $id): ?Boite{
        try {
            return Boite::query()->where('id', '=', $id)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }
}
