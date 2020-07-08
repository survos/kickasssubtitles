<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

use App\Models\Image;
use App\Models\Media;
use App\Models\Movie;
use App\Models\Subtitle;
use App\Models\Task;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use KickAssSubtitles\Subtitle\SubtitleProvider;

/**
 * Class CreateAppTables.
 */
class CreateAppTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @throws ReflectionException
     */
    public function up(): void
    {
        Schema::create(User::getTableName(), function (Blueprint $table) {
            $table->bigIncrements(User::ID);
            // data
            $table->string(User::USERNAME);
            $table->string(User::PASSWORD)->nullable();
            $table->string(User::EMAIL)->nullable();
            $table->string(User::ACTIVATION_TOKEN)->nullable();
            $table->boolean(User::ACTIVE)->default(0);
            $table->rememberToken();
            // dates
            $table->timestamps();
            // indexes
            $table->unique(User::USERNAME);
            $table->unique(User::EMAIL);
        });

        Schema::create(Movie::getTableName(), function (Blueprint $table) {
            $table->bigIncrements(Movie::ID);
            // data
            $table->string(Movie::IMDB_ID);
            $table->string(Movie::PROVIDER);
            $table->string(Movie::PROVIDER_PREVIOUS)->nullable();
            $table->string(Movie::TYPE);
            $table->string(Movie::TITLE);
            $table->string(Movie::SLUG);
            $table->integer(Movie::YEAR_FROM)->nullable();
            $table->integer(Movie::YEAR_TO)->nullable();
            // dates
            $table->datetime(Movie::SEARCHED_AT)->nullable();
            $table->timestamps();
            // indexes
            $table->unique(Movie::IMDB_ID);
            $table->index(Movie::SLUG);
        });

        Schema::create(Image::getTableName(), function (Blueprint $table) {
            $table->bigIncrements(Image::ID);
            // data
            $table->string(Image::IMDB_ID);
            $table->string(Image::PROVIDER);
            $table->string(Image::PROVIDER_PREVIOUS)->nullable();
            $table->string(Image::TYPE);
            $table->integer(Image::WIDTH);
            $table->integer(Image::HEIGHT);
            // dates
            $table->timestamps();
            // indexes
            $table->index(Image::IMDB_ID);
        });

        Schema::create(Video::getTableName(), function (Blueprint $table) {
            $table->bigIncrements(Video::ID);
            // data
            $table->string(Video::IMDB_ID);
            $table->bigInteger(Video::FILESIZE);
            $table->json(Video::FILENAMES);
            foreach (SubtitleProvider::values() as $provider) {
                if ($provider->equals(SubtitleProvider::KICKASSSUBTITLES())) {
                    continue;
                }
                $table->string($provider->getHashStorageField())->nullable();
            }
            $table->boolean(Video::UPDATE_HASHES)->default(0);
            // dates
            $table->timestamps();
            // indexes
            foreach (SubtitleProvider::values() as $provider) {
                if ($provider->equals(SubtitleProvider::KICKASSSUBTITLES())) {
                    continue;
                }
                $table->unique($provider->getHashStorageField());
            }
            $table->index(Video::IMDB_ID);
        });

        Schema::create(Subtitle::getTableName(), function (Blueprint $table) {
            $table->bigIncrements(Subtitle::ID);
            // relations
            $table->bigInteger(Subtitle::VIDEO_ID)->unsigned()->nullable();
            $table->foreign(Subtitle::VIDEO_ID)->references(Video::ID)->on(Video::getTableName());
            // data
            $table->string(Subtitle::IMDB_ID)->nullable();
            $table->string(Subtitle::FORMAT);
            $table->string(Subtitle::LANGUAGE);
            $table->string(Subtitle::ENCODING);
            $table->string(Subtitle::HASH);
            $table->string(Subtitle::PROVIDER)->nullable();
            $table->string(Subtitle::PROVIDER_PREVIOUS)->nullable();
            $table->timestamps();
            // indexes
            $table->unique([Subtitle::VIDEO_ID, Subtitle::LANGUAGE, Subtitle::FORMAT, Subtitle::ENCODING]);
            $table->unique(Subtitle::HASH);
            $table->index(Subtitle::IMDB_ID);
        });

        Schema::create(Task::getTableName(), function (Blueprint $table) {
            $table->bigIncrements(Task::ID);
            // relations
            $table->bigInteger(Task::USER_ID)->unsigned()->nullable();
            $table->foreign(Task::USER_ID)->references(User::ID)->on(User::getTableName())->onDelete('cascade');
            $table->bigInteger(Task::PARENT_ID)->unsigned()->nullable();
            $table->foreign(Task::PARENT_ID)->references(Task::ID)->on(Task::getTableName());
            // data
            $table->string(Task::GROUP)->nullable();
            $table->string(Task::TYPE);
            $table->string(Task::IDENTIFIER);
            $table->string(Task::STATUS);
            $table->string(Task::PROCESSOR_NAME)->nullable();
            $table->json(Task::OPTIONS);
            $table->json(Task::RESULTS)->nullable();
            $table->json(Task::ERROR)->nullable();
            // dates
            $table->timestamps();
            // indexes
            $table->unique(Task::IDENTIFIER);
            $table->index(Task::GROUP);
        });

        Schema::create(Media::getTableName(), function (Blueprint $table) {
            $table->bigIncrements(Media::ID);
            $table->morphs(Media::MODEL);
            $table->string(Media::COLLECTION_NAME);
            $table->string(Media::NAME);
            $table->string(Media::FILE_NAME);
            $table->string(Media::MIME_TYPE)->nullable();
            $table->string(Media::DISK);
            $table->unsignedInteger(Media::SIZE);
            $table->json(Media::MANIPULATIONS);
            $table->json(Media::CUSTOM_PROPERTIES);
            $table->json(Media::RESPONSIVE_IMAGES);
            $table->unsignedInteger(Media::ORDER_COLUMN)->nullable();
            $table->nullableTimestamps();
        });

        Schema::create('password_resets', function (Blueprint $table) {
            // data
            $table->string('email');
            $table->string('token');
            $table->timestamp('created_at')->nullable();
            // indexes
            $table->index('email');
        });

        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            // data
            $table->string('queue');
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            // dates
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
            // indexes
            $table->index('queue');
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            // data
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            // dates
            $table->timestamp('failed_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @throws ReflectionException
     */
    public function down(): void
    {
        Schema::dropIfExists(Task::getTableName());
        Schema::dropIfExists(Media::getTableName());
        Schema::dropIfExists(Subtitle::getTableName());
        Schema::dropIfExists(Video::getTableName());
        Schema::dropIfExists(Movie::getTableName());
        Schema::dropIfExists(User::getTableName());
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('failed_jobs');
    }
}
