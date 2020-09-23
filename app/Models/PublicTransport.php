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

    /**
     * The name of related database table.
     *
     * @var string
     */
    protected $table = 'public_transport';

    /**
     * Get ID ("id") attribute.
     *
     * @param  int  $id
     * @return int
     */
    public function getIdAttribute(int $id)
    {
        return $id;
    }
}
