<x-layout title="Email verification" class="max-w-3xl mx-auto flex flex-col justify-center">
    <div class="flex flex-col gap-6 py-8 p-4 bg-card/80 backdrop-blur-md rounded-2xl shadow-sm border border-border">
        <div class="text-center">
            <h1 class="text-2xl font-semibold tracking-tight">Verify email</h1>
            <p class="mt-2 text-sm text-muted-foreground text-balance">
                Please verify your email address by clicking on the link we have emailed to you to access this Page.
            </p>
        </div>

        @if (($status ?? session('status')))
            <div class="text-center text-sm font-medium text-green-600 dark:text-green-400">
                {{ session('status') }}
            </div>
        @endif

        <div class="space-y-6 text-center" x-data="{ processing: false }">
            <form method="POST" action="{{ route('verification.send') }}" @submit="processing = true">
                @csrf
                <x-ui.button type="submit" x-bind:disabled="processing" class="w-full">
                    <span x-show="processing" style="display: none;">
                        <x-lucide-loader-circle defer class="animate-spin" />
                    </span>
                    Resend verification email
                </x-ui.button>
            </form>
        </div>
    </div>
</x-layout>
