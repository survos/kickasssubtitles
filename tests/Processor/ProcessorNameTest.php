<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace Tests\Processor;

use KickAssSubtitles\Processor\ProcessorName;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Tests\Processor\TestClasses\LowerProcessor;

/**
 * Class ProcessorNameTest.
 */
class ProcessorNameTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testProcessorName(): void
    {
        $processorName = new ProcessorName(LowerProcessor::class);
        $this->assertEquals('Tests\Processor\TestClasses\LowerProcessor', $processorName->getName());
        $this->assertEquals('LowerProcessor', $processorName->getShortName());
        $this->assertEquals('tests.processor.test_classes.lower_processor', $processorName->getNameSnakeCase());
        $this->assertEquals('lower_processor', $processorName->getShortNameSnakeCase());
    }
}
