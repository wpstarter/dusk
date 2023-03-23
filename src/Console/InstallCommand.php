<?php

namespace Laravel\Dusk\Console;

use WpStarter\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dusk:install
                {--proxy= : The proxy to download the binary through (example: "tcp://127.0.0.1:9000")}
                {--ssl-no-verify : Bypass SSL certificate verification when installing through a proxy}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Dusk into the application';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (! is_dir(ws_base_path('tests/Browser/Pages'))) {
            mkdir(ws_base_path('tests/Browser/Pages'), 0755, true);
        }

        if (! is_dir(ws_base_path('tests/Browser/Components'))) {
            mkdir(ws_base_path('tests/Browser/Components'), 0755, true);
        }

        if (! is_dir(ws_base_path('tests/Browser/screenshots'))) {
            $this->createScreenshotsDirectory();
        }

        if (! is_dir(ws_base_path('tests/Browser/console'))) {
            $this->createConsoleDirectory();
        }

        if (! is_dir(ws_base_path('tests/Browser/source'))) {
            $this->createSourceDirectory();
        }

        $stubs = [
            'ExampleTest.stub' => ws_base_path('tests/Browser/ExampleTest.php'),
            'HomePage.stub' => ws_base_path('tests/Browser/Pages/HomePage.php'),
            'DuskTestCase.stub' => ws_base_path('tests/DuskTestCase.php'),
            'Page.stub' => ws_base_path('tests/Browser/Pages/Page.php'),
        ];

        foreach ($stubs as $stub => $file) {
            if (! is_file($file)) {
                copy(__DIR__.'/../../stubs/'.$stub, $file);
            }
        }

        $this->info('Dusk scaffolding installed successfully.');

        $this->comment('Downloading ChromeDriver binaries...');

        $driverCommandArgs = ['--all' => true];

        if ($this->option('proxy')) {
            $driverCommandArgs['--proxy'] = $this->option('proxy');
        }

        if ($this->option('ssl-no-verify')) {
            $driverCommandArgs['--ssl-no-verify'] = true;
        }

        $this->call('dusk:chrome-driver', $driverCommandArgs);
    }

    /**
     * Create the screenshots directory.
     *
     * @return void
     */
    protected function createScreenshotsDirectory()
    {
        mkdir(ws_base_path('tests/Browser/screenshots'), 0755, true);

        file_put_contents(ws_base_path('tests/Browser/screenshots/.gitignore'), '*
!.gitignore
');
    }

    /**
     * Create the console directory.
     *
     * @return void
     */
    protected function createConsoleDirectory()
    {
        mkdir(ws_base_path('tests/Browser/console'), 0755, true);

        file_put_contents(ws_base_path('tests/Browser/console/.gitignore'), '*
!.gitignore
');
    }

    /**
     * Create the source directory.
     *
     * @return void
     */
    protected function createSourceDirectory()
    {
        mkdir(ws_base_path('tests/Browser/source'), 0755, true);

        file_put_contents(ws_base_path('tests/Browser/source/.gitignore'), '*
!.gitignore
');
    }
}
