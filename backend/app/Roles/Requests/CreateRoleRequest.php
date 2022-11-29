<?php

namespace App\Roles\Requests;

use App\Base\BaseFormRequest;

class CreateRoleRequest extends BaseFormRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'name' => ['required', 'unique:roles', 'string']
    ];
  }
}
