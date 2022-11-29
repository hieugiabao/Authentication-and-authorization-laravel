<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Permissions\Exceptions\CreatePermissionErrorException;
use App\Permissions\Exceptions\PermissionNotFoundErrorException;
use App\Permissions\Exceptions\UpdatePermissionErrorException;
use App\Permissions\Repository\Interface\PermissionRepositoryInterface;
use App\Permissions\Requests\CreatePermissionRequest;
use App\Permissions\Requests\UpdatePermissionRequest;

class PermissionController extends Controller
{
  /**
   * @var PermissionRepositoryInterface
   */
  private $permRepo;

  /**
   * PermissionController constructor.
   *
   * @param PermissionRepositoryInterface $permissionRepository
   */
  public function __construct(PermissionRepositoryInterface $permissionRepository)
  {
    $this->permRepo = $permissionRepository;
  }

  /**
   * Get all permissions
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function getAllPermissions()
  {
    $permissions = $this->permRepo->listPermissions()->all();
    return response()->json($this->permRepo->paginateArrayResults($permissions, 5));
  }

  /**
   * Get a permission
   *
   * @param $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function getPermission($id)
  {
    try {
      $permission = $this->permRepo->findPermissionById($id);
      return response()->json($permission);
    } catch (PermissionNotFoundErrorException $e) {
      return response()->json(['error' => 'Permission not found'], 404);
    }
  }

  /**
   * Create a permission
   *
   * @param CreatePermissionRequest $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function create(CreatePermissionRequest $request)
  {
    try {
      $permission = $this->permRepo->createPermission($request->except('_method', '_token'));
      return response()->json($permission, 201);
    } catch (CreatePermissionErrorException $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }

  /**
   * Update a permission
   *
   * @param UpdatePermissionRequest $request
   * @param mixed $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function update(UpdatePermissionRequest $request, $id)
  {
    try {
      $permission = $this->permRepo->updatePermission($request->except('_method', '_token'), $id);

      return response()->json($permission, 200);
    } catch (PermissionNotFoundErrorException $e) {
      return response()->json(['error' => 'Permission not found'], 404);
    } catch (UpdatePermissionErrorException $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }
}
