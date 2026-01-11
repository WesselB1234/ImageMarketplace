<?php 

namespace App\Models\Helpers;

use App\Models\Exceptions\NotFoundException;

class RequestParamValidator {

    public static function validateRequestParamId(string $rawId)
    {
        if (filter_var($rawId, FILTER_VALIDATE_INT) === false) {
            throw new NotFoundException("Content cannot be found due to the given ID not being valid");
        }
    }
}