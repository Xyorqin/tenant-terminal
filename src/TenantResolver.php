<?php

namespace Xyorqin\TenantTerminal;

class TenantResolver
{
    /**
     * Get all available tenants.
     *
     * @return array
     */
    public function getAllTenants(): array
    {
        // Try stancl/tenancy package first
        if (class_exists(\Stancl\Tenancy\Tenancy::class)) {
            return $this->getStanclTenants();
        }

        // Try spatie/laravel-multitenancy
        if (class_exists(\Spatie\Multitenancy\Models\Tenant::class)) {
            return $this->getSpatieTenants();
        }

        // Try custom tenant model from config
        $tenantModel = config('tenant-terminal.tenant_model');
        if ($tenantModel && class_exists($tenantModel)) {
            return $this->getCustomTenants($tenantModel);
        }

        return [];
    }

    /**
     * Get tenants from stancl/tenancy package.
     *
     * @return array
     */
    protected function getStanclTenants(): array
    {
        $tenantModel = config('tenancy.tenant_model', \Stancl\Tenancy\Database\Models\Tenant::class);
        
        if (!class_exists($tenantModel)) {
            return [];
        }

        return $tenantModel::all()->all();
    }

    /**
     * Get tenants from spatie/laravel-multitenancy package.
     *
     * @return array
     */
    protected function getSpatieTenants(): array
    {
        $tenantModel = \Spatie\Multitenancy\Models\Tenant::class;
        return $tenantModel::all()->all();
    }

    /**
     * Get tenants from custom tenant model.
     *
     * @param  string  $tenantModel
     * @return array
     */
    protected function getCustomTenants(string $tenantModel): array
    {
        return $tenantModel::all()->all();
    }

    /**
     * Get tenant ID.
     *
     * @param  mixed  $tenant
     * @return string
     */
    public function getTenantId($tenant): string
    {
        // Try common ID fields
        if (isset($tenant->id)) {
            return (string) $tenant->id;
        }

        if (method_exists($tenant, 'getKey')) {
            return (string) $tenant->getKey();
        }

        // Try domain for stancl/tenancy
        if (isset($tenant->domains) && $tenant->domains->isNotEmpty()) {
            return $tenant->domains->first()->domain;
        }

        if (isset($tenant->domain)) {
            return $tenant->domain;
        }

        return 'unknown';
    }

    /**
     * Get tenant name/identifier for display.
     *
     * @param  mixed  $tenant
     * @return string
     */
    public function getTenantName($tenant): string
    {
        // Try name field
        if (isset($tenant->name)) {
            return $tenant->name;
        }

        // Try id field
        if (isset($tenant->id)) {
            return "Tenant #{$tenant->id}";
        }

        // Try domain
        if (isset($tenant->domains) && $tenant->domains->isNotEmpty()) {
            return $tenant->domains->first()->domain;
        }

        if (isset($tenant->domain)) {
            return $tenant->domain;
        }

        return 'Unknown Tenant';
    }

    /**
     * Initialize tenant context.
     *
     * @param  mixed  $tenant
     * @return void
     */
    public function initializeTenant($tenant): void
    {
        // Initialize stancl/tenancy
        if (class_exists(\Stancl\Tenancy\Tenancy::class)) {
            tenancy()->initialize($tenant);
            return;
        }

        // Initialize spatie/laravel-multitenancy
        if (class_exists(\Spatie\Multitenancy\Models\Tenant::class)) {
            $tenant->makeCurrent();
            return;
        }

        // Custom initialization
        $initializeMethod = config('tenant-terminal.initialize_method');
        if ($initializeMethod && method_exists($tenant, $initializeMethod)) {
            $tenant->$initializeMethod();
            return;
        }

        // Set tenant in container for custom usage
        app()->instance('current_tenant', $tenant);
    }

    /**
     * Cleanup tenant context.
     *
     * @return void
     */
    public function cleanupTenant(): void
    {
        // Cleanup stancl/tenancy
        if (class_exists(\Stancl\Tenancy\Tenancy::class)) {
            tenancy()->end();
            return;
        }

        // Cleanup spatie/laravel-multitenancy
        if (class_exists(\Spatie\Multitenancy\Models\Tenant::class)) {
            \Spatie\Multitenancy\Models\Tenant::forgetCurrent();
            return;
        }

        // Custom cleanup
        $cleanupMethod = config('tenant-terminal.cleanup_method');
        if ($cleanupMethod && function_exists($cleanupMethod)) {
            $cleanupMethod();
            return;
        }

        // Remove tenant from container
        app()->forgetInstance('current_tenant');
    }
}
