<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace App\Providers;

use App\Enums\Environment;
use App\Models\Image;
use App\Models\Movie;
use App\Models\Subtitle;
use App\Models\Task;
use App\Models\User;
use App\Models\Video;
use App\Repositories\ImageRepository;
use App\Repositories\MovieRepository;
use App\Repositories\SubtitleRepository;
use App\Repositories\TaskRepository;
use App\Repositories\UserRepository;
use App\Repositories\VideoRepository;
use App\Services\MovieProvider;
use App\Services\SubtitleConverter;
use App\Services\SubtitleProvider;
use App\Services\TablelessSubtitleConverter;
use App\Services\TaskDownloader;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\ClientInterface as HttpClientInterface;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\ServiceProvider;
use Intervention\Image\ImageManager;
use KickAssSubtitles\Encoding\EncodingConverter;
use KickAssSubtitles\Encoding\EncodingConverterDetector;
use KickAssSubtitles\Encoding\EncodingConverterDetectorInterface;
use KickAssSubtitles\Encoding\EncodingConverterInterface;
use KickAssSubtitles\Encoding\EncodingDetector;
use KickAssSubtitles\Encoding\EncodingDetectorInterface;
use KickAssSubtitles\Language\LanguageDetector;
use KickAssSubtitles\Language\LanguageDetectorInterface;
use KickAssSubtitles\LineEnding\LineEndingConverter;
use KickAssSubtitles\LineEnding\LineEndingConverterDetector;
use KickAssSubtitles\LineEnding\LineEndingConverterDetectorInterface;
use KickAssSubtitles\LineEnding\LineEndingConverterInterface;
use KickAssSubtitles\LineEnding\LineEndingDetector;
use KickAssSubtitles\LineEnding\LineEndingDetectorInterface;
use KickAssSubtitles\Movie\Image as TablelessImage;
use KickAssSubtitles\Movie\ImageRepository as TablelessImageRepository;
use KickAssSubtitles\Movie\ImageRepositoryInterface;
use KickAssSubtitles\Movie\Movie as TablelessMovie;
use KickAssSubtitles\Movie\MovieRepository as TablelessMovieRepository;
use KickAssSubtitles\Movie\MovieRepositoryInterface;
use KickAssSubtitles\Movie\MovieSlugger;
use KickAssSubtitles\Movie\Provider\KickAssSubtitlesMovieProvider;
use KickAssSubtitles\Movie\Provider\OmdbMovieProvider;
use KickAssSubtitles\Movie\Provider\TmdbMovieProvider;
use KickAssSubtitles\Movie\Video as TablelessVideo;
use KickAssSubtitles\Movie\VideoRepository as TablelessVideoRepository;
use KickAssSubtitles\Movie\VideoRepositoryInterface;
use KickAssSubtitles\OpenSubtitles\OpenSubtitlesClient;
use KickAssSubtitles\Processor\Exception\AllChildTasksFailedException;
use KickAssSubtitles\Processor\Task as TablelessTask;
use KickAssSubtitles\Processor\TaskDownloaderInterface;
use KickAssSubtitles\Processor\TaskRepository as TablelessTaskRepository;
use KickAssSubtitles\Processor\TaskRepositoryInterface;
use KickAssSubtitles\Subtitle\Converter\Exception\ConversionFailedException;
use KickAssSubtitles\Subtitle\Converter\PhpSubtitleConverter;
use KickAssSubtitles\Subtitle\Converter\SubotageSubtitleConverter;
use KickAssSubtitles\Subtitle\Converter\SubtitleEditSubtitleConverter;
use KickAssSubtitles\Subtitle\Provider\Exception\SubtitlesNotFoundException;
use KickAssSubtitles\Subtitle\Provider\KickAssSubtitlesSubtitleProvider;
use KickAssSubtitles\Subtitle\Provider\NapiProjektSubtitleProvider;
use KickAssSubtitles\Subtitle\Provider\OpenSubtitlesSubtitleProvider;
use KickAssSubtitles\Subtitle\Subtitle as TablelessSubtitle;
use KickAssSubtitles\Subtitle\SubtitleFormatDetector;
use KickAssSubtitles\Subtitle\SubtitleFormatDetectorInterface;
use KickAssSubtitles\Subtitle\SubtitleRepository as TablelessSubtitleRepository;
use KickAssSubtitles\Subtitle\SubtitleRepositoryInterface;
use KickAssSubtitles\Support\Exception\EnumMapperException;
use KickAssSubtitles\Support\SluggerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Tmdb\ApiToken as TmdbApiToken;
use Tmdb\Client as TmdbClient;

