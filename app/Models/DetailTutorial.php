<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTutorial extends Model
{
    protected $table = 'detail_tutorial';

    protected $fillable = [
        'master_tutorial_id',
        'text',
        'gambar',
        'code',
        'url',
        'order',
        'status',
    ];

    /**
     * Relasi Many-to-One: Detail milik satu master tutorial.
     */
    public function master()
    {
        return $this->belongsTo(MasterTutorial::class, 'master_tutorial_id');
    }
}
