<?php

namespace App\Controllers;

use App\Models\Exceptions\BadRequestException;

class ApiController
{
    private function throwIfInputDataIsInvalid(array $data, array $requiredParams) 
    {
        foreach($requiredParams as $requiredParam) {
            if (empty($data[$requiredParam])) {
                throw new BadRequestException("Input data does not contain the ".$requiredParam." parameter.");
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
