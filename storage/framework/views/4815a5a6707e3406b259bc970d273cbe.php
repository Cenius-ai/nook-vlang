<?php $__env->startSection('title', 'Questions'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex flex-col lg:flex-row gap-8">
    
    <div class="flex-1 min-w-0">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-heading font-bold text-[#132129] dark:text-[#e8eef1]">Questions</h1>
            <?php if(auth()->guard()->check()): ?>
            <a href="<?php echo e(route('questions.create')); ?>" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-[#009494] text-white text-sm font-medium hover:bg-[#007a7a] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#009494] focus-visible:ring-offset-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Ask Question
            </a>
            <?php endif; ?>
        </div>

        <?php if(request()->filled('tag')): ?>
            <div class="mb-4 p-2 text-sm text-[#5a656b] dark:text-[#8a959b]">
                Filtered by tag: <span class="font-medium text-[#009494]"><?php echo e(request('tag')); ?></span>
                <a href="<?php echo e(route('questions.index')); ?>" class="ml-2 text-[#c9302d] hover:underline">&times; Clear</a>
            </div>
        <?php endif; ?>

        <?php $__empty_1 = true; $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="mb-3 bg-[#fdffff] dark:bg-[#132129] rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] p-5 hover:border-[#009494]/30 dark:hover:border-[#009494]/30 transition-colors">
                <div class="flex gap-4">
                    
                    <div class="hidden sm:flex flex-col items-center gap-1 min-w-[60px] text-sm text-[#5a656b] dark:text-[#8a959b]">
                        <span class="font-medium text-[#0b1418] dark:text-[#e8eef1]"><?php echo e($question->votes_count); ?></span>
                        <span class="text-xs">votes</span>
                        <span class="font-medium <?php echo e($question->accepted_answer_id ? 'text-[#009494] bg-[#009494]/10 px-2 py-0.5 rounded' : 'text-[#0b1418] dark:text-[#e8eef1]'); ?>"><?php echo e($question->answers_count); ?></span>
                        <span class="text-xs"><?php echo e(Str::plural('answer', $question->answers_count)); ?></span>
                        <span class="text-xs text-[#5a656b] dark:text-[#5a656b] mt-1"><?php echo e($question->view_count); ?> <?php echo e(Str::plural('view', $question->view_count)); ?></span>
                    </div>

                    
                    <div class="flex-1 min-w-0">
                        <h2 class="text-lg font-heading font-semibold mb-1">
                            <a href="<?php echo e(route('questions.show', $question)); ?>" class="text-[#132129] dark:text-[#e8eef1] hover:text-[#009494] dark:hover:text-[#009494] transition-colors">
                                <?php echo e($question->title); ?>

                            </a>
                        </h2>
                        <p class="text-sm text-[#5a656b] dark:text-[#8a959b] line-clamp-2 mb-3">
                            <?php echo e(\Illuminate\Support\Str::limit(strip_tags($question->body), 200)); ?>

                        </p>
                        <div class="flex flex-wrap items-center gap-2">
                            <?php $__currentLoopData = $question->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(route('tags.show', $tag)); ?>" class="px-2 py-0.5 text-xs rounded-md bg-[#e8eef1] dark:bg-[#1a282f] text-[#5a656b] dark:text-[#8a959b] hover:text-[#009494] dark:hover:text-[#009494] transition-colors"><?php echo e($tag->name); ?></a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <span class="text-xs text-[#5a656b] dark:text-[#5a656b] ml-auto">
                                asked <?php echo e($question->created_at->diffForHumans()); ?> by
                                <a href="<?php echo e(route('users.profile', $question->user)); ?>" class="text-[#009494] hover:underline font-medium"><?php echo e($question->user->name); ?></a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center py-16 text-[#5a656b] dark:text-[#8a959b]">
                <svg class="w-16 h-16 mx-auto mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-lg font-medium">No questions yet.</p>
                <?php if(auth()->guard()->check()): ?>
                    <p class="mt-2"><a href="<?php echo e(route('questions.create')); ?>" class="text-[#009494] hover:underline font-medium">Be the first to ask one!</a></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="mt-6">
            <?php echo e($questions->links()); ?>

        </div>
    </div>

    
    <div class="w-full lg:w-[260px] shrink-0">
        <div class="bg-[#fdffff] dark:bg-[#132129] rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] p-4">
            <h3 class="text-sm font-heading font-semibold text-[#132129] dark:text-[#e8eef1] mb-3">Popular Tags</h3>
            <div class="flex flex-wrap gap-2">
                <?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('tags.show', $tag)); ?>"
                       class="px-2.5 py-1 text-xs rounded-md border <?php echo e(request('tag') === $tag->slug ? 'border-[#009494] bg-[#009494]/10 text-[#009494]' : 'border-[#d9dfe2] dark:border-[#2a353a] text-[#5a656b] dark:text-[#8a959b] hover:text-[#009494] dark:hover:text-[#009494] hover:border-[#009494]/30'); ?> transition-colors">
                        <?php echo e($tag->name); ?> <span class="opacity-60">(<?php echo e($tag->questions_count); ?>)</span>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <a href="<?php echo e(route('tags.index')); ?>" class="block mt-3 text-xs text-[#009494] hover:underline font-medium">View all tags &rarr;</a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /work/resources/views/questions/index.blade.php ENDPATH**/ ?>