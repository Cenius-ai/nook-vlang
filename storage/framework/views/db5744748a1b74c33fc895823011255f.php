<?php $__env->startSection('title', 'Tags'); ?>

<?php $__env->startSection('content'); ?>
<h1 class="text-2xl font-heading font-bold text-[#132129] dark:text-[#e8eef1] mb-6">Tags</h1>

<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
    <?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('tags.show', $tag)); ?>"
           class="bg-[#fdffff] dark:bg-[#132129] rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] p-4 hover:border-[#009494]/30 dark:hover:border-[#009494]/30 transition-colors group">
            <span class="inline-block px-2.5 py-1 text-xs rounded-md bg-[#e8eef1] dark:bg-[#1a282f] text-[#009494] group-hover:bg-[#009494] group-hover:text-white transition-colors font-medium"><?php echo e($tag->name); ?></span>
            <p class="mt-2 text-xs text-[#5a656b] dark:text-[#8a959b]"><?php echo e($tag->questions_count); ?> <?php echo e(Str::plural('question', $tag->questions_count)); ?></p>
        </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /work/resources/views/tags/index.blade.php ENDPATH**/ ?>