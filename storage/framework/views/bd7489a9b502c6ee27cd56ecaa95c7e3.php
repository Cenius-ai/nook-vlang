<?php $__env->startSection('title', 'Tag: ' . $tag->name); ?>

<?php $__env->startSection('content'); ?>
<div class="flex flex-col lg:flex-row gap-8">
    <div class="flex-1 min-w-0">
        <div class="flex items-center gap-3 mb-6">
            <span class="px-3 py-1.5 text-sm rounded-lg bg-[#009494] text-white font-medium"><?php echo e($tag->name); ?></span>
            <span class="text-sm text-[#5a656b] dark:text-[#8a959b]"><?php echo e($tag->questions_count); ?> <?php echo e(Str::plural('question', $tag->questions_count)); ?></span>
        </div>

        <?php $__empty_1 = true; $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="mb-3 bg-[#fdffff] dark:bg-[#132129] rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] p-5">
                <div class="flex gap-4">
                    <div class="hidden sm:flex flex-col items-center gap-1 min-w-[60px] text-sm text-[#5a656b] dark:text-[#8a959b]">
                        <span class="font-medium text-[#0b1418] dark:text-[#e8eef1]"><?php echo e($question->votes_count); ?></span>
                        <span class="text-xs">votes</span>
                        <span class="font-medium <?php echo e($question->accepted_answer_id ? 'text-[#009494] bg-[#009494]/10 px-2 py-0.5 rounded' : 'text-[#0b1418] dark:text-[#e8eef1]'); ?>"><?php echo e($question->answers_count); ?></span>
                        <span class="text-xs">answers</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h2 class="text-lg font-heading font-semibold mb-1">
                            <a href="<?php echo e(route('questions.show', $question)); ?>" class="text-[#132129] dark:text-[#e8eef1] hover:text-[#009494] dark:hover:text-[#009494]"><?php echo e($question->title); ?></a>
                        </h2>
                        <p class="text-sm text-[#5a656b] dark:text-[#8a959b] line-clamp-2 mb-2"><?php echo e(\Illuminate\Support\Str::limit(strip_tags($question->body), 160)); ?></p>
                        <span class="text-xs text-[#5a656b]">asked <?php echo e($question->created_at->diffForHumans()); ?> by <a href="<?php echo e(route('users.profile', $question->user)); ?>" class="text-[#009494] hover:underline"><?php echo e($question->user->name); ?></a></span>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-[#5a656b] py-8 text-center">No questions with this tag yet.</p>
        <?php endif; ?>
        <div class="mt-4"><?php echo e($questions->links()); ?></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /work/resources/views/tags/show.blade.php ENDPATH**/ ?>