<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * @codeCoverageIgnore
 */
final class GenerateRepositoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:repository {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Wimski AbstractModel Repositories repository and contract for a model';

    /** Execute the console command. */
    public function handle(): int
    {
        $model = $this->argument('model');

        // Format model to start with uppercase
        $model = ucfirst($model);

        $this->call('make:repository', [
            'model'        => 'App\\Models\\' . $model,
            '--contract'   => 'App\\Contracts\\Repositories\\' . $model . 'RepositoryInterface',
            '--repository' => 'App\\Repositories\\' . $model . 'Repository',
        ]);

        return Command::SUCCESS;
    }
}
