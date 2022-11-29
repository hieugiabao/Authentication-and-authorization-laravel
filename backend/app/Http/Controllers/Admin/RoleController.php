<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Permissions\Repository\Interface\PermissionRepositoryInterface;
use App\Roles\Repository\Exceptions\DeleteRoleErrorException;
use App\Roles\Repository\Exceptions\RoleNotFoundErrorException;
use App\Roles\Repository\Exceptions\UpdateRoleErrorException;
use App\Roles\Repository\Interface\RoleRepositoryInterface;
use App\Roles\Repository\RoleRepository;
use App\Roles\Requests\CreateRoleRequest;
use App\Roles\Requests\GetAllRolesRequest;
use App\Roles\Requests\UpdateRoleRequest;

class RoleController extends Controller
{
  /**
   * @var RoleRepositoryInterface
   */
  private $roleRepo;

  /**
   * @var PermissionRepositoryInterface
   */
  private $permissionRepo;

  /**
   * RoleController constructor.
   *
   * @param RoleRepositoryInterface $roleRepo
   * @param PermissionRepositoryInterface $permissionRepo
   */
  public function __construct(
    RoleRepositoryInterface $roleRepo,
    PermissionRepositoryInterface $permissionRepo
  ) {
    $this->roleRepo = $roleRepo;
    $this->permissionRepo = $permissionRepo;
  }

  /**
   * Create a new role
   *
   * @param CreateRoleRequest
   */
  public function create(CreateRoleRequest $request)
  {
    $this->roleRepo->createRole($request->except('_method', '_token'));

    return response()->json([
      'message' => 'Role successfully created'
    ], 201);
  }

  /**
   * Get pagination of roles
   *
   * @param GetAllRolesRequest $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function getAllRoles(GetAllRolesRequest $request)
  {
    $perPage = $request->input('per_page', 10);
    $list = $this->roleRepo->listRoles('name', 'asc')->all();
    $roles = $this->roleRepo->paginateArrayResults($list, $perPage);
    return response()->json($roles, 200);
  }

  /**
   * Get a role
   *
   * @param int $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function getRole(int $id)
  {
    try {
      $role = $this->roleRepo->findRoleById($id);
      return response()->json($role, 200);
    } catch (RoleNotFoundErrorException $e) {
      return response()->json([
        'error' => 'Role not found'
      ], 404);
    }
  }

  /**
   * Update a role
   *
   * @param int $id
   * @param UpdateRoleRequest $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function update(UpdateRoleRequest $request, int $id)
  {
    try {
      $role = $this->roleRepo->findRoleById($id);

      if ($request->has('permissions')) {
        $roleRepo = new RoleRepository($role);
        $roleRepo->syncPermissions($request->input('permissions'));
      }

      $new_role = $this->roleRepo->updateRole($request->except('_method', '_token'), $id);

      return response()->json($role, 200);
    } catch (RoleNotFoundErrorException $e) {
      return response()->json([
        'error' => 'Role not found'
      ], 404);
    } catch (UpdateRoleErrorException $e) {
      return response()->json([
        'error' => $e->getMessage(),
      ], 500);
    }
  }
}
