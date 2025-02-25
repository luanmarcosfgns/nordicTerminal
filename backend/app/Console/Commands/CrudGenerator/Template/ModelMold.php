<?php

namespace App\Console\Commands\CrudGenerator\Template;

use App\Console\Commands\CrudGenerator\Util\WriteArchive;
use App\Console\Commands\CrudGenerator\Util\WriteModelText;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class ModelMold
{
    public static function buildModel($table):bool
    {
        try {
            self::setScope();
            self::checkAndInstallScoutPackage();
            $columns = self::getColumns($table);
            if(empty($columns)){
                echo 'The table not exists'.PHP_EOL;
            }
            $listName = [];
            $listNameTable = [];
            foreach ($columns as $column) {
                $listName[] = $column->name;
                $listNameTable[] = $table.'.'.$column->name;
            }
            $fillable = json_encode($listName);
            $searchableFields = json_encode($listNameTable);
            $hasMany = self::buildHasMany($table);
            $beLongTo = self::buildBeLongTo($table);
            $model = self::setClassName($table);

            $wireText = WriteModelText::render(self::mold(),[
                ':model'=>$model,
                ':fillable'=>$fillable,
                ':belongsTo'=>$beLongTo,
                ':hasMany'=>$hasMany,
                ':table'=>$table,
                ':searchableFields'=>$searchableFields
            ]);
            $dir = app_path('Models');
            $filename = $model.'.php';
            return WriteArchive::now($wireText,$filename,$dir)!==false;
        }catch (\Exception $e){
            Log::error("code:".$e->getCode()."message".$e->getMessage()."file:".$e->getFile()."line:".$e->getLine());
            return false;
        }


    }

    public static function mold()
    {
        $texto =   file_get_contents(__DIR__ . '/Molds/ModelMold.mold');
        return $texto;

    }

    private static function buildHasMany($table):string
    {
        $hasMany = "";
        $database = getenv('DB_DATABASE');
        $foreign = rtrim($table,'s').'_id';
        $sql = "select TABLE_NAME as table_name
from information_schema.COLUMNS
where TABLE_SCHEMA = ':database'
    and COLUMN_NAME = ':foreign_id'
    and TABLE_NAME <> ':table'";

        $sql = str_replace(':foreign_id', $foreign, $sql);
        $sql = str_replace(':database', $database, $sql);
        $sql = str_replace(':table', $table, $sql);

        $tables = DB::select($sql);
        if(empty($tables)){
            return "";
        }
        $moldHasMany = '    public function :modelName()
    {
        return $this->hasMany(:modelName::class);
    }';
        foreach ($tables as $table) {
            $hasMany .=   PHP_EOL.str_replace(':modelName',self::setClassName($table->table_name),$moldHasMany);

        }
        return $hasMany;


    }

    private static function buildBeLongTo($table):string
    {
        $beLongTo = "";
        $database = getenv('DB_DATABASE');

        $sql = "select COLUMN_NAME as name, DATA_TYPE as type, CHARACTER_MAXIMUM_LENGTH as length
from information_schema.COLUMNS
where TABLE_NAME = ':table'
    and TABLE_SCHEMA = ':database'
    and COLUMN_NAME like '%_id'";

        $sql = str_replace(':table', $table, $sql);
        $sql = str_replace(':database', $database, $sql);

        $columns = DB::select($sql);

        if (empty($columns)) {
            return "";
        }
        $moldBeLongTo = '   public function :modelName()
    {
        return $this->belongsTo(:modelName::class);
    }';
        foreach ($columns as $column) {
            $table =rtrim($column->name, '_id').'s';

            if (Schema::hasTable($table)) {
                $beLongTo .= PHP_EOL . str_replace(':modelName', self::setClassName($table), $moldBeLongTo);

            }
        }

        return $beLongTo;
    }

    private static function setClassName($table) :string
    {
        $listModelName = explode('_',$table);
        $numberListName = count($listModelName);
        $modelName = "";
        foreach ($listModelName as $i => $word) {
            if(count($listModelName)==1 || ($numberListName>1 && ($numberListName-1)==$i)){
                $modelName .= ucfirst(rtrim($word, 's'));
            }else{
                $modelName .= ucfirst($word);
            }

        }
            return $modelName;
    }


    private static function getColumns($table)
    { $database = getenv('DB_DATABASE');

        $sql = "select COLUMN_NAME as name, DATA_TYPE as type, CHARACTER_MAXIMUM_LENGTH as length
from information_schema.COLUMNS
where TABLE_NAME = ':table'
    and TABLE_SCHEMA = ':database'
    and COLUMN_NAME NOT IN ('id', 'created_at', 'updated_at')
    ";

        $sql = str_replace(':table', $table, $sql);
        $sql = str_replace(':database', $database, $sql);

       return DB::select($sql);
    }

    private static function checkAndInstallScoutPackage()
    {
        $packageName = 'laravel/scout';

        // Check if the package is already installed
        if (self::isPackageInstalled($packageName)) {
            echo "Package $packageName is already installed.".PHP_EOL;
        } else {
            // Install the package using Composer
            echo "Installing $packageName...\n";
            self::installPackage($packageName);
            echo "Package $packageName installed successfully!";
        }
    }

    private static function isPackageInstalled(string $packageName): bool
    {
        // Get the installed packages list from composer.lock
        $composerLockContent = file_get_contents('composer.lock');
        $composerLockData = json_decode($composerLockContent, true);

        // Check if the package exists in the list of installed packages
        foreach ($composerLockData['packages'] as $package) {
            if ($package['name'] === $packageName) {
                return true;
            }
        }

        return false;
    }

    private static function installPackage(string $packageName)
    {
        $cmd = "composer require $packageName";
        shell_exec($cmd);
    }

    private static function setScope()
    {
        $wireText = '<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait Searchable
{
    /**
     * Search paginated items ordering by ID descending
     */
    public function scopeSearchLatestPaginated(
        Builder $query,
        string $search,
        int $paginationQuantity = 10
    ): Builder {
        return $query
            ->search($search)
            ->orderBy("updated_at", "desc")
            ->paginate($paginationQuantity);
    }

    /**
     * Adds a scope to search the table based on the
     * $searchableFields array inside the model
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        $query->where(function ($query) use ($search) {
            foreach ($this->getSearchableFields() as $field) {
                $query->orWhere($field, "like", "%{$search}%");
            }
        });

        return $query;
    }

    /**
     * Returns the searchable fields. If $searchableFields is undefined,
     * or is an empty array, or its first element is \'*\', it will search
     * in all table fields
     */
    protected function getSearchableFields(): array
    {
        if (isset($this->searchableFields) && count($this->searchableFields)) {
            return $this->searchableFields[0] === \'*\'
                ? $this->getAllModelTableFields()
                : $this->searchableFields;
        }

        return $this->getAllModelTableFields();
    }

    /**
     * Gets all fields from Model\'s table
     */
    protected function getAllModelTableFields(): array
    {
        $tableName = $this->getTable();

        return $this->getConnection()
            ->getSchemaBuilder()
            ->getColumnListing($tableName);
    }
}
';
        $dir =  app_path('Models/Scopes');
        $filename =  'Searchable.php';
        WriteArchive::now($wireText,$filename,$dir)!==false;

    }

}
