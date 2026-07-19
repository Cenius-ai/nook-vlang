<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Flag;
use App\Models\Question;
use App\Models\Tag;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Users ──────────────────────────────────
        $cenius = User::firstOrCreate(
            ['email' => 'cenius@cenius.ai'],
            [
                'name' => 'Cenius',
                'username' => 'cenius',
                'password' => Hash::make('cenius'),
                'role' => 'moderator',
                'bio' => 'Platform demo account — moderator with full access.',
                'email_verified_at' => now(),
            ]
        );

        $modUser = User::firstOrCreate(
            ['email' => 'mod@nook.local'],
            [
                'name' => 'Taylor Otwell',
                'username' => 'taylor',
                'password' => Hash::make('password'),
                'role' => 'moderator',
                'bio' => 'Creator of Laravel.',
                'email_verified_at' => now(),
            ]
        );

        $usersData = [
            ['name' => 'Samantha Chen', 'username' => 'samchen', 'email' => 'sam@devs.local', 'bio' => 'Full-stack developer. TypeScript, Rust, API design.'],
            ['name' => 'Marcus Rivera', 'username' => 'mrivera', 'email' => 'marcus@devs.local', 'bio' => 'Backend engineer. Databases and distributed systems.'],
            ['name' => 'Priya Kapoor', 'username' => 'priyak', 'email' => 'priya@devs.local', 'bio' => 'Frontend specialist. CSS and accessibility.'],
            ['name' => 'David Lindström', 'username' => 'dlind', 'email' => 'david@devs.local', 'bio' => 'DevOps engineer. Kubernetes and CI/CD.'],
            ['name' => 'Aisha Mohammed', 'username' => 'aisham', 'email' => 'aisha@devs.local', 'bio' => 'Data scientist. Python, ML, pipelines.'],
            ['name' => 'James Okonkwo', 'username' => 'jokonkwo', 'email' => 'james@devs.local', 'bio' => 'Mobile developer. React Native and Flutter.'],
            ['name' => 'Elena Kowalski', 'username' => 'elenak', 'email' => 'elena@devs.local', 'bio' => 'Security researcher. AppSec and bug bounties.'],
            ['name' => 'Yuki Tanaka', 'username' => 'ytanaka', 'email' => 'yuki@devs.local', 'bio' => 'Game developer. Graphics programming.'],
        ];

        $allUsers = [$cenius, $modUser];
        foreach ($usersData as $data) {
            $allUsers[] = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'username' => $data['username'],
                    'password' => Hash::make('password'),
                    'bio' => $data['bio'],
                    'email_verified_at' => now(),
                ]
            );
        }

        // ── Tags ───────────────────────────────────
        $tagNames = ['javascript', 'php', 'python', 'rust', 'typescript', 'react', 'vue', 'laravel', 'django', 'nodejs',
            'sql', 'docker', 'kubernetes', 'aws', 'css', 'go', 'java', 'csharp', 'testing', 'devops', 'api', 'security',
            'performance', 'database', 'git'];

        $tags = [];
        foreach ($tagNames as $name) {
            $tags[] = Tag::firstOrCreate(['name' => $name], ['slug' => $name]);
        }

        if (Question::count() > 0) {
            $this->command?->info('Seed data already exists, skipping.');
            return;
        }

        // ── Questions ──────────────────────────────
        $questionsData = [
            ['user_idx' => 2, 'title' => 'How do I optimize Eloquent queries with large datasets in Laravel?',
             'body' => "I'm working on a Laravel application that needs to process about 500,000 records in a single request. The current implementation uses Eloquent models with relationships, but it's taking over 30 seconds to complete.\n\nHere's my current code:\n```php\n\$users = User::with(['posts', 'comments'])->get();\nforeach (\$users as \$user) {\n    // process each user\n}\n```\n\nI've tried chunking but it's still slow. What are the best practices for handling large datasets with Eloquent? Should I switch to the query builder, or use lazy collections?",
             'tags' => ['laravel', 'php', 'sql', 'performance']],
            ['user_idx' => 3, 'title' => 'TypeScript: When should I use `type` vs `interface` for object shapes?',
             'body' => "I've been using TypeScript for a while but I still get confused about when to use `type` versus `interface`.\n\nI know interfaces support declaration merging and types support unions, but beyond that, are there performance differences? What does the community generally prefer?",
             'tags' => ['typescript', 'javascript']],
            ['user_idx' => 4, 'title' => 'Docker Compose: How to properly wait for PostgreSQL to be ready before starting my app?',
             'body' => "I have a Node.js app in Docker Compose that depends on PostgreSQL. The problem is that my app starts before Postgres is ready to accept connections, causing crashes on startup.\n\nI know `depends_on` only waits for the container to start, not for the service to be ready. What's the recommended approach? Healthchecks? Wait-for-it scripts?",
             'tags' => ['docker', 'nodejs']],
            ['user_idx' => 5, 'title' => 'Rust async: What is the difference between tokio::spawn and tokio::task::spawn_blocking?',
             'body' => "I'm learning async Rust with Tokio and I'm confused about when to use `spawn` vs `spawn_blocking`.\n\nI have a task that reads a large file from disk. Should I use `spawn_blocking` for this? What happens if I use regular `spawn` for CPU-intensive work?",
             'tags' => ['rust', 'async']],
            ['user_idx' => 6, 'title' => 'CSS Grid vs Flexbox: Which one should I use for a dashboard layout?',
             'body' => "I'm building an admin dashboard with a sidebar, header, main content area, and some cards/widgets. I keep going back and forth between CSS Grid and Flexbox.\n\nShould I use Grid for the overall layout and Flexbox for the cards, or Grid for everything?",
             'tags' => ['css', 'html']],
            ['user_idx' => 7, 'title' => 'How to handle JWT token refresh in a React SPA without breaking the user experience?',
             'body' => "I have a React SPA that uses JWT access tokens (15 min expiry) and refresh tokens (7 day expiry). The problem is handling token refresh gracefully.\n\nCurrent approach: Axios interceptor catches 401, tries to refresh token, retries the original request. But this causes flickering and multiple refresh requests simultaneously. How are others handling this?",
             'tags' => ['react', 'javascript', 'api', 'security']],
            ['user_idx' => 8, 'title' => 'PostgreSQL: Why is my query using a sequential scan instead of the index?',
             'body' => "I have a table with 2 million rows and an index on the `status` column, but PostgreSQL is choosing a sequential scan.\n\nAbout 40% of rows have status='pending'. Is the planner making the right choice? How can I force it to use the index or should I restructure my query?",
             'tags' => ['sql', 'performance', 'database']],
            ['user_idx' => 2, 'title' => 'What are the best practices for structuring a large Vue 3 application with Composition API?',
             'body' => "Our team is migrating a large Vue 2 codebase to Vue 3 Composition API with about 200 components.\n\nI'm looking for conventions around composable organization, state management (Pinia vs provide/inject), testing composables, and directory structure for a feature-based architecture.",
             'tags' => ['vue', 'javascript', 'typescript']],
            ['user_idx' => 3, 'title' => 'Kubernetes: How do I debug CrashLoopBackOff when logs show nothing useful?',
             'body' => "I have a pod stuck in CrashLoopBackOff. The container starts, runs for about 2 seconds, and then crashes with exit code 1. No application logs are being produced. How can I debug this?",
             'tags' => ['kubernetes', 'docker', 'devops']],
            ['user_idx' => 4, 'title' => 'Python: Understanding asyncio.gather vs asyncio.create_task for concurrent API calls',
             'body' => "I'm making multiple API calls and want them to run concurrently. I've seen both `asyncio.gather` and `asyncio.create_task` patterns. Is there any practical difference? When should I use one over the other?",
             'tags' => ['python', 'async']],
            ['user_idx' => 5, 'title' => 'Git: How to undo a merge that has already been pushed to remote?',
             'body' => "I accidentally merged a feature branch into main and pushed it. Team members have already pulled. What's the safest way to undo this? Options: git revert -m 1, git reset --hard + force push, or something else?",
             'tags' => ['git']],
            ['user_idx' => 6, 'title' => 'Best approach for handling file uploads in a Next.js app with server actions?',
             'body' => "I'm building a Next.js 14 app that needs file upload functionality (images up to 10MB). Should I use Server Actions directly, or a Route Handler? How do I handle progress and security considerations?",
             'tags' => ['react', 'typescript', 'nodejs', 'security']],
        ];

        $questionModels = [];
        foreach ($questionsData as $i => $qData) {
            $q = Question::create([
                'user_id' => $allUsers[$qData['user_idx']]->id,
                'title' => $qData['title'],
                'body' => $qData['body'],
                'slug' => Question::uniqueSlug($qData['title']),
                'view_count' => rand(50, 5000),
                'votes_count' => 0,
                'created_at' => now()->subDays(rand(0, 30))->subHours(rand(0, 23)),
            ]);
            $tagIds = Tag::whereIn('name', $qData['tags'])->pluck('id')->toArray();
            $q->tags()->sync($tagIds);
            $questionModels[] = $q;
        }

        // ── Answers ────────────────────────────────
        $answerTexts = [
            "Great question! For large datasets, use `lazy()` instead of `get()`. This returns a `LazyCollection` that processes records one at a time without loading everything into memory. Also make sure you have proper indexes on your foreign key columns.",
            "I'd recommend using `chunkById()` instead of `chunk()` when updating records. It avoids skipping records when the query is modified during iteration. Also consider using the query builder directly for bulk operations.",
            "The general guideline: use `interface` by default, reach for `type` when you need union types, intersection types, or mapped types. Interfaces are slightly faster in the compiler but the difference is negligible.",
            "Use `depends_on` with `condition: service_healthy` and add a healthcheck to your Postgres service. This ensures the app only starts after Postgres is truly accepting connections.",
            "For file I/O in Tokio: if using `tokio::fs`, use `spawn`. If using `std::fs`, use `spawn_blocking`. CPU-intensive work in `spawn` blocks the async runtime thread, preventing other tasks from making progress.",
            "For a dashboard, use CSS Grid for the overall page layout (2D: rows AND columns) and Flexbox for the card grid inside the main area (1D: a row of cards that wraps).",
            "The standard approach is an axios interceptor with a queue pattern. While refreshing, queue all other requests and replay them once the new token arrives. This prevents duplicate refresh calls.",
            "At 40% matching, a sequential scan is faster than an index scan. Try a partial index or table partitioning instead.",
            "I use a feature-based structure: `src/features/auth/components/`, one composable per file, grouped by feature. Pinia for global state, composables with provide/inject for tree-scoped state.",
            "Try `kubectl logs --previous` for crash logs, or add a sleep command to your container entrypoint to exec in and debug manually.",
            "Use `asyncio.gather` for simple fire-and-concurrent use cases. It's cleaner and starts all coroutines immediately. Pass `return_exceptions=True` to collect exceptions instead of raising.",
            "Use `git revert -m 1 <merge-commit>`. This is the safe option for shared branches. The `-m 1` tells Git to keep the first parent and revert changes from the merged branch.",
            "For Next.js 14, use a Route Handler for file uploads rather than Server Actions. Server Actions have size limits. Implement progress via a client-side fetch with `onUploadProgress`. Validate file types server-side.",
        ];

        $answerModels = [];
        foreach ($questionModels as $qi => $question) {
            $numAnswers = rand(2, 4);
            // Pick answerers who are NOT the question author
            $candidates = [];
            foreach ($allUsers as $ui => $user) {
                if ($user->id !== $question->user_id) {
                    $candidates[] = ['idx' => $ui, 'user' => $user];
                }
            }
            shuffle($candidates);
            $picked = array_slice($candidates, 0, $numAnswers);

            foreach ($picked as $ai => $c) {
                $answer = Answer::create([
                    'question_id' => $question->id,
                    'user_id' => $c['user']->id,
                    'body' => $answerTexts[($qi + $ai) % count($answerTexts)],
                    'votes_count' => 0,
                    'is_accepted' => false,
                    'created_at' => $question->created_at->addHours(rand(1, 48)),
                ]);
                $answerModels[] = $answer;
            }

            // Accept one answer for ~50% of questions
            if (rand(0, 1) && $question->answers()->count() > 0) {
                $accepted = $question->answers()->inRandomOrder()->first();
                if ($accepted) {
                    $accepted->update(['is_accepted' => true]);
                    $question->update(['accepted_answer_id' => $accepted->id]);
                }
            }
        }

        // ── Votes ──────────────────────────────────
        $votables = array_merge(
            array_map(fn($q) => ['type' => Question::class, 'model' => $q, 'id' => $q->id], $questionModels),
            array_map(fn($a) => ['type' => Answer::class, 'model' => $a, 'id' => $a->id], $answerModels),
        );

        foreach ($votables as $item) {
            $numVotes = rand(1, 6);
            $voterUsers = $allUsers;
            shuffle($voterUsers);
            $voters = array_slice($voterUsers, 0, $numVotes);

            foreach ($voters as $voter) {
                $value = rand(0, 1) ? 1 : -1;
                try {
                    Vote::create([
                        'user_id' => $voter->id,
                        'votable_id' => $item['id'],
                        'votable_type' => $item['type'],
                        'value' => $value,
                    ]);
                    $item['model']->increment('votes_count', $value);
                } catch (\Exception $e) {
                    // Duplicate vote, skip
                }
            }
        }

        // ── Flags ──────────────────────────────────
        $flagReasons = [
            'This content appears to be spam or promotional material.',
            'Contains outdated information that may mislead readers.',
            'The answer does not address the question asked.',
            'Inappropriate or unprofessional language.',
        ];

        // Flag a few items
        $flagTargets = array_slice($votables, 0, 6);
        foreach ($flagTargets as $item) {
            try {
                Flag::create([
                    'user_id' => $cenius->id,
                    'flaggable_id' => $item['id'],
                    'flaggable_type' => $item['type'],
                    'reason' => $flagReasons[array_rand($flagReasons)],
                    'status' => 'pending',
                ]);
            } catch (\Exception $e) {
                // Skip duplicates
            }
        }

        // Resolve one flag to show resolved state
        $firstFlag = Flag::where('status', 'pending')->first();
        if ($firstFlag) {
            $firstFlag->update([
                'status' => 'resolved',
                'resolved_by' => $modUser->id,
                'resolved_at' => now()->subDays(2),
            ]);
        }

        $this->command?->info('Seeded: ' . User::count() . ' users, ' . Question::count() . ' questions, ' .
            Answer::count() . ' answers, ' . Vote::count() . ' votes, ' . Flag::count() . ' flags.');
    }
}
