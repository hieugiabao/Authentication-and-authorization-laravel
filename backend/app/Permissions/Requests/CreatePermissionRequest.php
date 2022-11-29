<?php

namespace App\Permissions\Requests;

use App\Base\BaseFormRequest;

class CreatePermissionRequest extends BaseFormRequest
{
  public function rules()
  {
    return [
      'name' => 'required|string|unique:permissions,name',
    ];
  }
}
