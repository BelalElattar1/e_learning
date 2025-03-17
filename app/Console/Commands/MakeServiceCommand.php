<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeServiceCommand extends Command
{
    protected $signature = 'make:service {name}';
    protected $description = 'Create Service, Controller, and Request with folders';

    public function handle()
    {
        $name = $this->argument('name');

        // كلاس الاسم كابيتال
        $className = ucfirst($name);

        // فولدر الاسم كله small مع s في الآخر
        $folderName = strtolower($name) . 's';

        // مسارات الفولدارات
        $servicePath = app_path("Services/{$folderName}");
        $requestPath = app_path("Http/Requests/{$folderName}");
        $controllerPath = app_path("Http/Controllers");

        // إنشاء الفولدارات لو مش موجودة
        if (!File::exists($servicePath)) {
            File::makeDirectory($servicePath, 0755, true);
        }

        if (!File::exists($requestPath)) {
            File::makeDirectory($requestPath, 0755, true);
        }

        // إنشاء الملفات
        File::put("{$servicePath}/{$className}Service.php", $this->generateServiceContent($folderName, $className));
        File::put("{$requestPath}/{$className}Request.php", $this->generateRequestContent($folderName, $className));
        File::put("{$controllerPath}/{$className}Controller.php", $this->generateControllerContent($folderName, $className));

        $this->info("Service, Request, and Controller for {$className} created successfully!");
    }

    // محتوى الـ Service
    private function generateServiceContent($folderName, $className)
    {
        return <<<PHP
<?php

namespace App\Services\\$folderName;

use Exception;

class {$className}Service
{

}

PHP;
    }

    // محتوى الـ Request
    private function generateRequestContent($folderName, $className)
    {
        return <<<PHP
<?php

namespace App\Http\Requests\\$folderName;

use App\Http\Requests\BaseRequest;

class {$className}Request extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // Define your validation rules
        ];
    }
}

PHP;
    }

    // محتوى الـ Controller
    private function generateControllerContent($folderName, $className)
    {
        // اسم السيرفيس للإنجيكت user_service
        $serviceVar = Str::snake($className) . '_service';

        return <<<PHP
<?php

namespace App\Http\Controllers;

use Exception;
use App\Services\\$folderName\\{$className}Service;
use App\Http\Requests\\$folderName\\{$className}Request;

class {$className}Controller extends Controller
{
    protected \${$serviceVar};

    public function __construct({$className}Service \${$serviceVar})
    {
        \$this->{$serviceVar} = \${$serviceVar};
    }

}

PHP;
    }
}