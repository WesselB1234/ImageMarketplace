<?php 

use App\Models\Dtos\ErrorDto;
use App\Exception\BadRequestException;
use App\Exception\ConflictException;
use App\Exception\ForbiddenException;
use App\Exception\InvalidAuthTokenException;
use App\Exception\NotAuthorizedException;
use App\Exception\NotFoundException;
use App\Exception\MethodNotAllowedException;
use App\Exception\UploadException;
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
            $e instanceof InvalidArgumentException => $this->handleInvalidArgumentException($e),
            $e instanceof UploadException => $this->handleUploadException($e),
            $e instanceof ExpiredException => $this->handleExpiredException($e),
            $e instanceof SignatureInvalidException => $this->handleSignatureInvalidException($e),
            $e instanceof ForbiddenException => $this->handleForbiddenException($e),
            $e instanceof handleMethodNotAllowedException => $this->handleMethodNotAllowedException($e),
            default => $this->handleGeneric($e)
        };
    }

    private function displayErrorJson(int $errorCode, string $message)
    {
        http_response_code($errorCode);
        echo json_encode(new ErrorDto($message), JSON_PRETTY_PRINT);
        exit;
    }

    private function handleGeneric(Throwable $e)
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

    private function handleInvalidArgumentException(InvalidArgumentException $e)
    {
        $this->displayErrorJson(400, $e->getMessage());
    }

    private function handleUploadException(UploadException $e)
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

    private function handleForbiddenException(ForbiddenException $e) 
    {
        $this->displayErrorJson(403, $e->getMessage());
    }

    private function handleMethodNotAllowedException(MethodNotAllowedException $e) 
    {
        $this->displayErrorJson(405, $e->getMessage());
    }
}