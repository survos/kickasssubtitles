<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace App\Http\ViewComposers;

use App\Enums\Menu;
use App\Enums\Route;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\View\View;
use KickAssSubtitles\Processor\TaskRepositoryInterface;
use KickAssSubtitles\Processor\TaskStatus;
use KickAssSubtitles\Processor\TaskType;
use KickAssSubtitles\Support\UserInterface;
use Lavary\Menu\Menu as LavaryMenu;
use Mcamara\LaravelLocalization\LaravelLocalization;
use function Safe\sprintf;

/**
 * Class LayoutComposer.
 */
class LayoutComposer
{
    /**
     * @var LaravelLocalization|null
     */
    protected static $localization;

    /**
     * @var LavaryMenu|null
     */
    protected static $menu;

    public static function getLocalization(): LaravelLocalization
    {
        if (null !== static::$localization) {
            return static::$localization;
        }
        static::$localization = app(LaravelLocalization::class);

        return static::$localization;
    }

    public static function getMenu(): LavaryMenu
    {
        if (null !== static::$menu) {
            return static::$menu;
        }

        /** @var TaskRepositoryInterface $taskRepository */
        $taskRepository = app(TaskRepositoryInterface::class);

        $renderCount = function (TaskType $type, TaskStatus $status) use ($taskRepository) {
            $count = $taskRepository->findCount($type, $status);
            if ($count > 0) {
                return sprintf(
                    '&nbsp;<b title="%s">%s</b>',
                    __('messages.pending_tasks'),
                    $count
                );
            }

            return '';
        };

        $menuCollection = app(LavaryMenu::class);
        $localization = static::getLocalization();
        $menuCollection->makeOnce(Menu::MAIN, function ($menu) use ($renderCount) {
            $menu
                ->add(
                    '<em>'.(\is_string(__('messages.search')) ? __('messages.search') : '').'</em>'.
                    $renderCount(TaskType::SUBTITLE_SEARCH(), TaskStatus::PENDING()),
                    ['route' => Route::SEARCH]
                )
                ->prepend('<i class="fa fa-search" aria-hidden="true"></i>')
                ->append('<span>'.(\is_string(__('messages.search_hint')) ? __('messages.search_hint') : '').'</span>')
            ;
            $menu
                ->add(
                    '<em>'.(\is_string(__('messages.convert')) ? __('messages.convert') : '').'</em>'.
                    $renderCount(TaskType::SUBTITLE_CONVERSION(), TaskStatus::PENDING()),
                    ['route' => Route::CONVERT]
                )
                ->prepend('<i class="fa fa-exchange" aria-hidden="true"></i>')
                ->append('<span>'.(\is_string(__('messages.convert_hint')) ? __('messages.convert_hint') : '').'</span>')
            ;
            $menu
                ->add('<em>'.(\is_string(__('messages.history')) ? __('messages.history') : '').'</em>', ['route' => Route::HISTORY])
                ->prepend('<i class="fa fa-history" aria-hidden="true"></i>')
                ->append('<span>'.(\is_string(__('messages.history_hint')) ? __('messages.history_hint') : '').'</span>')
            ;
            $menu
                ->add('<em>'.(\is_string(__('messages.browse')) ? __('messages.browse') : '').'</em>', ['route' => Route::MOVIES])
                ->prepend('<i class="fa fa-film" aria-hidden="true"></i>')
                ->append('<span>'.(\is_string(__('messages.browse_hint')) ? __('messages.browse_hint') : '').'</span>')
            ;
        });

        $menuCollection->makeOnce(Menu::AUTH, function ($menu) {
            /** @var StatefulGuard $auth */
            $auth = auth();
            if ($auth->check()) {
                /** @var UserInterface $user */
                $user = $auth->user();
                $menu
                    ->add((\is_string(__('messages.logged_as')) ? __('messages.logged_as') : '').' ', ['route' => Route::HISTORY])
                    ->append('<em>'.$user->getUsername().'</em>')
                    ->prepend('<i class="fa fa-user" aria-hidden="true"></i>')
                ;
                $menu
                    ->add(__('messages.logout'), ['route' => Route::LOGOUT])
                    ->prepend('<i class="fa fa-sign-out" aria-hidden="true"></i>')
                    ->after(sprintf('<form id="logout-form" action="%s" method="post" style="display:none">%s</form>', route('logout'), csrf_field()))
                    ->link->attr(['onclick' => 'event.preventDefault();document.getElementById("logout-form").submit()'])
                ;
            } else {
                $menu
                    ->add(__('messages.register'), ['route' => Route::REGISTER])
                    ->prepend('<i class="fa fa-pencil" aria-hidden="true"></i>')
                ;
                $menu
                    ->add(__('messages.login'), ['route' => Route::LOGIN])
                    ->prepend('<i class="fa fa-sign-in" aria-hidden="true"></i>')
                ;
            }
        });

        $menuCollection->makeOnce(Menu::LANG, function ($menu) use ($localization) {
            foreach ($localization->getSupportedLocales() as $code => $properties) {
                $code = (string) $code;
                $flagCode = ('en' === $code) ? 'gb' : $code;
                $active = ($code === $localization->getCurrentLocale()) ? true : false;
                $item = $menu
                    ->add($code, $localization->getLocalizedURL($code))
                    ->link->attr([
                        'rel' => 'alternate',
                        'hreflang' => $code,
                        'title' => $properties['native'],
                        'class' => 'flag-icon-background flag-icon-'.$flagCode,
                    ])
                ;
                if ($active) {
                    $item->active();
                }
            }
        });
        static::$menu = $menuCollection;

        return static::$menu;
    }

    public function compose(View $view): void
    {
        $view->with('_menu', static::getMenu());
        $view->with('_localization', static::getLocalization());
    }
}
