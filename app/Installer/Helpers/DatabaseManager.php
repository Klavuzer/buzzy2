<?php

namespace App\Installer\Helpers;

use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;

class DatabaseManager
{

    /**
     * Migrate and seed the database.
     *
     * @return array
     */
    public function migrateAndSeed()
    {
        return $this->migrate();
    }

    /**
     * Execute migrations and seeders.
     *
     * @return array
     */
    public function updateDatabaseAndSeedTables()
    {
        Config::set('app.env', 'local');
        $this->clearCache();

        return $this->updateDatabase();
    }

    /**
     * Run the migration and call the seeder.
     *
     * @return array
     */
    private function migrate()
    {
        try {
            Artisan::call('migrate', ['--force' => true]);
        } catch (Exception $e) {
            return $this->response($e->getMessage());
        }

        return $this->seed();
    }

    /**
     * Seed the database.
     *
     * @return array
     */
    private function seed()
    {
        try {
            Artisan::call('db:seed', ['--force' => true]);
        } catch (Exception $e) {
            return $this->response($e->getMessage());
        }
        return $this->finish();
    }

    /**
     * Update the database.
     *
     * @return array
     */
    private function updateDatabase()
    {
        $migrations = config('buzzy.upgrade.migrations');

        if ($migrations) {
            try {
                if (is_array($migrations)) {
                    $database_path = database_path();

                    foreach ($migrations as $key => $value) {
                        Artisan::call('migrate', ['--path' => $database_path . "/migrations/" . $value, '--force' => true]);
                    }
                } else {
                    Artisan::call('migrate', ['--force' => true]);
                }
            } catch (Exception $e) {
                return $this->response($e->getMessage(), 'error');
            }
        }

        return $this->updateSeed();
    }

    /**
     * Seed the database.
     *
     * @return array
     */
    private function updateSeed()
    {
        $seeds = config('buzzy.upgrade.seeds');

        if ($seeds && is_array($seeds) && !empty($seeds)) {
            try {
                foreach ($seeds as $key => $value) {
                    Artisan::call('db:seed', ['--class' => $value, '--force' => true]);
                }
            } catch (Exception $e) {
                return $this->response($e->getMessage(), 'error');
            }
        }
        return $this->finish();
    }

    /**
     * Return a formatted error messages.
     *
     * @param  $message
     * @param  string  $status
     * @return array
     */
    private function finish()
    {
        try {
            Artisan::call('key:generate', ['--force' => true]);
            Artisan::call('optimize', ['--force' => true]);
            Artisan::call('config:cache', ['--force' => true]);
            Artisan::call('route:cache', ['--force' => true]);
            Artisan::call('view:cache', ['--force' => true]);

            set_buzzy_config('APP_ENV', 'production', false);
        } catch (Exception $e) {
            //
        }

        return $this->response(trans('installer.final.finished'), 'success');
    }

    /**
     * Clear Cache
     */
    public function clearCache()
    {
        try {
            Cache::flush();
            Artisan::call('cache:clear', ['--force' => true]);
            Artisan::call('config:clear', ['--force' => true]);
            Artisan::call('route:clear', ['--force' => true]);
            Artisan::call('view:clear', ['--force' => true]);
        } catch (Exception $e) {
            //
        }
    }

    /**
     * Return a formatted error messages.
     *
     * @param  $message
     * @param  string  $status
     * @return array
     */
    private function response($message, $status = 'error')
    {
        return array(
            'status' => $status,
            'message' => $message
        );
    }
}
