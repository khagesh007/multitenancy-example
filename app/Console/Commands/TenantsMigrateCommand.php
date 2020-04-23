<?php

namespace App\Console\Commands;

use App\Tenant;
use Illuminate\Console\Command;

class TenantsMigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenants:migrate {tenant?} {--fresh} {--seed}';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->argument('tenant')) {
            $this->migrate(
                Tenant::find($this->argument('tenant'))
            );

        } else {
            Tenant::all()->each(
                fn($tenant) => $this->migrate($tenant)
            );
        }
    }

    /**
     * @param \App\Tenant $tenant
     * @return int
     */
    public function migrate($tenant)
    {
        $tenant->use();

        $this->info("Migrating: {$tenant->name} #{$tenant->id}");

        $command = $this->option('fresh') ? 'migrate:fresh' : 'migrate';

        $options = ['--force' => true];

        if ($this->option('seed')) {
            $options['--seed'] = true;
        }

        return $this->call($command, $options);
    }
}
