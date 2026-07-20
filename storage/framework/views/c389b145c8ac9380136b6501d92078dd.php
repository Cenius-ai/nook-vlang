<?php $__env->startSection('title', 'Flag Moderation'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-heading font-bold text-[#132129] dark:text-[#e8eef1]">Flag Moderation</h1>
            <p class="text-sm text-[#5a656b] dark:text-[#8a959b]">Review and resolve flagged content.</p>
        </div>
        <span class="px-3 py-1 text-sm rounded-full bg-[#c9302d]/10 text-[#c9302d] font-medium">
            <?php echo e($flags->where('status', 'pending')->count()); ?> pending
        </span>
    </div>

    <?php $__empty_1 = true; $__currentLoopData = $flags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $flag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="mb-3 bg-[#fdffff] dark:bg-[#132129] rounded-lg border <?php echo e($flag->status === 'pending' ? 'border-[#c9302d]/40 dark:border-[#c9302d]/40' : 'border-[#d9dfe2] dark:border-[#2a353a]'); ?> p-5">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-2 py-0.5 text-xs rounded-full font-medium <?php echo e($flag->status === 'pending' ? 'bg-[#c9302d]/10 text-[#c9302d]' : 'bg-[#e8eef1] dark:bg-[#1a282f] text-[#5a656b] dark:text-[#8a959b]'); ?>">
                            <?php echo e(ucfirst($flag->status)); ?>

                        </span>
                        <span class="text-xs text-[#5a656b] dark:text-[#8a959b]">
                            Flagged by <a href="<?php echo e(route('users.profile', $flag->user)); ?>" class="text-[#009494] hover:underline"><?php echo e($flag->user->name); ?></a>
                            <?php echo e($flag->created_at->diffForHumans()); ?>

                        </span>
                    </div>

                    <p class="text-sm text-[#0b1418] dark:text-[#e8eef1] mb-2"><strong>Reason:</strong> <?php echo e($flag->reason); ?></p>

                    
                    <?php $content = $flag->flaggable; ?>
                    <?php if($content): ?>
                        <div class="p-3 rounded-lg bg-[#e8eef1] dark:bg-[#1a282f] text-sm text-[#5a656b] dark:text-[#8a959b] mb-2">
                            <span class="text-xs font-medium uppercase tracking-wide text-[#5a656b] mb-1 block">
                                <?php echo e(class_basename($content)); ?>

                                <?php if($content instanceof \App\Models\Answer): ?>
                                    on "<a href="<?php echo e(route('questions.show', $content->question)); ?>" class="text-[#009494] hover:underline"><?php echo e(\Illuminate\Support\Str::limit($content->question->title, 60)); ?></a>"
                                <?php endif; ?>
                            </span>
                            <p class="whitespace-pre-wrap break-words"><?php echo e(\Illuminate\Support\Str::limit($content instanceof \App\Models\Question ? $content->title : $content->body, 200)); ?></p>
                        </div>
                    <?php else: ?>
                        <p class="text-xs text-[#5a656b] italic">[Content has been deleted]</p>
                    <?php endif; ?>

                    <?php if($flag->resolver): ?>
                        <p class="text-xs text-[#5a656b]">
                            Resolved by <?php echo e($flag->resolver->name); ?> <?php echo e($flag->resolved_at?->diffForHumans()); ?>

                        </p>
                    <?php endif; ?>
                </div>

                <?php if($flag->status === 'pending'): ?>
                    <div class="flex flex-col gap-2 shrink-0">
                        <button onclick="resolveFlag(<?php echo e($flag->id); ?>, 'dismiss')"
                                class="px-3 py-1.5 text-xs rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] text-[#5a656b] dark:text-[#8a959b] hover:bg-[#e8eef1] dark:hover:bg-[#1a282f] font-medium transition-colors">
                            Dismiss
                        </button>
                        <button onclick="resolveFlag(<?php echo e($flag->id); ?>, 'delete')"
                                class="px-3 py-1.5 text-xs rounded-lg bg-[#c9302d] text-white hover:bg-[#a82826] font-medium transition-colors">
                            Delete Content
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="text-center py-16 text-[#5a656b] dark:text-[#8a959b]">
            <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            <p class="font-medium">No flags to review.</p>
        </div>
    <?php endif; ?>

    <div class="mt-4"><?php echo e($flags->links()); ?></div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
async function resolveFlag(id, action) {
    if (action === 'delete' && !confirm('Delete this content? This cannot be undone.')) return;
    try {
        const res = await fetch('/api/admin/flags/' + id + '/resolve', {
            method: 'PUT',
            headers: {'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'},
            body: JSON.stringify({action: action})
        });
        if (res.ok) window.location.reload();
    } catch(e) { alert('Error resolving flag.'); }
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /work/resources/views/admin/flags/index.blade.php ENDPATH**/ ?>