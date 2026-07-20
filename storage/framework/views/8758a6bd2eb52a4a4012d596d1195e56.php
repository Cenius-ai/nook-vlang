<?php $__env->startSection('title', 'Sign up'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-md mx-auto mt-12">
    <div class="bg-[#fdffff] dark:bg-[#132129] rounded-xl border border-[#d9dfe2] dark:border-[#2a353a] p-8">
        <h1 class="text-2xl font-heading font-bold text-[#132129] dark:text-[#e8eef1] mb-2">Create your account</h1>
        <p class="text-sm text-[#5a656b] dark:text-[#8a959b] mb-6">Join the Nook developer community.</p>

        <form method="POST" action="/api/register" x-data="registerForm()" @submit.prevent="submit">
            <?php echo csrf_field(); ?>

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-[#0b1418] dark:text-[#e8eef1] mb-1">Name</label>
                <input type="text" id="name" name="name" x-model="name" required maxlength="100"
                       class="w-full px-3 py-2 rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] bg-[#fdffff] dark:bg-[#0b1418] text-[#0b1418] dark:text-[#e8eef1] focus:outline-none focus:ring-2 focus:ring-[#009494] focus:border-transparent">
            </div>

            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-[#0b1418] dark:text-[#e8eef1] mb-1">Username</label>
                <input type="text" id="username" name="username" x-model="username" required maxlength="50" pattern="[A-Za-z0-9_-]+"
                       class="w-full px-3 py-2 rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] bg-[#fdffff] dark:bg-[#0b1418] text-[#0b1418] dark:text-[#e8eef1] focus:outline-none focus:ring-2 focus:ring-[#009494] focus:border-transparent">
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-[#0b1418] dark:text-[#e8eef1] mb-1">Email</label>
                <input type="email" id="email" name="email" x-model="email" required
                       class="w-full px-3 py-2 rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] bg-[#fdffff] dark:bg-[#0b1418] text-[#0b1418] dark:text-[#e8eef1] focus:outline-none focus:ring-2 focus:ring-[#009494] focus:border-transparent">
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-[#0b1418] dark:text-[#e8eef1] mb-1">Password</label>
                <input type="password" id="password" name="password" x-model="password" required minlength="8"
                       class="w-full px-3 py-2 rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] bg-[#fdffff] dark:bg-[#0b1418] text-[#0b1418] dark:text-[#e8eef1] focus:outline-none focus:ring-2 focus:ring-[#009494] focus:border-transparent">
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-medium text-[#0b1418] dark:text-[#e8eef1] mb-1">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" x-model="passwordConfirmation" required minlength="8"
                       class="w-full px-3 py-2 rounded-lg border border-[#d9dfe2] dark:border-[#2a353a] bg-[#fdffff] dark:bg-[#0b1418] text-[#0b1418] dark:text-[#e8eef1] focus:outline-none focus:ring-2 focus:ring-[#009494] focus:border-transparent">
            </div>

            <div x-show="error" x-text="error" class="mb-4 p-2 text-sm text-[#c9302d] bg-[#c9302d]/10 rounded-lg"></div>

            <button type="submit" :disabled="loading"
                    class="w-full py-2 px-4 rounded-lg bg-[#009494] text-white font-medium hover:bg-[#007a7a] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#009494] focus-visible:ring-offset-2 disabled:opacity-60 transition-colors">
                <span x-show="!loading">Create account</span>
                <span x-show="loading" class="inline-flex items-center gap-1">
                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    Creating…
                </span>
            </button>
        </form>

        <p class="mt-4 text-sm text-center text-[#5a656b] dark:text-[#8a959b]">
            Already have an account? <a href="<?php echo e(route('login')); ?>" class="text-[#009494] hover:underline font-medium">Sign in</a>
        </p>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function registerForm() {
    return {
        name: '', username: '', email: '', password: '', passwordConfirmation: '',
        error: '', loading: false,
        async submit() {
            this.error = '';
            if (this.password !== this.passwordConfirmation) { this.error = 'Passwords do not match'; return; }
            this.loading = true;
            try {
                const res = await fetch('/api/register', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || ''},
                    body: JSON.stringify({name: this.name, username: this.username, email: this.email, password: this.password, password_confirmation: this.passwordConfirmation})
                });
                if (!res.ok) {
                    const data = await res.json();
                    throw new Error(data.message || Object.values(data.errors || {}).flat().join(', ') || 'Registration failed');
                }
                window.location.href = '/';
            } catch (e) {
                this.error = e.message;
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /work/resources/views/auth/register.blade.php ENDPATH**/ ?>