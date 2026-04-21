<div class="min-h-[70vh] flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Welcome back</h1>
            <p class="mt-2 text-gray-500">Sign in to your account</p>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-8">
            <form wire:submit="login" class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email address</label>
                    <input wire:model="email" type="email" autocomplete="email" placeholder="you@example.com"
                           class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-400 @enderror">
                    @error('email') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                    <input wire:model="password" type="password" autocomplete="current-password" placeholder="••••••••"
                           class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-400 @enderror">
                    @error('password') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input wire:model="remember" type="checkbox" class="rounded border-gray-300 text-primary focus:ring-blue-500">
                        <span class="text-sm text-gray-600">Remember me</span>
                    </label>
                    <a href="/forgot-password" class="text-sm text-primary hover:underline font-medium">Forgot password?</a>
                </div>

                <button type="submit"
                        wire:loading.attr="disabled"
                        class="w-full py-3 px-4 inline-flex justify-center items-center gap-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors disabled:opacity-70">
                    <span wire:loading.remove>Sign in</span>
                    <span wire:loading>Signing in...</span>
                    <svg wire:loading class="animate-spin size-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-gray-500">
                Don't have an account?
                <a href="/register" class="text-primary hover:underline font-medium">Sign up for free</a>
            </p>
        </div>
    </div>
</div>
