<?php

namespace App\Console\Commands\CrudGenerator\Template\Molds;

class RouteMold
{
    public static function buildRouteBackend($table)
    {

        $route = self::moldRouteBackend($table);
        $use = self::moldUseBackend($table);
        $dir = base_path('/routes/api.php');
        $content = file_get_contents($dir);
        if (!str_contains($content, $use)) {
            $content = str_replace('<?php', '<?php ' . PHP_EOL . $use, $content);
        }

        if (!str_contains($content, $route)) {
            $content .= PHP_EOL . $route;
        }
        file_put_contents($dir,$content);
        return true;
    }

    public static function buildRouteFrontend($table)
    {
        $use = self::moldUseFrontEnd($table);
        $route = self::moldRouteFrontend($table);
        $dir = self::diretorioFrontend();
        $dir = $dir.'/src/route/web.js';
        $content = file_get_contents($dir);
        if (!str_contains($content, $use)) {
            $content = str_replace('const routes', $use.PHP_EOL.'const routes', $content);
        }
        if (!str_contains($content, $route)) {
            $content = str_replace('];', $route.PHP_EOL.'];', $content);
        }
        file_put_contents($dir,$content);


        return true;
    }

    private static function moldRouteBackend($table)
    {
        $tableExplode = explode('_', $table);
        $friendlyName = '';
        foreach ($tableExplode as $item) {
            $friendlyName .= ucfirst($item);
        }

        $mold = "Route::get('/$table/list', [" . $friendlyName . "Controller::class, 'find']);";
        $mold .= PHP_EOL."Route::resource('$table', " . $friendlyName . "Controller::class)->middleware('auth');";
        return $mold;
    }

    private static function moldUseBackend($table)
    {
        $tableExplode = explode('_', $table);
        $friendlyName = '';
        foreach ($tableExplode as $item) {
            $friendlyName .= ucfirst($item);
        }
        return "use App\Http\Controllers\\" . $friendlyName . "Controller;";
    }

    private static function moldRouteFrontend($table)
    {
        $tableExplode = explode('_', $table);
        $friendlyName = '';
        foreach ($tableExplode as $item) {
            $friendlyName .= ucfirst($item);
        }

        $mold = "
    {
        path: '/$table/index',
        name: 'index" . $friendlyName . "',
        component: index" . $friendlyName . ",
        meta: {
            auth: true
        }
    },
        ";

        return $mold;
    }

    private static function moldUseFrontEnd($table)
    {
        $tableExplode = explode('_', $table);
        $friendlyName = '';
        foreach ($tableExplode as $item) {
            $friendlyName .= ucfirst($item);
        }
        return "import index" . $friendlyName . " from \"@/views/$table/Index" . $friendlyName . ".vue\";";
    }

    private static function diretorioFrontend(): string
    {
        $dirArray = explode('/', base_path());
        $numberArray = count($dirArray);
        $dirArray[($numberArray - 1)] = 'frontend';
        return implode('/', $dirArray);
    }

}
