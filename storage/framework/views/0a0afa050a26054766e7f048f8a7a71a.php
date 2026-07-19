<?php $__env->startSection('title', 'Ask a Question'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-heading font-bold text-[#132129] dark:text-[#e8eef1] mb-6">Ask a Question</h1>

    <div class="bg-[#fdffff] dark:bg-[#132129] rounded-xl border border-[#d9dfe2] dark:border-[#2a353a] p-6">
        <form method="POST" action="<?php echo e(route('questions.store')); ?>" x-data="askForm()" @submit.prevent="submit">
            <?php echo csrf_field(); ?>
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-[#0b1418] dark:text-[#e8eef1] mb-1">Title</label>
                <input type="text" id="title" name="title" x-model="title" required maxlength="300"
                       class="w-full px-3 py-2 rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] bg-[#f5fbff] dark:bg-[#0b1418] text-[#0b1418] dark:text-[#e8eef1] focus:outline-none focus:ring-2 focus:ring-[#009494] focus:border-transparent"
                       placeholder="e.g. How do I optimize SQL queries with Eloquent?">
            </div>

            <div class="mb-4">
                <label for="body" class="block text-sm font-medium text-[#0b1418] dark:text-[#e8eef1] mb-1">Body</label>
                <textarea id="body" name="body" x-model="body" rows="10" required maxlength="50000"
                          class="w-full px-3 py-2 rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] bg-[#f5fbff] dark:bg-[#0b1418] text-[#0b1418] dark:text-[#e8eef1] focus:outline-none focus:ring-2 focus:ring-[#009494] focus:border-transparent text-sm"
                          placeholder="Describe your question in detail. Include any relevant code, error messages, or context…"></textarea>
            </div>

            <div class="mb-6">
                <label for="tags" class="block text-sm font-medium text-[#0b1418] dark:text-[#e8eef1] mb-1">Tags</label>
                <input type="text" id="tags" name="tags" x-model="tags" maxlength="500"
                       class="w-full px-3 py-2 rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] bg-[#f5fbff] dark:bg-[#0b1418] text-[#0b1418] dark:text-[#e8eef1] focus:outline-none focus:ring-2 focus:ring-[#009494] focus:border-transparent"
                       placeholder="e.g. laravel, sql, eloquent (comma separated)">
                <p class="mt-1 text-xs text-[#5a656b] dark:text-[#8a959b]">Separate tags with commas. Existing tags will be reused.</p>
            </div>

            <div x-show="error" x-text="error" class="mb-4 p-2 text-sm text-[#c9302d] bg-[#c9302d]/10 rounded-lg"></div>

            <div class="flex items-center gap-3">
                <button type="submit" :disabled="loading"
                        class="px-6 py-2 rounded-lg bg-[#009494] text-white font-medium hover:bg-[#007a7a] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#009494] focus-visible:ring-offset-2 disabled:opacity-60 transition-colors">
                    <span x-show="!loading">Post Question</span>
                    <span x-show="loading">Posting…</span>
                </button>
                <a href="<?php echo e(route('questions.index')); ?>" class="px-4 py-2 text-sm rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] text-[#5a656b] dark:text-[#8a959b] hover:bg-[#e8eef1] dark:hover:bg-[#1a282f]">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function askForm() {
    return {
        title: '', body: '', tags: '', error: '', loading: false,
        async submit() {
            this.error = ''; this.loading = true;
            try {
                const res = await fetch('<?php echo e(route('questions.store')); ?>', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'},
                    body: JSON.stringify({title: this.title, body: this.body, tags: this.tags})
                });
                if (!res.ok) {
                    const d = await res.json();
                    throw new Error(d.message || Object.values(d.errors||{}).flat().join(', ') || 'Failed to post');
                }
                const data = await res.json();
                window.location.href = '/questions/' + data.slug;
            } catch (e) { this.error = e.message; this.loading = false; }
        }
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /work/resources/views/questions/create.blade.php ENDPATH**/ ?>