<?php

namespace App\Console\Commands;

use App\Repositories\Posts\PostRepository;
use Illuminate\Console\Command;

class DeleteOlderPosts extends Command
{
    const NUMBER_OF_DAYS_TO_DELETE_POSTS = 15;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:delete-older {days? : Number of days}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete posts older than specific days';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(PostRepository $postRepository)
    {
        $days = $this->argument('days') ?? self::NUMBER_OF_DAYS_TO_DELETE_POSTS;

        $postRepository->deletePostsOlderThanGivenDays((int) $days);

        $this->info("All posts older than {$days} are deleted !");

        return self::SUCCESS;
    }
}
