<?php

declare(strict_types=1);

namespace App\Tests\ArgumentResolver;

use App\ArgumentResolver\RequestFileArgumentResolver;
use App\Attribute\RequestFile;
use App\Exception\ValidationException;
use App\Tests\AbstractTestCase;
use stdClass;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestFileArgumentResolverTest extends AbstractTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validator = $this->createMock(ValidatorInterface::class);
    }

    public function testSupports(): void
    {
        $meta = new ArgumentMetadata('some', null, false, false, null, false, [
            new RequestFile('file', []),
        ]);

        $this->assertTrue($this->createResolver()->supports(new Request(), $meta));
    }

    public function testNotSupports(): void
    {
        $meta = new ArgumentMetadata('some', null, false, false, null);

        $this->assertFalse($this->createResolver()->supports(new Request(), $meta));
    }

    public function testResolveThrowsWhenValidationFails(): void
    {
        $this->expectException(ValidationException::class);

        $file = new UploadedFile('path', 'field', null, UPLOAD_ERR_NO_FILE, true);
        $request = new Request();
        $request->files->add(['field' => $file]);

        $meta = new ArgumentMetadata('some', stdClass::class, false, false, null, false, [
            new RequestFile('field', []),
        ]);

        $this->validator->expects($this->once())
            ->method('validate')
            ->with($file, [])
            ->willReturn(new ConstraintViolationList([
                new ConstraintViolation('error', null, [], null, 'some', null),
            ]));

        $this->createResolver()->resolve($request, $meta)->next();
    }

    public function testResolveThrowsWhenConstraintFails(): void
    {
        $this->expectException(ValidationException::class);

        $constraints = [new NotNull()];
        $file = new UploadedFile('path', 'field', null, UPLOAD_ERR_NO_FILE, true);
        $request = new Request();
        $request->files->add(['field' => $file]);

        $meta = new ArgumentMetadata('some', stdClass::class, false, false, null, false, [
            new RequestFile('field', $constraints),
        ]);

        $this->validator->expects($this->once())
            ->method('validate')
            ->with($file, $constraints)
            ->willReturn(new ConstraintViolationList([
                new ConstraintViolation('error', null, [], null, 'some', null),
            ]));

        $this->createResolver()->resolve($request, $meta)->next();
    }

    public function testResolve(): void
    {
        $file = new UploadedFile('path', 'field', null, UPLOAD_ERR_NO_FILE, true);
        $request = new Request();
        $request->files->add(['field' => $file]);

        $meta = new ArgumentMetadata('some', stdClass::class, false, false, null, false, [
            new RequestFile('field', []),
        ]);

        $this->validator->expects($this->once())
            ->method('validate')
            ->with($file, [])
            ->willReturn(new ConstraintViolationList([]));

        $actual = $this->createResolver()->resolve($request, $meta);

        $this->assertEquals($file, $actual->current());
    }

    private function createResolver(): RequestFileArgumentResolver
    {
        return new RequestFileArgumentResolver($this->validator);
    }
}
