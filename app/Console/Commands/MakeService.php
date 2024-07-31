<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeService extends Command
{
    protected $signature = 'make:service {name}';
    protected $description = 'Create a new service class';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->argument('name');
        $directory = app_path('Services');
        $path = $directory . '/' . $name . '.php';

        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        if (File::exists($path)) {
            $this->error('Service already exists!');
            return 1;
        }

        File::put($path, $this->getStub($name));
        $this->info('Service created successfully.');
        return 0;
    }

    protected function getStub($name)
    {
        return "<?php\n\nnamespace App\Services;\n\nclass $name\n{\n    //\n}\n";
    }
}
