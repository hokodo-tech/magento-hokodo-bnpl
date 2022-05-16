<?php

namespace Hokodo\BNPL\Test\Unit\Controller\OnAuthorization;

use Hokodo\BNPL\Controller\OnAuthorization\Index;
use PHPUnit\Framework\TestCase;

class IndexTest extends TestCase
{
    /**
     * @covers \Hokodo\BNPL\Controller\OnAuthorization\Index
     */
    public function testClassExists(): void
    {
        $this->assertTrue(class_exists(Index::class));
    }
}
