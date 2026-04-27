<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterTutorial extends Model
{
    protected $table = 'master_tutorial';

    protected $fillable = [
        'judul',
        'kode_makul',
        'url_presentation',
        'url_finished',
        'creator_email',
    ];

    /**
     * Relasi One-to-Many: Satu master memiliki banyak detail tutorial.
     */
    public function details()
    {
        return $this->hasMany(DetailTutorial::class, 'master_tutorial_id');
    }
}
