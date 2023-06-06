<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScrapedData extends Model
{
    protected $table = 'scraped_data'; // Nom de la table dans la base de données

    protected $fillable = ['title', 'paragraph']; // Champs remplissables

    // Définir d'autres relations ou méthodes si nécessaire
}
