<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\ServiceProvider;
use App\Http\Requests\Api\V1;
class ApiController extends Controller
{
    use ApiResponses;

    protected $policyClass;

    public function include(string $relationship): bool
    {

        $param = request()->get("include"); // get the include query parameter from url
        if (!isset($param)) {
            return false;
        }
        $includeValues = explode(",", strtolower($param));
        return in_array(strtolower($relationship), $includeValues);

    }

    public function isAble($ability, $targetModel)
    {
        return $this->authorize($ability, [$targetModel, $this->policyClass]);
    }
}
