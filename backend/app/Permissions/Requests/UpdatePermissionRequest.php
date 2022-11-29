<?php

namespace App\Permissions\Requests;

use App\Base\BaseFormRequest;

class UpdatePermissionRequest extends BaseFormRequest
{
  public function rules()
  {
    return [
      'display_name' => 'required|string',
      'name' => 'unique:permissions,name',
    ];
  }
}
