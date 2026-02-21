<?php

namespace Blaze\TenantTerminal;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class TerminalSession
{
    /**
     * The current tenant instance.
     *
     * @var mixed
     */
    protected $tenant;

    /**
     * The tenant ID.
     *
     * @var string
     */
    protected $tenantId;

    /**
     * The tenant resolver instance.
     *
     * @var TenantResolver
     */
    protected $resolver;

    /**
     * Create a new terminal session instance.
     *
     * @param  mixed  $tenant
     * @param  string  $tenantId
     * @param  TenantResolver  $resolver
     */
    public function __construct($tenant, $tenantId, TenantResolver $resolver)
    {
        $this->tenant = $tenant;
        $this->tenantId = $tenantId;
        $this->resolver = $resolver;
    }

    /**
     * Start the interactive terminal session.
     *
     * @param  Command  $command
     * @return void
     */
    public function start(Command $command)
    {
        $tenantName = $this->resolver->getTenantName($this->tenant);
        
        // Set up signal handler for Ctrl+C and Ctrl+X
        if (function_exists('pcntl_signal')) {
            pcntl_signal(SIGINT, function() use ($command) {
                $command->newLine();
                $command->info('Exited tenant context.');
                exit(0);
            });
        }
        
        while (true) {
            try {
                // Read command from user
                $input = $this->readCommand($command, $tenantName);
                
                if ($input === false || strtolower(trim($input)) === 'exit') {
                    break;
                }

                $input = trim($input);
                
                if (empty($input)) {
                    continue;
                }

                // Handle signal processing if available
                if (function_exists('pcntl_signal_dispatch')) {
                    pcntl_signal_dispatch();
                }

                // Execute command in tenant context
                $this->executeCommand($command, $input);
                
            } catch (\Exception $e) {
                $message = $e->getMessage();
                if (strpos($message, 'Interrupted') !== false || 
                    strpos($message, 'SIGINT') !== false ||
                    $message === '') {
                    // Ctrl+C or Ctrl+X pressed
                    break;
                }
                $command->error('Error: ' . $message);
            }
        }

        $command->info('Exited tenant context.');
    }

    /**
     * Read command from user input.
     *
     * @param  Command  $command
     * @param  string  $tenantName
     * @return string|false
     */
    protected function readCommand(Command $command, string $tenantName)
    {
        // Use readline if available for better control
        if (function_exists('readline')) {
            $prompt = "tenant:{$tenantName}> ";
            $line = readline($prompt);
            
            if ($line === false) {
                return false;
            }
            
            if (trim($line) !== '') {
                readline_add_history($line);
            }
            
            return $line;
        }

        // Fallback to ask method
        return $command->ask("tenant:{$tenantName}> ");
    }

    /**
     * Execute a command in tenant context.
     *
     * @param  Command  $command
     * @param  string  $input
     * @return void
     */
    protected function executeCommand(Command $command, string $input)
    {
        // Initialize tenant context
        $this->resolver->initializeTenant($this->tenant);

        try {
            // Parse the command
            $parts = $this->parseCommand($input);
            $artisanCommand = $parts['command'];
            $arguments = $parts['arguments'];
            $options = $parts['options'];

            // Remove --tenant option if present (we're already in tenant context)
            unset($options['tenant']);

            // Convert arguments array to associative array if needed
            $commandArguments = [];
            if (!empty($arguments)) {
                // For commands that expect positional arguments
                $commandArguments = $arguments;
            }

            // Merge with options
            $allOptions = array_merge($commandArguments, $options);

            // Execute the artisan command
            $exitCode = Artisan::call($artisanCommand, $allOptions);
            
            // Output the result
            $output = Artisan::output();
            if (!empty(trim($output))) {
                $command->line($output);
            }

            if ($exitCode !== 0) {
                $command->warn("Command exited with code: {$exitCode}");
            }

        } catch (\Exception $e) {
            $command->error('Command failed: ' . $e->getMessage());
        } finally {
            // Cleanup tenant context if needed
            $this->resolver->cleanupTenant();
        }
    }

    /**
     * Parse command string into command, arguments, and options.
     *
     * @param  string  $input
     * @return array
     */
    protected function parseCommand(string $input): array
    {
        // Improved command parser that handles quoted strings
        $parts = [];
        $current = '';
        $inQuotes = false;
        $quoteChar = '';

        for ($i = 0; $i < strlen($input); $i++) {
            $char = $input[$i];
            
            if (($char === '"' || $char === "'") && ($i === 0 || $input[$i - 1] !== '\\')) {
                if (!$inQuotes) {
                    $inQuotes = true;
                    $quoteChar = $char;
                } elseif ($char === $quoteChar) {
                    $inQuotes = false;
                    $quoteChar = '';
                } else {
                    $current .= $char;
                }
            } elseif ($char === ' ' && !$inQuotes) {
                if ($current !== '') {
                    $parts[] = $current;
                    $current = '';
                }
            } else {
                $current .= $char;
            }
        }
        
        if ($current !== '') {
            $parts[] = $current;
        }
        
        if (empty($parts)) {
            return ['command' => '', 'arguments' => [], 'options' => []];
        }

        $command = array_shift($parts);
        $arguments = [];
        $options = [];

        foreach ($parts as $part) {
            // Remove quotes if present
            $part = trim($part, '"\'');
            
            if (strpos($part, '--') === 0) {
                // It's an option
                $optionParts = explode('=', substr($part, 2), 2);
                $optionName = $optionParts[0];
                $optionValue = $optionParts[1] ?? true;
                
                // Remove quotes from option value
                $optionValue = trim($optionValue, '"\'');
                
                if ($optionValue === 'true' || $optionValue === true) {
                    $optionValue = true;
                } elseif ($optionValue === 'false' || $optionValue === false) {
                    $optionValue = false;
                } elseif (is_numeric($optionValue)) {
                    $optionValue = strpos($optionValue, '.') !== false ? (float) $optionValue : (int) $optionValue;
                }
                
                $options[$optionName] = $optionValue;
            } else {
                // It's an argument
                $arguments[] = $part;
            }
        }

        return [
            'command' => $command,
            'arguments' => $arguments,
            'options' => $options,
        ];
    }
}
