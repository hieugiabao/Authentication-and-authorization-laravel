<?php

namespace App\Roles\Repository;

use Jsdecena\Baserepo\BaseRepository;
use App\Roles\Role;
use App\Roles\Repository\Interface\RoleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use App\Roles\Repository\Exceptions\CreateRoleErrorException;
use App\Roles\Repository\Exceptions\UpdateRoleErrorException;
use App\Roles\Repository\Exceptions\DeleteRoleErrorException;
use App\Roles\Repository\Exceptions\RoleNotFoundErrorException;
use App\Permissions\Permission;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
  /**
   * @var Role
   */
  protected $model;
  /**
   * RoleRepository constructor.
   * @param Role $role
   */
  public function __construct(Role $role)
  {
    parent::__construct($role);
    $this->model = $role;
  }
  /**
   * List all Roles
   * @param string $order
   * @param string $sort
   * @return Collection
   */
  public function listRoles(string $order = 'id', string $sort = 'desc'): Collection
  {
    return $this->all(['*'], $order, $sort);
  }
  /**
   * Create a new role
   * @param array $data
   * @return Role
   * @throws CreateRoleErrorException
   */
  public function createRole(array $data): Role
  {
    try {
      $role = new Role($data);
      $role->save();
      return $role;
    } catch (QueryException $e) {
      throw new CreateRoleErrorException($e);
    }
  }

  /**
   * Update a role
   * @param array $data
   * @param mixed $id
   *
   * @return bool
   * @throws UpdateRoleErrorException
   */
  public function updateRole(array $data, $id): Role
  {
    try {
      $role = $this->findRoleById($id);
      $role->update($data);
      return $role;
    } catch (QueryException $e) {
      throw new UpdateRoleErrorException($e);
    }
  }
  /**
   * Delete a role
   * @return bool
   * @throws DeleteRoleErrorException
   */
  public function deleteRole(): bool
  {
    try {
      return $this->delete();
    } catch (QueryException $e) {
      throw new DeleteRoleErrorException($e);
    }
  }
  /**
   * Attach permission
   * @param Permission $permission
   */
  public function attachToPermission(Permission $permission)
  {
    $this->model->attachPermission($permission);
  }
  /**
   * Attach permissions
   * @param Permission ... $permissions
   */
  public function attachToPermissions(...$permissions)
  {
    $this->model->attachPermissions($permissions);
  }
  /**
   * Sync permission
   * @param array $ids
   */
  public function syncPermissions(array $ids)
  {
    $this->model->syncPermissions($ids);
  }
  /**
   * List all permissions
   * @return Collection
   */
  public function listPermissions(): Collection
  {
    return $this->model->permissions()->get();
  }
  /**
   * @param int $id
   *
   * @return Role
   * @throws RoleNotFoundErrorException
   */
  public function findRoleById(int $id): Role
  {
    try {
      return $this->findOneOrFail($id);
    } catch (QueryException $e) {
      throw new RoleNotFoundErrorException($e);
    }
  }
  /**
   * @return bool
   * @throws DeleteRoleErrorException
   */
  public function deleteRoleById(): bool
  {
    try {
      return $this->delete();
    } catch (QueryException $e) {
      throw new DeleteRoleErrorException($e);
    }
  }
}
