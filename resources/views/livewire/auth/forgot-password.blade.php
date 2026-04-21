<div class="min-h-[70vh] flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Forgot password?</h1>
            <p class="mt-2 text-gray-500">No worries, we'll send you reset instructions.</p>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-8">
            @if($linkSent)
                <div class="text-center space-y-4">
                    <div class="inline-flex items-center justify-center size-14 bg-green-100 rounded-full">
                        <svg class="size-7 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-600">We've sent a password reset link to <span class="font-semibold text-gray-900">{{ $email }}</span></p>
                    <p class="text-xs text-gray-400">Didn't receive the email? Check your spam folder or try again.</p>
                    <button wire:click="$set('linkSent', false)" class="text-sm text-primary hover:underline font-medium">
                        Try another email
                    </button>
                </div>
            @else
                <form wire:submit="sendResetLink" class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Email address</label>
                        <input wire:model="email" type="email" autocomplete="email" placeholder="you@example.com"
                               class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-400 @enderror">
                        @error('email') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit"
                            wire:loading.attr="disabled"
                            class="w-full py-3 px-4 inline-flex justify-center items-center gap-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors disabled:opacity-70">
                        <span wire:loading.remove>Send reset link</span>
                        <span wire:loading>Sending...</span>
                        <svg wire:loading class="animate-spin size-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                    </button>
                </form>
            @endif

            <p class="mt-6 text-center text-sm text-gray-500">
                <a href="/login" class="text-primary hover:underline font-medium">Back to sign in</a>
            </p>
        </div>
    </div>
</div>
