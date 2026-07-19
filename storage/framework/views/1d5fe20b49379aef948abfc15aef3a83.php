<?php $__env->startSection('title', $user->name . ' — Profile'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto">
    <div class="bg-[#fdffff] dark:bg-[#132129] rounded-xl border border-[#d9dfe2] dark:border-[#2a353a] p-6 mb-6">
        <div class="flex items-start gap-4">
            <div class="w-16 h-16 rounded-full bg-[#009494] flex items-center justify-center text-white text-2xl font-bold shrink-0"><?php echo e(strtoupper(substr($user->name, 0, 1))); ?></div>
            <div>
                <h1 class="text-xl font-heading font-bold text-[#132129] dark:text-[#e8eef1]"><?php echo e($user->name); ?></h1>
                <p class="text-sm text-[#5a656b] dark:text-[#8a959b]">@ <?php echo e($user->username); ?></p>
                <?php if($user->bio): ?>
                    <p class="mt-2 text-sm text-[#0b1418] dark:text-[#e8eef1]"><?php echo e($user->bio); ?></p>
                <?php endif; ?>
                <div class="flex gap-4 mt-3 text-sm text-[#5a656b] dark:text-[#8a959b]">
                    <span><strong class="text-[#0b1418] dark:text-[#e8eef1]"><?php echo e($stats['questions_count']); ?></strong> questions</span>
                    <span><strong class="text-[#0b1418] dark:text-[#e8eef1]"><?php echo e($stats['answers_count']); ?></strong> answers</span>
                    <span><strong class="text-[#0b1418] dark:text-[#e8eef1]"><?php echo e($stats['total_votes']); ?></strong> votes</span>
                </div>
                <p class="text-xs text-[#5a656b] dark:text-[#5a656b] mt-2">Member since <?php echo e($user->created_at->format('F Y')); ?></p>
            </div>
        </div>
        <?php if(auth()->id() === $user->id): ?>
            <div class="mt-4 pt-4 border-t border-[#d9dfe2] dark:border-[#2a353a]">
                <form method="POST" action="<?php echo e(route('profile.update')); ?>" x-data="editProfile()" @submit.prevent="submit">
                    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                    <h3 class="text-sm font-heading font-semibold text-[#132129] dark:text-[#e8eef1] mb-3">Edit Profile</h3>
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-[#5a656b] mb-1">Name</label>
                        <input type="text" name="name" x-model="name" required maxlength="100"
                               class="w-full px-3 py-1.5 text-sm rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] bg-[#f5fbff] dark:bg-[#0b1418] text-[#0b1418] dark:text-[#e8eef1] focus:outline-none focus:ring-2 focus:ring-[#009494]">
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-[#5a656b] mb-1">Bio</label>
                        <textarea name="bio" x-model="bio" rows="2" maxlength="500"
                                  class="w-full px-3 py-1.5 text-sm rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] bg-[#f5fbff] dark:bg-[#0b1418] text-[#0b1418] dark:text-[#e8eef1] focus:outline-none focus:ring-2 focus:ring-[#009494]"></textarea>
                    </div>
                    <button type="submit" class="px-4 py-1.5 text-sm rounded-lg bg-[#009494] text-white hover:bg-[#007a7a]">Save</button>
                </form>
            </div>
        <?php endif; ?>
    </div>

    
    <h2 class="text-lg font-heading font-semibold text-[#132129] dark:text-[#e8eef1] mb-3">Questions</h2>
    <?php $__empty_1 = true; $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="mb-2 bg-[#fdffff] dark:bg-[#132129] rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] p-4">
            <div class="flex items-center gap-3">
                <span class="text-sm font-medium tabular-nums min-w-[40px] <?php echo e($q->accepted_answer_id ? 'text-[#009494]' : 'text-[#5a656b] dark:text-[#8a959b]'); ?>"><?php echo e($q->votes_count); ?> votes</span>
                <span class="text-sm font-medium tabular-nums min-w-[40px] <?php echo e($q->accepted_answer_id ? 'text-[#009494] bg-[#009494]/10 px-2 py-0.5 rounded' : 'text-[#5a656b] dark:text-[#8a959b]'); ?>"><?php echo e($q->answers_count); ?> answers</span>
                <a href="<?php echo e(route('questions.show', $q)); ?>" class="text-sm font-medium text-[#009494] hover:underline truncate"><?php echo e($q->title); ?></a>
                <span class="text-xs text-[#5a656b] dark:text-[#8a959b] ml-auto shrink-0"><?php echo e($q->created_at->diffForHumans()); ?></span>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="text-sm text-[#5a656b] py-4">No questions yet.</p>
    <?php endif; ?>

    
    <h2 class="text-lg font-heading font-semibold text-[#132129] dark:text-[#e8eef1] mb-3 mt-8">Answers</h2>
    <?php $__empty_1 = true; $__currentLoopData = $answers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="mb-2 bg-[#fdffff] dark:bg-[#132129] rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] p-4">
            <div class="flex items-center gap-3">
                <span class="text-sm font-medium tabular-nums min-w-[40px] text-[#5a656b] dark:text-[#8a959b]"><?php echo e($a->votes_count); ?> votes</span>
                <a href="<?php echo e(route('questions.show', $a->question)); ?>" class="text-sm font-medium text-[#009494] hover:underline truncate">Re: <?php echo e($a->question->title); ?></a>
                <span class="text-xs text-[#5a656b] dark:text-[#8a959b] ml-auto shrink-0"><?php echo e($a->created_at->diffForHumans()); ?></span>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="text-sm text-[#5a656b] py-4">No answers yet.</p>
    <?php endif; ?>
</div>

<?php if(auth()->id() === $user->id): ?>
<?php $__env->startPush('scripts'); ?>
<script>
function editProfile() {
    return {
        name: '<?php echo e($user->name); ?>',
        bio: '<?php echo e($user->bio ?? ''); ?>',
        async submit(e) {
            try {
                const form = e.target.closest('form');
                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'},
                    body: JSON.stringify({name: this.name, bio: this.bio, _method: 'PUT'})
                });
                if (res.ok) window.location.reload();
            } catch (e) {}
        }
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /work/resources/views/users/profile.blade.php ENDPATH**/ ?>