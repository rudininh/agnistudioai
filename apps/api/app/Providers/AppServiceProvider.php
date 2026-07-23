<?php

namespace App\Providers;

use App\Domain\Authentication\Repositories\UserRepository;
use App\Domain\Authentication\Services\AuthService;
use App\Domain\Authentication\Services\AuthServiceImpl;
use App\Domain\Content\Services\ProjectService;
use App\Domain\Content\Services\ProjectServiceInterface;
use App\Domain\Workspace\Services\WorkspaceService;
use App\Domain\Workspace\Services\WorkspaceServiceInterface;
use App\Infrastructure\Persistence\Eloquent\Authentication\EloquentUserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind service interfaces to their implementations
        $this->app->bind(WorkspaceServiceInterface::class, WorkspaceService::class);
        $this->app->bind(ProjectServiceInterface::class, ProjectService::class);
        $this->app->bind(AuthService::class, AuthServiceImpl::class);
        // Bind the UserRepository interface to its Eloquent implementation
        $this->app->bind(UserRepository::class, EloquentUserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}