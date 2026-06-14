<?php

namespace App\Controllers;

use App\Exception\BadRequestException;
use App\Exception\NotFoundException;

class ApiController
{
    private function throwIfInputDataIsInvalid(?array $data, array $requiredParams): void
    {
        if ($data === null) {
            throw new BadRequestException("Invalid JSON.");
        }

        foreach ($requiredParams as $param) {
            if (!array_key_exists($param, $data)) {
                throw new NotFoundException("Missing required param: $param.");
            }
        }
    }

    public function getDataFromInput(array $requiredParams): array
    {
        $contentType = $_SERVER["CONTENT_TYPE"];

        if (str_contains($contentType, "multipart/form-data")) {
            return array_merge($_POST, $_FILES);
        }

        $input = file_get_contents("php://input");
        $data = json_decode($input, true);

        $this->throwIfInputDataIsInvalid($data, $requiredParams);

        return $data;
    }
}
