<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class PublicTransport extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'route_number',
        'capacity',
        'organization_name',
    ];

    protected $table = 'public_transport';

    public function getIdAttribute(int $id)
    {
        return $id;
    }
}
