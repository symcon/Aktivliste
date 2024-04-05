<?php

declare(strict_types=1);
include_once __DIR__ . '/stubs/Validator.php';
class AktivlisteValidationTest extends TestCaseSymconValidation
{
    public function testValidateAktivliste(): void
    {
        $this->validateLibrary(__DIR__ . '/..');
    }
    public function testValidateActiveListModule(): void
    {
        $this->validateModule(__DIR__ . '/../ActiveList');
    }
}