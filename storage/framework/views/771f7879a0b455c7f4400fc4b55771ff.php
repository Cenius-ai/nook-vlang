<?php $__env->startSection('title', 'Search: ' . $q); ?>

<?php $__env->startSection('content'); ?>
<h1 class="text-2xl font-heading font-bold text-[#132129] dark:text-[#e8eef1] mb-2">Search Results</h1>

<form action="<?php echo e(route('search')); ?>" method="GET" class="mb-6">
    <div class="flex gap-2">
        <input type="text" name="q" value="<?php echo e($q); ?>"
               class="flex-1 px-4 py-2 rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] bg-[#fdffff] dark:bg-[#132129] text-[#0b1418] dark:text-[#e8eef1] focus:outline-none focus:ring-2 focus:ring-[#009494] focus:border-transparent"
               placeholder="Search questions…">
        <button type="submit" class="px-4 py-2 rounded-lg bg-[#009494] text-white font-medium hover:bg-[#007a7a] transition-colors">Search</button>
    </div>
</form>

<?php if(mb_strlen(trim($q)) === 0): ?>
    <p class="text-[#5a656b] dark:text-[#8a959b]">Enter a search term above.</p>
<?php else: ?>
    <p class="text-sm text-[#5a656b] dark:text-[#8a959b] mb-4"><?php echo e($questions->total()); ?> result<?php echo e($questions->total() !== 1 ? 's' : ''); ?> for "<strong><?php echo e($q); ?></strong>"</p>

    <?php $__empty_1 = true; $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="mb-3 bg-[#fdffff] dark:bg-[#132129] rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] p-5">
            <h2 class="text-lg font-heading font-semibold mb-1">
                <a href="<?php echo e(route('questions.show', $question)); ?>" class="text-[#132129] dark:text-[#e8eef1] hover:text-[#009494] dark:hover:text-[#009494]"><?php echo e($question->title); ?></a>
            </h2>
            <p class="text-sm text-[#5a656b] dark:text-[#8a959b] line-clamp-2 mb-2"><?php echo e(\Illuminate\Support\Str::limit(strip_tags($question->body), 200)); ?></p>
            <span class="text-xs text-[#5a656b]">
                <?php echo e($question->votes_count); ?> votes &middot; <?php echo e($question->answers_count); ?> answers &middot;
                asked by <a href="<?php echo e(route('users.profile', $question->user)); ?>" class="text-[#009494] hover:underline"><?php echo e($question->user->name); ?></a>
                <?php echo e($question->created_at->diffForHumans()); ?>

            </span>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="text-[#5a656b] py-8 text-center">No questions found matching "<?php echo e($q); ?>".</p>
    <?php endif; ?>
    <div class="mt-4"><?php echo e($questions->links()); ?></div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /work/resources/views/search/results.blade.php ENDPATH**/ ?>