<?php

namespace App\Roles\Requests;

use App\Base\BaseFormRequest;

class UpdateRoleRequest extends BaseFormRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'display_name' => ['required'],
      'roles' => ['array'],
      'permissions' => ['array|string'],
      'name' => ['unique:roles,name'],
    ];
  }
}
