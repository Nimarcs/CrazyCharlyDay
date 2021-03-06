<?php
declare(strict_types=1);

// NAMESPACE
namespace CustomBox\Models;

/**
 * Classe Categorie
 * Représente une categorie au sein de la base de données
 * Hérite de la classe Modele du module Eloquent
 */
class Categorie extends \Illuminate\Database\Eloquent\Model
{

    // ATTRIBUTS

    public $timestamps = false;
    protected $table = 'categorie';
    protected $primaryKey = 'id';


    // CONSTRUCTEUR

    public function produits()
    {
        return $this->hasMany('\CustomBox\Models\Produit', 'categorie');
    }

}
