<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateFeatureTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:test {test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Test';

    private $folder = 'tests/Feature/';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $test = $this->argument('test');
        $path = base_path($this->folder . $test . '.php');
        if (!File::exists(dirname($path))) {
            File::makeDirectory(dirname($path), 0755, true);
        }

        if (File::exists($path)) {
            $this->newLine();
            $this->error('ERROR:');
            $this->line("\tThe test '{$test}' already exists!");
            $this->newLine();
            return;
        }

        $stub = <<<PHP
            <?php

            namespace Tests\Feature;

            use Illuminate\Foundation\Testing\RefreshDatabase;
            use Illuminate\Support\Facades\Hash;
            use Tests\TestCase;
            
            class {$test} extends TestCase
            {
            // use RefreshDatabase;
                #[Test]
                public function test_initialization()
                {
                    //test initialization
                }
            }
            PHP;

        File::put($path, $stub);

        $this->newLine();
        $this->line("\Test '{$test}' created with successful in path:\n\t{$path}");
        $this->newLine();
    }
}
