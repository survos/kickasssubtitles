@extends('layouts.master')

<?php
$title = __('messages.convert');
$options = (new KickAssSubtitles\Subtitle\SubtitleConversionOptions([
    KickAssSubtitles\Subtitle\SubtitleInterface::LANGUAGE => $_localization->getCurrentLocale(),
]))->toArray();
?>

@section('title', $title)
@section('heading', $title)
@section('content.class', 'content _padding-reset')

@section('content')

    <div id="app" v-cloak>
        <converter
                :options='{!! json_encode($options) !!}'
                endpoint="{{ route(App\Enums\Route::CONVERT_CREATE) }}"
                limit="{{ config('app.conversion.limit') }}"
                filesize="{{ config('app.conversion.filesize') }}">
        </converter>
    </div>

    <script type="text/x-template" id="converter-template">
        <div>

            <div class="app-files">
                <div class="input">
                    <input type="file" multiple="multiple" v-on:change="change">
                </div>
            </div>

            <div class="app-form-field -submit" v-if="items.length">
                <button type="submit" class="app-button -inverted" v-on:click.stop.prevent="submit">
                    <span v-if="!working">{{ __('messages.convert') }}</span>
                    <span v-if="working">{{ __('messages.working') }}...</span>
                </button>
            </div>

            <table class="app-table" v-if="items.length">
                <thead>
                    <tr>
                        <td colspan="2">
                            {{ __('messages.limit_info') }} <strong>@{{ limit }}</strong>
                            {{ trans_choice('plurals.files', config('app.conversion.limit')) }}
                            ({{ __('messages.limit_left') }}:&nbsp;<strong>@{{ items_left }}</strong>)
                        </td>
                        <td>
                            {{ __('messages.language') }}<br>
                            <?php
                            echo form_select(KickAssSubtitles\Language\Language::class, [
                                'empty' => ['' => __('messages.choose')],
                                'attrs' => ['v-on:change' => "bulkSet(\$event, 'language')"],
                                'selected' => $options[KickAssSubtitles\Subtitle\SubtitleInterface::LANGUAGE],
                                'label' => function (KickAssSubtitles\Language\Language $enum) {
                                    return __('enums.language.'.$enum->getValue());
                                },
                            ]);
                            ?>
                        </td>
                        <td>
                            {{ __('messages.input_encoding') }}<br>
                            <?php
                                echo form_select(KickAssSubtitles\Encoding\Encoding::class, [
                                    'empty' => ['' => __('messages.auto_detect')],
                                    'attrs' => ['v-on:change' => "bulkSet(\$event, 'input_encoding')"],
                                    'label' => function (KickAssSubtitles\Encoding\Encoding $enum) {
                                        return $enum->getName();
                                    },
                                ]);
                            ?>
                        </td>
                        <td>
                            {{ __('messages.output_encoding') }}<br>
                            <?php
                                echo form_select(KickAssSubtitles\Encoding\Encoding::class, [
                                    'attrs' => ['v-on:change' => "bulkSet(\$event, 'encoding')"],
                                    'selected' => $options[KickAssSubtitles\Subtitle\SubtitleInterface::ENCODING],
                                    'label' => function (KickAssSubtitles\Encoding\Encoding $enum) {
                                        return $enum->getName();
                                    },
                                ]);
                            ?>
                        </td>
                        <td>
                            {{ __('messages.output_format') }}<br>
                            <?php
                                echo form_select(KickAssSubtitles\Subtitle\SubtitleFormat::class, [
                                    'attrs' => ['v-on:change' => "bulkSet(\$event, 'format')"],
                                    'selected' => $options[KickAssSubtitles\Subtitle\SubtitleInterface::FORMAT],
                                    'label' => function (KickAssSubtitles\Subtitle\SubtitleFormat $enum) {
                                        $extensions = array_map(function ($extension) {
                                            return '.' . $extension;
                                        }, $enum->getExtensions());
                                        return sprintf('%s (%s)', $enum->getName(), implode(', ', $extensions));
                                    },
                                ]);
                            ?>
                        </td>
                        <td class="nowrap">
                            {{ __('messages.fps') }}<br>
                            <?php
                            echo form_select(KickAssSubtitles\Movie\VideoFps::class, [
                                'empty' => ['' => __('messages.ignore')],
                                'attrs' => ['v-on:change' => "bulkSet(\$event, 'fps')"],
                                'label' => function (KickAssSubtitles\Movie\VideoFps $enum) {
                                    return $enum->getValue();
                                },
                            ]);
                            ?>
                        </td>
                        <td
                            class="action"
                            v-on:click.stop.prevent="bulkRemove"
                            title="{{ __('messages.bulk_remove') }}"
                        >
                            <i class="fa fa-trash-o fa-2x" aria-hidden="true"></i>
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in items">
                        <td>
                            <div class="app-number-bullet">@{{ index + 1 }}</div>
                        </td>
                        <td class="break">
                            {{ __('messages.filename') }}<br>
                            <strong>@{{ item.filename }}</strong>
                        </td>
                        <td>
                            {{ __('messages.language') }}<br>
                            <?php
                            echo form_select(KickAssSubtitles\Language\Language::class, [
                                'empty' => ['' => __('messages.choose')],
                                'attrs' => ['v-model' => 'item.language'],
                                'label' => function (KickAssSubtitles\Language\Language $enum) {
                                    return __('enums.language.'.$enum->getValue());
                                },
                            ]);
                            ?>
                        </td>
                        <td>
                            {{ __('messages.input_encoding') }}<br>
                            <?php
                                echo form_select(KickAssSubtitles\Encoding\Encoding::class, [
                                    'empty' => ['' => __('messages.auto_detect')],
                                    'attrs' => ['v-model' => 'item.input_encoding'],
                                    'label' => function (KickAssSubtitles\Encoding\Encoding $enum) {
                                        return $enum->getName();
                                    },
                                ]);
                            ?>
                        </td>
                        <td>
                            {{ __('messages.output_encoding') }}<br>
                            <?php
                                echo form_select(KickAssSubtitles\Encoding\Encoding::class, [
                                    'attrs' => ['v-model' => 'item.encoding'],
                                    'label' => function (KickAssSubtitles\Encoding\Encoding $enum) {
                                        return $enum->getName();
                                    },
                                ]);
                            ?>
                        </td>
                        <td>
                            {{ __('messages.output_format') }}<br>
                            <?php
                                echo form_select(KickAssSubtitles\Subtitle\SubtitleFormat::class, [
                                    'attrs' => ['v-model' => 'item.format'],
                                    'label' => function (KickAssSubtitles\Subtitle\SubtitleFormat $enum) {
                                        $extensions = array_map(function ($extension) {
                                            return '.' . $extension;
                                        }, $enum->getExtensions());
                                        return sprintf('%s (%s)', $enum->getName(), implode(', ', $extensions));
                                    },
                                ]);
                            ?>
                        </td>
                        <td class="nowrap">
                            {{ __('messages.fps') }}<br>
                            <?php
                            echo form_select(KickAssSubtitles\Movie\VideoFps::class, [
                                'empty' => ['' => __('messages.ignore')],
                                'attrs' => ['v-model' => 'item.fps'],
                                'label' => function (KickAssSubtitles\Movie\VideoFps $enum) {
                                    return $enum->getValue();
                                },
                            ]);
                            ?>
                        </td>
                        <td class="action" v-on:click.stop.prevent="remove(index)" title="{{ __('messages.remove') }}">
                            <i class="fa fa-trash-o fa-2x" aria-hidden="true"></i>
                        </td>
                    </tr>
                </tbody>
            </table>

            <template v-if="!items.length">
                @component('components.empty')
                    {!! __('messages.add_files') !!}
                @endcomponent
            </template>

        </div>
    </script>

@endsection
