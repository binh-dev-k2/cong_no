<?php

namespace App\Models;

use App\Traits\LogActivityTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collaborator extends Model
{
    use HasFactory;
    use LogActivityTrait;

    protected $table = 'collaborators';

    protected $guarded = ['id'];

    public function businesses()
    {
        return $this->hasMany(Business::class);
    }

    public function businessFees()
    {
        return $this->hasMany(CollaboratorBusinessFee::class);
    }
}
