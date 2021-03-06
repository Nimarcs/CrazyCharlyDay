<?php
declare(strict_types=1);

// NAMESPACE
namespace CustomBox\Views;

// IMPORTS
use CustomBox\Models\User;
use Slim\Container;

/**
 * Classe VueRender
 *
 */
class ViewRender
{

    // ATTRIBUTS
    private Container $container;

    // CONSTRUCTEUR
    public function __construct(Container $c)
    {
        $this->container = $c;
    }

    // METHODES

    /**
     * Fonction qui retourne l'affichage général du site web
     * @param $content Container
     * @return string String: texte html, cointenu global de chaque page
     * @author Lucas Weiss
     */
    public function render(string $content): string
    {
        $fonctionnaliteAdmin=<<<END

    END;
        if(isset($_SESSION['user'])){
            $admin = User::where('id', $_SESSION['user'])->first();
            if($admin->admin==1){
                $fonctionnaliteAdmin=<<<END

            <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Fonctionnalité Admin</a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                                        <li class="dropdown-item"><a class="nav-link" href="{$this->container->router->pathFor("creationProduit")}">Création de produit</a></li>
                                                        <li class="nav-item"><a class="dropdown-item" href="{$this->container->router->pathFor("modifierProduit")}">Modification de produits</a></li>
                                                        <li><hr class="dropdown-divider" /></li>
                                                        <li class="nav-item"><a class="dropdown-item" href="{$this->container->router->pathFor("creationBoite")}">Création de boite</a></li>
                                                        <li class="nav-item"><a class="dropdown-item" href="{$this->container->router->pathFor("modifierBoite")}">Modification de boite</a></li>
                                                        <li><hr class="dropdown-divider" /></li>
                                                        <li class="nav-item"><a class="dropdown-item" href="{$this->container->router->pathFor("affichageUser")}">Afficher les utilisateurs</a></li>

                                    </ul>
                                </li>
        END;

            }
        }

        $connexion = <<<END
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
        END;
        if(!isset($_SESSION['user'])){
            $connexion .= <<<END
                                    <li class="nav-item"><a class="nav-link" href="{$this->container->router->pathFor("signup")}">Connexion</a></li>  
                                    <li class="nav-item"><a class="nav-link" href="{$this->container->router->pathFor("signin")}">Inscription</a></li>
            END;
        }else {
            $connexion .= <<<END
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-person-circle"></i></a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <li><a class="dropdown-item" href="{$this->container->router->pathFor("editCompte")}">Modification de mot de passe</a></li>
                                        <li><hr class="dropdown-divider" /></li>
                                        <li><a class="dropdown-item" href="{$this->container->router->pathFor("signout")}">Déconnexion</a></li>
                                    </ul>
                                </li>

            END;
        }

        $connexion .= <<<END
                </ul>
        END;
        if (isset($_SESSION["panier"])) {
            $nbArticle = count($_SESSION["panier"]);
        } else {
            $nbArticle = 0;
        }
        return <<<END
        <!DOCTYPE html>
        <html lang="fr">
            <head>
                <meta charset="utf-8" />
                <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
                <meta name="description" content="" />
                <meta name="author" content="" />
                <title>CustomBox</title>
                <!-- Favicon-->
                <link rel="icon" type="image/ico" href="{$this->container->router->pathFor("home")}assets/favicon.ico"/>
                <!-- Bootstrap icons-->
                <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
                <!-- Core theme CSS (includes Bootstrap)-->
                <link href="{$this->container->router->pathFor("home")}assets/css/style.css" rel="stylesheet" />
            </head>
            <body>
                <!-- Navigation-->
                <nav class="navbar navbar-expand-lg navbar-light bg-light w-100">
                    <div class="container px-4 px-lg-5 ">
                        <a class="navbar-brand" href="{$this->container->router->pathFor("home")}">
                            <img style="width: 40px" src="{$this->container->router->pathFor("home")}assets/logo.png">
                        </a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                                <li class="nav-item"><a class="nav-link" aria-current="page" href="{$this->container->router->pathFor("home")}">Accueil</a></li>
                                <li class="nav-item"><a class="nav-link" href="https://www.instagram.com/atelier17.91/" target="_blank">A propos</a></li>
                               
                            $fonctionnaliteAdmin  
                            </ul>
                            <form class="d-flex">              
                              $connexion
        
                                    <a class="btn btn-outline-secondary" href="{$this->container->router->pathFor("panier")}">
                                        <i class="bi bi-box"></i>
                                        Panier
                                        <span class="badge bg-dark text-white ms-1 rounded-pill">$nbArticle</span>
                                    </a>
                                
                            </form>
                        </div>
                    </div>
                </nav>
                
                $content
                
                                
                <!-- Footer-->
                <footer class="py-5 bg-dark">
                    <div class="container"><img src="{$this->container->router->pathFor("home")}assets/logoblanc.png" class="centrerImage"></div>
                </footer>
                <!-- Bootstrap core JS-->
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
                <!-- Core theme JS-->
            </body>
        </html>
        END;
    }

    /**
     * Génère un mesage d'erreur
     * @param string $message message d'erreur
     * @return string html a afficher
     */
    public function afficherErreur(string $message)
    {
        return $this->render("<div class='block-heading'>
                <div class='div-panier'>
                    <div class='panier-titre'>
                        <h2>Erreur</h2>
                    </div>
                        <p class='txt-err'>$message</p>
                </div>
            </div>");
    }

    /**
     * Genere un message d'information
     * @param string $message message
     * @return string html a afficher
     */
    public function afficherMessage(string $message)
    {
        return $this->render("<div class='block-heading'>
                <h2 class='text-info'>Message</h2>
                <p>$message</p>
            </div>                
        ");
    }
}