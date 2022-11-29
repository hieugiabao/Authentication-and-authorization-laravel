<?php

namespace App\Permissions\Repository\Interface;

use Jsdecena\Baserepo\BaseRepositoryInterface;
use App\Permissions\Permission;
use Illuminate\Support\Collection;

interface PermissionRepositoryInterface extends BaseRepositoryInterface
{
  public function createPermission(array $data): Permission;

  public function findPermissionById(int $id): Permission;

  public function updatePermission(array $data, mixed $id): Permission;

  public function deletePermissionById(): bool;

  public function listPermissions($columns = array('*'), string $orderBy = 'id', string $sortBy = 'asc'): Collection;
}
