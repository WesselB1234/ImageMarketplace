<?php

namespace App\Controllers;

use App\Policies\ApiPolicy;

class ApiController
{
    // private ApiPolicy $apiPolicy;

    // public function __construct(ApiPolicy $apiPolicy) 
    // {
    //     $this->apiPolicy = $apiPolicy;
    // }

    public function getDataFromInput(?array $requiredParams = null): array
    {
        $contentType = $_SERVER["CONTENT_TYPE"];

        if (str_contains($contentType, "multipart/form-data")) {
            return array_merge($_POST, $_FILES);
        }

        error_log(print_r($_GET, true));

        $input = file_get_contents("php://input");
        $json = json_decode($input, true) ?? [];
        $data = array_merge($_GET, $json);

        // if ($requiredParams !== null) {
        //     $this->apiPolicy->enforceRequiredParams($data, $requiredParams);
        // }

        return $data;
    }
}
