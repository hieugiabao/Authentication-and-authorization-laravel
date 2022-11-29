<?php

namespace App\Roles\Repository\Interface;

use App\Permissions\Permission;
use Jsdecena\Baserepo\BaseRepositoryInterface;
use Illuminate\Support\Collection;
use App\Roles\Role;

interface RoleRepositoryInterface extends BaseRepositoryInterface
{
  public function createRole(array $data): Role;

  public function listRoles(string $order = 'id', string $sort = 'desc'): Collection;

  public function findRoleById(int $id);

  public function updateRole(array $data, $id): Role;

  public function deleteRoleById(): bool;

  public function attachToPermission(Permission $permission);

  public function attachToPermissions(...$permissions);

  public function syncPermissions(array $id);

  public function listPermissions(): Collection;
}
