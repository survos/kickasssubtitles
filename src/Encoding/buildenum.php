<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

use function Safe\file_put_contents;
use function Safe\mb_encoding_aliases;
use function Safe\preg_match;

require __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

$render = function (string $template, array $variables = []) {
    \ob_start();
    \extract($variables);
    include $template;
    $contents = \ob_get_clean();

    return $contents;
};

$encodings = function () {
    $encodings = [];

    foreach (\mb_list_encodings() as $encoding) {
        if (\in_array($encoding, ['auto', 'pass'], true)) {
            continue;
        }
        $key = \strtoupper($encoding);
        $key = \str_replace('-', '_', $key);
        $key = \str_replace('#', '_', $key);
        if (1 === preg_match('/^\d/', $key)) {
            $key = 'ENCODING_'.$key;
        }
        $encodings[$key] = [
            'encoding' => $encoding,
            'aliases' => mb_encoding_aliases($encoding),
        ];
    }

    return $encodings;
};

$filename = __DIR__.DIRECTORY_SEPARATOR.'Encoding.php';
$template = __DIR__.DIRECTORY_SEPARATOR.'buildenumtemplate.txt';
$variables = [
    'encodings' => $encodings(),
];

file_put_contents($filename, "<?php\n\n".$render($template, $variables));
