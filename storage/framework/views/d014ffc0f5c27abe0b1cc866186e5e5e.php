<?php $__env->startSection('title', 'Notifications'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-heading font-bold text-[#132129] dark:text-[#e8eef1]">Notifications</h1>
        <?php if(auth()->user()->unreadNotifications->count() > 0): ?>
            <button onclick="markAllRead()" class="text-sm text-[#009494] hover:underline font-medium">Mark all as read</button>
        <?php endif; ?>
    </div>

    <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="mb-2 bg-[#fdffff] dark:bg-[#132129] rounded-lg border <?php echo e($notification->read_at ? 'border-[#d9dfe2] dark:border-[#2a353a]' : 'border-[#009494]/40 dark:border-[#009494]/40 bg-[#009494]/5 dark:bg-[#009494]/10'); ?> p-4 flex items-start gap-3">
            <?php if(!$notification->read_at): ?>
                <div class="w-2 h-2 rounded-full bg-[#009494] mt-1.5 shrink-0"></div>
            <?php else: ?>
                <div class="w-2 h-2 mt-1.5 shrink-0"></div>
            <?php endif; ?>
            <div class="flex-1 min-w-0">
                <p class="text-sm text-[#0b1418] dark:text-[#e8eef1]"><?php echo e($notification->data['message'] ?? 'Notification'); ?></p>
                <?php if(isset($notification->data['question_slug'])): ?>
                    <a href="<?php echo e(url('/questions/' . $notification->data['question_slug'])); ?>" class="text-xs text-[#009494] hover:underline">View question &rarr;</a>
                <?php endif; ?>
                <p class="text-xs text-[#5a656b] dark:text-[#5a656b] mt-1"><?php echo e($notification->created_at->diffForHumans()); ?></p>
            </div>
            <?php if(!$notification->read_at): ?>
                <button onclick="markRead('<?php echo e($notification->id); ?>')" class="text-xs text-[#009494] hover:underline shrink-0">Mark read</button>
            <?php endif; ?>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="text-center py-16 text-[#5a656b] dark:text-[#8a959b]">
            <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            <p class="font-medium">No notifications yet.</p>
        </div>
    <?php endif; ?>

    <div class="mt-4"><?php echo e($notifications->links()); ?></div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
async function markRead(id) {
    try {
        await fetch('/api/notifications/' + id + '/mark-read', {
            method: 'PUT',
            headers: {'Accept': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'}
        });
        window.location.reload();
    } catch(e) {}
}
async function markAllRead() {
    try {
        await fetch('/api/notifications/mark-all-read', {
            method: 'PUT',
            headers: {'Accept': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'}
        });
        window.location.reload();
    } catch(e) {}
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /work/resources/views/notifications/index.blade.php ENDPATH**/ ?>