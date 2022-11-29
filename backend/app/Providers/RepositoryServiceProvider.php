<?php

namespace App\Providers;

use App\Roles\Repository\Interface\RoleRepositoryInterface;
use App\Roles\Repository\RoleRepository;
use App\Permissions\Repository\Interface\PermissionRepositoryInterface;
use App\Permissions\Repository\PermissionRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
  public function register()
  {
    $this->app->bind(
      RoleRepositoryInterface::class,
      RoleRepository::class
    );

    $this->app->bind(
      PermissionRepositoryInterface::class,
      PermissionRepository::class
    );
  }
}
