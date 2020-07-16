<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

use Hashids\HashidsException;
use MyCLabs\Enum\Enum;
use function Safe\sprintf;
use Vinkla\Hashids\HashidsManager;

if (!\function_exists('hashid_encode')) {
    /**
     * @param int $value
     *
     * @return string
     */
    function hashid_encode(int $value): string
    {
        return app(HashidsManager::class)->encode($value);
    }
}

if (!\function_exists('hashid_decode')) {
    /**
     * @param string $value
     *
     * @return int
     */
    function hashid_decode(string $value): int
    {
        $ids = app(HashidsManager::class)->decode($value);

        if (empty($ids)) {
            throw new HashidsException(sprintf('Unable to decode value [%s]', $value));
        }

        return \current($ids);
    }
}

if (!\function_exists('form_select')) {
    /**
     * @param array|string $list
     * @param array        $options
     *
     * @return string
     */
    function form_select($list, array $options = [])
    {
        if (\is_string($list)) {
            /** @var Enum $enumClass */
            $enumClass = $list;
            $list = [];
            foreach ($enumClass::values() as $enum) {
                if (isset($options['label']) && \is_callable($options['label'])) {
                    $list[$enum->getValue()] = $options['label']($enum);
                } else {
                    $list[$enum->getValue()] = $enum->getKey();
                }
            }
        }
        \natcasesort($list);
        if (isset($options['empty'])) {
            $list = $options['empty'] + $list;
        }
        $listHtml = '';
        foreach ($list as $k => $v) {
            $attrs = '';
            if (empty($k)) {
                $attrs .= ' :value="null"';
            }
            if (isset($options['selected']) && $options['selected'] === $k) {
                $attrs .= ' selected="selected"';
            }
            $listHtml .= \sprintf(
                '<option value="%s"%s>%s</option>',
                $k,
                $attrs,
                $v
            );
        }
        $attrsHtml = '';
        if (isset($options['attrs'])) {
            foreach ($options['attrs'] as $k => $v) {
                $attrsHtml .= \sprintf(' %s="%s"', $k, $v);
            }
        }

        return \sprintf('<select%s>%s</select>', $attrsHtml, $listHtml);
    }
}
