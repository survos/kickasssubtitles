<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace Tests\Support;

use Exception;
use PHPUnit\Framework\TestCase;

/**
 * Class ObjectCastsTraitTest.
 */
class ObjectCastsTraitTest extends TestCase
{
    public function testValidScalarAttribute(): void
    {
        $model = new Model();
        $model->{Model::FORMAT} = Format::SUBRIP;

        $this->assertInstanceOf(Format::class, $model->{Model::FORMAT});
        /** @var Format $format */
        $format = $model->{Model::FORMAT};
        $this->assertEquals(Format::SUBRIP, $format->getValue());
    }

    public function testInvalidScalarAttribute(): void
    {
        $this->expectException(Exception::class);
        $model = new Model();
        $model->{Model::FORMAT} = 'xx';
    }

    public function testInvalidNullableAttribute(): void
    {
        $this->expectException(Exception::class);
        $model = new Model();
        $model->{Model::FORMAT} = null;
    }

    public function testNullableAttribute(): void
    {
        $model = new Model();
        $model->{Model::FORMAT_NULLABLE} = Format::SUBRIP;

        $this->assertInstanceOf(Format::class, $model->{Model::FORMAT_NULLABLE});

        $model->{Model::FORMAT_NULLABLE} = null;
        $this->assertEquals(null, $model->{Model::FORMAT_NULLABLE});
    }

    public function testObjectAttribute(): void
    {
        $model = new Model();
        $model->{Model::FORMAT} = new Format(Format::SUBRIP);

        $this->assertInstanceOf(Format::class, $model->{Model::FORMAT});
        $this->assertEquals(Format::SUBRIP, $model->toArray()[Model::FORMAT]);
    }

    public function testSettingOtherAttribute(): void
    {
        $model = new Model();
        $model->{Model::OPTIONS} = ['a' => 'b'];
        $this->assertEquals(['a' => 'b'], $model->{Model::OPTIONS});
    }
}
