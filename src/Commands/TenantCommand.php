<?php

namespace Blaze\TenantTerminal\Commands;

use Blaze\TenantTerminal\TerminalSession;
use Blaze\TenantTerminal\TenantResolver;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TenantCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enter interactive tenant terminal';

    /**
     * The terminal session instance.
     *
     * @var TerminalSession
     */
    protected $session;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $resolver = new TenantResolver();
        $tenants = $resolver->getAllTenants();

        if (empty($tenants)) {
            $this->error('No tenants found!');
            return Command::FAILURE;
        }

        // Display tenants list
        $this->info('Available Tenants:');
        $this->newLine();
        
        $tenantList = [];
        foreach ($tenants as $index => $tenant) {
            $id = $resolver->getTenantId($tenant);
            $name = $resolver->getTenantName($tenant);
            $tenantList[$index + 1] = ['id' => $id, 'tenant' => $tenant];
            $this->line(sprintf('  [%d] %s (ID: %s)', $index + 1, $name, $id));
        }

        $this->newLine();
        
        try {
            $selectedIndex = (int) $this->ask('Select tenant number (or press Ctrl+X/Ctrl+C to exit)');
        } catch (\Exception $e) {
            // User pressed Ctrl+C or Ctrl+X
            $this->newLine();
            $this->info('Exiting...');
            return Command::SUCCESS;
        }

        // Validate selection
        if (!isset($tenantList[$selectedIndex])) {
            $this->error('Invalid selection!');
            return Command::FAILURE;
        }

        $selectedTenant = $tenantList[$selectedIndex]['tenant'];
        $tenantId = $tenantList[$selectedIndex]['id'];

        $this->newLine();
        $this->info("Entered tenant context: {$resolver->getTenantName($selectedTenant)}");
        $this->line("Type 'exit' or press Ctrl+X to leave tenant context");
        $this->newLine();

        // Start interactive session
        $this->session = new TerminalSession($selectedTenant, $tenantId, $resolver);
        $this->session->start($this);

        return Command::SUCCESS;
    }

    /**
     * Handle interactive input.
     *
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        return $this->handle();
    }
}
