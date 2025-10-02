<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {service}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Service';

    private $folder = 'app/Http/Services/';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $service = $this->argument('service');
        $path = base_path($this->folder . $service . '.php');
        if (!File::exists(dirname($path))) {
            File::makeDirectory(dirname($path), 0755, true);
        }

        if (File::exists($path)) {
            $this->newLine();
            $this->error('ERROR:');
            $this->line("\tThe service '{$service}' already exists!");
            $this->newLine();
            return;
        }

        $stub = <<<PHP
            <?php

            namespace App\Http\Services;

            class {$service}
            {
                public function __construct()
                {
                    // Service Inicialization
                }
            }
            PHP;

        File::put($path, $stub);

        $this->newLine();
        $this->line("\tService '{$service}' created with successful in path:\n\t{$path}");
        $this->newLine();
    }
}
