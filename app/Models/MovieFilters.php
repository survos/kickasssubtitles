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

use Illuminate\Http\Request;
use KickAssSubtitles\Support\FiltersInterface;
use KickAssSubtitles\Support\FiltersTrait;

/**
 * Class MovieFilters.
 */
class MovieFilters implements FiltersInterface
{
    use FiltersTrait;

    /**
     * @var bool
     */
    protected $recentlySearched = false;

    /**
     * @var null|string
     */
    protected $letter = null;

    /**
     * @var Request
     */
    protected $request;

    /**
     * {@inheritdoc}
     */
    public static function createFromRequest(Request $request): FiltersInterface
    {
        return new static($request);
    }

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request->duplicate();

        $letters = range('a', 'z');
        $letters[] = 'number';

        $letter = $this->request->get('letter', null);
        if (in_array($letter, $letters, true)) {
            $this->letter = $letter;
        }
    }

    /**
     * @return string|null
     */
    public function getLetter(): ?string
    {
        return $this->letter;
    }

    /**
     * @param bool $recentlySearched
     *
     * @return self
     */
    public function setRecentlySearched(bool $recentlySearched): self
    {
        $this->recentlySearched = $recentlySearched;

        return $this;
    }

    /**
     * @return bool
     */
    public function getRecentlySearched(): bool
    {
        return $this->recentlySearched;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $html = '<ul class="app-filter-alphabet">';
        if ('number' === $this->letter) {
            $html .= '<li class="active"><span>0-9</span></li>';
        } else {
            $html .= '<li><a href="'.$this->request->url().'?letter=number">0-9</a></li>';
        }
        foreach (range('a', 'z') as $letter) {
            if ($this->letter === $letter) {
                $html .= '<li class="active"><span>'.$letter.'</span></li>';
            } else {
                $html .= '<li><a href="'.$this->request->url().'?letter='.$letter.'">'.$letter.'</a></li>';
            }
        }
        $html .= '</ul>';

        return $html;
    }
}
