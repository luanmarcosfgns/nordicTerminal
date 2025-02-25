<?php
        namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comand extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ["connection_id","nome","comand"];

    protected $table = "comands";

    protected $searchableFields = ["comands.connection_id","comands.nome","comands.comand"];

    
   public function Connection()
    {
        return $this->belongsTo(Connection::class);
    }
    


}
