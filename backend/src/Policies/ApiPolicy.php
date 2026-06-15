<?php 

namespace App\Policies;

use App\Exception\BadRequestException;
use App\Exception\NotFoundException;

class ApiPolicy
{
    public function enforceRequiredParams(array $data, array $requiredParams): void
    {
        if (empty($data)) {
            throw new BadRequestException("Invalid JSON.");
        }

        foreach ($requiredParams as $param) {
            if (!array_key_exists($param, $data)) {
                throw new NotFoundException("Missing required param: $param.");
            }
        }
    }
}
