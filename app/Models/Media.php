<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace App\Models;

use KickAssSubtitles\Support\ModelInterface;
use KickAssSubtitles\Support\ModelTrait;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Media.
 */
class Media extends BaseMedia implements ModelInterface
{
    use ModelTrait;

    const MODEL = 'model';

    const NAME = 'name';

    const FILE_NAME = 'file_name';

    const MIME_TYPE = 'mime_type';

    const DISK = 'disk';

    const SIZE = 'size';

    const COLLECTION_NAME = 'collection_name';

    const MANIPULATIONS = 'manipulations';

    const CUSTOM_PROPERTIES = 'custom_properties';

    const RESPONSIVE_IMAGES = 'responsive_images';

    const ORDER_COLUMN = 'order_column';
}
