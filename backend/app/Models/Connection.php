<?php
        namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Connection extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ["nome","user_id","host","port","username","password"];

    protected $table = "connections";

    protected $searchableFields = ["connections.nome","connections.user_id","connections.host","connections.port","connections.username","connections.password"];


   public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function Comand()
    {
        return $this->hasMany(Comand::class);
    }


}
