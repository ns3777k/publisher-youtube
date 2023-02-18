<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Model;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

class ErrorResponse
{
    public function __construct(private string $message, private mixed $details = null)
    {
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @OA\Property(type="object", oneOf={
     *     @OA\Schema(ref=@Model(type=ErrorDebugDetails::class)),
     *     @OA\Schema(ref=@Model(type=ErrorValidationDetails::class)),
     * })
     */
    public function getDetails(): mixed
    {
        return $this->details;
    }
}
