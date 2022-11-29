<?php

namespace App\Roles\Requests;

use App\Base\BaseFormRequest;

class GetAllRolesRequest extends BaseFormRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'per_page' => 'integer'
    ];
  }
}
