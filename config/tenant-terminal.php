<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Tenant Terminal Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can configure the settings for the tenant terminal package.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Tenant Model
    |--------------------------------------------------------------------------
    |
    | If you're using a custom tenant package, specify the tenant model class.
    | The package will auto-detect stancl/tenancy and spatie/laravel-multitenancy.
    |
    */
    'tenant_model' => env('TENANT_MODEL', null),

    /*
    |--------------------------------------------------------------------------
    | Custom Initialization Method
    |--------------------------------------------------------------------------
    |
    | If you have a custom method to initialize tenant context, specify it here.
    | This method will be called on the tenant instance.
    |
    */
    'initialize_method' => env('TENANT_INITIALIZE_METHOD', null),

    /*
    |--------------------------------------------------------------------------
    | Custom Cleanup Method
    |--------------------------------------------------------------------------
    |
    | If you have a custom method to cleanup tenant context, specify it here.
    | This should be a function name (not a method on tenant instance).
    |
    */
    'cleanup_method' => env('TENANT_CLEANUP_METHOD', null),
];