/**
 * Class AppServiceProvider.
 */

/**
 * Class AppServiceProvider.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $providers = [
        \Barryvdh\Debugbar\ServiceProvider::class => [Environment::DEVELOPMENT],
        \Sentry\Laravel\ServiceProvider::class => [Environment::PRODUCTION],
    ];

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        $this->registerEnvironmentServiceProviders();
        $this->registerRepositories();
        $this->registerMovieProviders();
        $this->registerSubtitleProviders();
        $this->registerSubtitleConverters();
        $this->registerServices();
        $this->registerClients();
    }

    protected function registerEnvironmentServiceProviders(): void
    {
        $environment = new Environment($this->app->environment());
        foreach ($this->providers as $provider => $environments) {
            if (\in_array($environment->getValue(), $environments, true)) {
                $this->app->register($provider);
            }
        }
    }

    protected function registerRepositories(): void
    {
        $this->app->singleton(
            UserRepository::class,
            function (Container $app) {
                $taskRepository = $app->make(TaskRepositoryInterface::class);

                return new UserRepository(User::class, $taskRepository);
            }
        );

        $this->app->singleton(
            MovieRepositoryInterface::class,
            function (Container $app) {
                $slugger = $app->make(SluggerInterface::class);

                return new MovieRepository(
                    Movie::class,
                    $slugger
                );
            }
        );
        $this->app->singleton(
            TablelessMovieRepository::class,
            function (Container $app) {
                $slugger = $app->make(SluggerInterface::class);

                return new TablelessMovieRepository(
                    TablelessMovie::class,
                    $slugger
                );
            }
        );

        $this->app->singleton(
            VideoRepositoryInterface::class,
            function () {
                return new VideoRepository(
                    Video::class
                );
            }
        );
        $this->app->singleton(
            TablelessVideoRepository::class,
            function () {
                return new TablelessVideoRepository(
                    TablelessVideo::class
                );
            }
        );

        $this->app->singleton(
            TaskRepositoryInterface::class,
            function () {
                return new TaskRepository(Task::class);
            }
        );
        $this->app->singleton(
            TablelessTaskRepository::class,
            function () {
                return new TablelessTaskRepository(TablelessTask::class);
            }
        );

        $this->app->singleton(
            SubtitleRepositoryInterface::class,
            function (Container $app) {
                return new SubtitleRepository(
                    Subtitle::class,
                    $app->make(LanguageDetectorInterface::class),
                    $app->make(EncodingDetectorInterface::class),
                    $app->make(SubtitleFormatDetectorInterface::class)
                );
            }
        );
        $this->app->singleton(
            TablelessSubtitleRepository::class,
            function (Container $app) {
                return new TablelessSubtitleRepository(
                    TablelessSubtitle::class,
                    $app->make(LanguageDetectorInterface::class),
                    $app->make(EncodingDetectorInterface::class),
                    $app->make(SubtitleFormatDetectorInterface::class)
                );
            }
        );

        $this->app->singleton(
            ImageRepositoryInterface::class,
            function (Container $app) {
                $imageManager = $app->make(ImageManager::class);
                $filesystem = $app->make(FilesystemManager::class)->disk('tmp');

                return new ImageRepository(
                    Image::class,
                    $imageManager,
                    $filesystem
                );
            }
        );
        $this->app->singleton(
            TablelessImageRepository::class,
            function (Container $app) {
                $imageManager = $app->make(ImageManager::class);
                $filesystem = $app->make(FilesystemManager::class)->disk('tmp');

                return new TablelessImageRepository(
                    TablelessImage::class,
                    $imageManager,
                    $filesystem
                );
            }
        );
    }

    protected function registerMovieProviders(): void
    {
        $this->app->singleton(
            TmdbMovieProvider::class,
            function (Container $app) {
                $movieRepository = $app->make(TablelessMovieRepository::class);
                $imageRepository = $app->make(TablelessImageRepository::class);
                $client = $app->make(TmdbClient::class);

                return new TmdbMovieProvider(
                    $movieRepository,
                    $imageRepository,
                    $client
                );
            }
        );

        $this->app->singleton(
            OmdbMovieProvider::class,
            function (Container $app) {
                $movieRepository = $app->make(TablelessMovieRepository::class);
                $imageRepository = $app->make(TablelessImageRepository::class);
                $httpClient = $app->make(HttpClientInterface::class);
                $config = $app->make(ConfigRepository::class);

                return new OmdbMovieProvider(
                    $movieRepository,
                    $imageRepository,
                    $httpClient,
                    $config->get('omdb.api_key')
                );
            }
        );

        $this->app->singleton(
            KickAssSubtitlesMovieProvider::class,
            function (Container $app) {
                $movieRepository = $app->make(MovieRepositoryInterface::class);
                $imageRepository = $app->make(ImageRepositoryInterface::class);
                $tablelessMovieRepository = $app->make(TablelessMovieRepository::class);
                $tablelessImageRepository = $app->make(TablelessImageRepository::class);

                return new KickAssSubtitlesMovieProvider(
                    $movieRepository,
                    $imageRepository,
                    $tablelessMovieRepository,
                    $tablelessImageRepository
                );
            }
        );

        $this->app->singleton(
            MovieProvider::class,
            function (Container $app) {
                $provider = new MovieProvider([
                    $app->make(KickAssSubtitlesMovieProvider::class),
                    $app->make(TmdbMovieProvider::class),
                    $app->make(OmdbMovieProvider::class),
                ], $app->make(TablelessTaskRepository::class));
                $provider->setLogger($this->getProcessorLogger());
                $provider->setStopAfterFirstCompletedTask(true);

                return $provider;
            }
        );
    }

    protected function registerSubtitleProviders(): void
    {
        $this->app->singleton(
            NapiProjektSubtitleProvider::class,
            function (Container $app) {
                $subtitleRepository = $app->make(TablelessSubtitleRepository::class);
                $subtitleFormatDetector = $app->make(SubtitleFormatDetectorInterface::class);
                $encodingDetector = $app->make(EncodingDetectorInterface::class);
                $httpClient = $app->make(HttpClientInterface::class);

                $provider = new NapiProjektSubtitleProvider(
                    $subtitleRepository,
                    $subtitleFormatDetector,
                    $encodingDetector,
                    $httpClient
                );
                $provider->setLogger($this->getProcessorLogger());
                $provider->logSkip = $this->getProcessorLogSkip();

                return $provider;
            }
        );

        $this->app->singleton(
            OpenSubtitlesSubtitleProvider::class,
            function (Container $app) {
                $subtitleRepository = $app->make(TablelessSubtitleRepository::class);
                $openSubtitlesClient = $app->make(OpenSubtitlesClient::class);

                $provider = new OpenSubtitlesSubtitleProvider(
                    $subtitleRepository,
                    $openSubtitlesClient
                );
                $provider->setLogger($this->getProcessorLogger());
                $provider->logSkip = $this->getProcessorLogSkip();

                return $provider;
            }
        );

        $this->app->singleton(
            KickAssSubtitlesSubtitleProvider::class,
            function (Container $app) {
                $subtitleRepository = $app->make(SubtitleRepositoryInterface::class);
                $tablelessSubtitleRepository = $app->make(TablelessSubtitleRepository::class);

                $provider = new KickAssSubtitlesSubtitleProvider(
                    $subtitleRepository,
                    $tablelessSubtitleRepository
                );
                $provider->setLogger($this->getProcessorLogger());
                $provider->logSkip = $this->getProcessorLogSkip();

                return $provider;
            }
        );

        $this->app->singleton(
            SubtitleProvider::class,
            function (Container $app) {
                $provider = new SubtitleProvider(
                    [
                        $app->make(KickAssSubtitlesSubtitleProvider::class),
                        $app->make(NapiProjektSubtitleProvider::class),
                        $app->make(OpenSubtitlesSubtitleProvider::class),
                    ],
                    $app->make(TaskRepositoryInterface::class),
                    $app->make(MovieProvider::class),
                    $app->make(TablelessSubtitleConverter::class)
                );
                $provider->setStopAfterFirstCompletedTask(true);
                $provider->setLogger($this->getProcessorLogger());
                $provider->logSkip = $this->getProcessorLogSkip();
                $provider->setEventDispatcher($app->make(Dispatcher::class));

                return $provider;
            }
        );
    }

    protected function registerSubtitleConverters(): void
    {
        $this->app->singleton(
            PhpSubtitleConverter::class,
            function (Container $app) {
                $lineEndingConverterDetector = $app->make(LineEndingConverterDetectorInterface::class);
                $encodingConverterDetector = $app->make(EncodingConverterDetectorInterface::class);
                $subtitleFormatDetector = $app->make(SubtitleFormatDetectorInterface::class);

                $converter = new PhpSubtitleConverter(
                    $lineEndingConverterDetector,
                    $encodingConverterDetector,
                    $subtitleFormatDetector
                );
                $converter->setLogger($this->getProcessorLogger());
                $converter->logSkip = $this->getProcessorLogSkip();

                return $converter;
            }
        );

        $this->app->singleton(
            SubotageSubtitleConverter::class,
            function (Container $app) {
                $lineEndingConverterDetector = $app->make(LineEndingConverterDetectorInterface::class);
                $encodingConverterDetector = $app->make(EncodingConverterDetectorInterface::class);
                $subtitleFormatDetector = $app->make(SubtitleFormatDetectorInterface::class);

                $converter = new SubotageSubtitleConverter(
                    $lineEndingConverterDetector,
                    $encodingConverterDetector,
                    $subtitleFormatDetector
                );
                $converter->setLogger($this->getProcessorLogger());
                $converter->logSkip = $this->getProcessorLogSkip();

                return $converter;
            }
        );

        $this->app->singleton(
            SubtitleEditSubtitleConverter::class,
            function (Container $app) {
                $lineEndingConverterDetector = $app->make(LineEndingConverterDetectorInterface::class);
                $encodingConverterDetector = $app->make(EncodingConverterDetectorInterface::class);
                $subtitleFormatDetector = $app->make(SubtitleFormatDetectorInterface::class);

                $converter = new SubtitleEditSubtitleConverter(
                    $lineEndingConverterDetector,
                    $encodingConverterDetector,
                    $subtitleFormatDetector
                );
                $converter->setLogger($this->getProcessorLogger());
                $converter->logSkip = $this->getProcessorLogSkip();

                return $converter;
            }
        );

        $this->app->singleton(
            SubtitleConverter::class,
            function (Container $app) {
                $converter = new SubtitleConverter(
                    [
                        $app->make(PhpSubtitleConverter::class),
                        $app->make(SubotageSubtitleConverter::class),
                        $app->make(SubtitleEditSubtitleConverter::class),
                    ],
                    $app->make(TaskRepositoryInterface::class),
                    $app->make(TablelessSubtitleRepository::class)
                );
                $converter->setLogger($this->getProcessorLogger());
                $converter->logSkip = $this->getProcessorLogSkip();

                return $converter;
            }
        );
        $this->app->singleton(
            TablelessSubtitleConverter::class,
            function (Container $app) {
                $converter = new TablelessSubtitleConverter(
                    [
                        $app->make(PhpSubtitleConverter::class),
                        $app->make(SubotageSubtitleConverter::class),
                        $app->make(SubtitleEditSubtitleConverter::class),
                    ],
                    $app->make(TablelessTaskRepository::class),
                    $app->make(TablelessSubtitleRepository::class)
                );
                $converter->setLogger($this->getProcessorLogger());
                $converter->logSkip = $this->getProcessorLogSkip();

                return $converter;
            }
        );
    }

    protected function registerServices(): void
    {
        $this->app->singleton(
            SubtitleFormatDetectorInterface::class,
            function () {
                return SubtitleFormatDetector::create();
            }
        );

        $this->app->singleton(
            SluggerInterface::class,
            function () {
                return new MovieSlugger();
            }
        );

        $this->app->singleton(
            LanguageDetectorInterface::class,
            function () {
                return LanguageDetector::create();
            }
        );

        $this->app->singleton(
            LineEndingDetectorInterface::class,
            function () {
                return LineEndingDetector::create();
            }
        );

        $this->app->singleton(
            LineEndingConverterInterface::class,
            function () {
                return LineEndingConverter::create();
            }
        );

        $this->app->singleton(
            LineEndingConverterDetectorInterface::class,
            function (Container $app) {
                return new LineEndingConverterDetector(
                    $app->make(LineEndingConverterInterface::class),
                    $app->make(LineEndingDetectorInterface::class)
                );
            }
        );

        $this->app->singleton(
            EncodingDetectorInterface::class,
            function () {
                return EncodingDetector::create();
            }
        );

        $this->app->singleton(
            EncodingConverterInterface::class,
            function () {
                return EncodingConverter::create();
            }
        );

        $this->app->singleton(
            EncodingConverterDetectorInterface::class,
            function (Container $app) {
                return new EncodingConverterDetector(
                    $app->make(EncodingConverterInterface::class),
                    $app->make(EncodingDetectorInterface::class)
                );
            }
        );

        $this->app->bind(
            ImageManager::class,
            function () {
                return new ImageManager(['driver' => 'imagick']);
            }
        );

        $this->app->singleton(
            TaskDownloaderInterface::class,
            function () {
                return new TaskDownloader();
            }
        );
    }

    protected function registerClients(): void
    {
        $this->app->singleton(TmdbClient::class, function (Container $app) {
            /** @var ConfigRepository $config */
            $config = $app->make(ConfigRepository::class);

            $token = new TmdbApiToken($config->get('tmdb.api_token'));
            $client = new TmdbClient($token, [
                'secure' => false,
            ]);

            return $client;
        });

        $this->app->bind(HttpClientInterface::class, function (Container $app) {
            $config = $app->make(ConfigRepository::class);

            return new GuzzleHttpClient($config->get('httpclient'));
        });

        $this->app->singleton(OpenSubtitlesClient::class, function (Container $app) {
            $config = $app->make(ConfigRepository::class);

            return OpenSubtitlesClient::create($config->get('opensubtitles'));
        });
    }

    protected function getProcessorLogger(): LoggerInterface
    {
        $logger = (Environment::TESTING === $this->app->environment()) ?
            new NullLogger() :
            $this->app->make(Log::class)
        ;

        return $logger;
    }

    protected function getProcessorLogSkip(): array
    {
        return [
            ConversionFailedException::class,
            SubtitlesNotFoundException::class,
            AllChildTasksFailedException::class,
            EnumMapperException::class,
        ];
    }
}
