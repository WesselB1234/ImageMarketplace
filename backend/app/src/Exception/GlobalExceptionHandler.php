<?php 

use App\Models\Dtos\ErrorDto;
use App\Models\Exceptions\BadRequestException;
use App\Models\Exceptions\ConflictException;
use App\Models\Exceptions\InvalidAuthTokenException;
use App\Models\Exceptions\NotAuthorizedException;
use App\Models\Exceptions\NotFoundException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;

class GlobalExceptionHandler {

    function dispatch(Throwable $e)
    {
        match (true) {
            $e instanceof InvalidAuthTokenException => $this->handleInvalidAuthTokenException($e),
            $e instanceof NotAuthorizedException => $this->handleNotAuthorizedException($e),
            $e instanceof NotFoundException => $this->handleNotFoundException($e),
            $e instanceof ConflictException => $this->handleConflictException($e),
            $e instanceof BadRequestException => $this->handleBadRequestException($e),
            $e instanceof ExpiredException => $this->handleExpiredException($e),
            $e instanceof SignatureInvalidException => $this->handleSignatureInvalidException($e),
            default => $this->handleGeneric($e),
        };
    }

    private function displayErrorJson(int $errorCode, string $message)
    {
        http_response_code($errorCode);
        echo json_encode(new ErrorDto($message), JSON_PRETTY_PRINT);
        exit;
    }

    private function handleGeneric(Exception $e)
    {
        $this->displayErrorJson(500, $e->getMessage());
    } 

    private function handleInvalidAuthTokenException(InvalidAuthTokenException $e)
    {
        $this->displayErrorJson(440, $e->getMessage());
    }

    private function handleNotAuthorizedException(NotAuthorizedException $e)
    {
        $this->displayErrorJson(401, $e->getMessage());
    }

    private function handleNotFoundException(NotFoundException $e)
    {
        $this->displayErrorJson(404, $e->getMessage());
    }

    private function handleConflictException(ConflictException $e)
    {
        $this->displayErrorJson(409, $e->getMessage());
    }

    private function handleBadRequestException(BadRequestException $e)
    {
        $this->displayErrorJson(400, $e->getMessage());
    }

    private function handleExpiredException(ExpiredException $e) 
    {
        $this->displayErrorJson(440, "Your auth token has expired.");
    }

    private function handleSignatureInvalidException(SignatureInvalidException $e) 
    {
        $this->displayErrorJson(440, "Auth token signature is not valid.");
    }
}