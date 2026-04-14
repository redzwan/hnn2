<div class="min-h-[70vh] flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Reset password</h1>
            <p class="mt-2 text-gray-500">Enter your new password below.</p>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-8">
            @if($resetSuccess)
                <div class="text-center space-y-4">
                    <div class="inline-flex items-center justify-center size-14 bg-green-100 rounded-full">
                        <svg class="size-7 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-600">Your password has been reset successfully.</p>
                    <a href="/login" class="inline-block mt-2 py-3 px-6 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors">
                        Sign in
                    </a>
                </div>
            @else
                <form wire:submit="resetPassword" class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Email address</label>
                        <input wire:model="email" type="email" autocomplete="email"
                               class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50 @error('email') border-red-400 @enderror">
                        @error('email') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">New password</label>
                        <input wire:model="password" type="password" autocomplete="new-password" placeholder="Min. 8 characters"
                               class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-400 @enderror">
                        @error('password') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirm password</label>
                        <input wire:model="password_confirmation" type="password" autocomplete="new-password" placeholder="Re-enter password"
                               class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <button type="submit"
                            wire:loading.attr="disabled"
                            class="w-full py-3 px-4 inline-flex justify-center items-center gap-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors disabled:opacity-70">
                        <span wire:loading.remove>Reset password</span>
                        <span wire:loading>Resetting...</span>
                        <svg wire:loading class="animate-spin size-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
