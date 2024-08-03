<x-authentication-layout>
    <!-- Welcome Message -->
    <h1
        x-data
        x-init="$el.style.transform = 'translateX(-100%)'; $el.style.opacity = 0;
                setTimeout(() => {
                    $el.style.transition = 'transform 0.5s ease, opacity 0.5s ease';
                    $el.style.transform = 'translateX(0)';
                    $el.style.opacity = 1;
                }, 100);"
        class="text-3xl text-slate-800 font-bold mb-6">
        {{ __('Welcome back!') }}
    </h1>

    @if (session('status'))
        <!-- Status Message -->
        <div
            x-data
            x-init="$el.style.transform = 'translateX(-100%)'; $el.style.opacity = 0;
                    setTimeout(() => {
                        $el.style.transition = 'transform 0.5s ease, opacity 0.5s ease';
                        $el.style.transform = 'translateX(0)';
                        $el.style.opacity = 1;
                    }, 200);"
            class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>
    @endif

    <!-- Form -->
    <form
        x-data
        x-init="$el.style.transform = 'translateX(-100%)'; $el.style.opacity = 0;
                setTimeout(() => {
                    $el.style.transition = 'transform 0.5s ease, opacity 0.5s ease';
                    $el.style.transform = 'translateX(0)';
                    $el.style.opacity = 1;
                }, 300);"
        method="POST" wire:submit.prevent='login'>
        @csrf
        <div class="space-y-4 w-full">
            <div>
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" type="email" name="email" :value="old('email')" required autofocus />
            </div>
            <div>
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" type="password" name="password" required autocomplete="current-password" />
            </div>
        </div>
        <div class="flex items-center justify-between mt-6">
            @if (Route::has('password.request'))
                <div class="mr-1">
                    <a class="text-sm underline hover:no-underline" href="{{ route('password.request') }}">
                        {{ __('Forgot Password?') }}
                    </a>
                </div>
            @endif
            <x-button class="ml-3">
                {{ __('Sign in') }}
            </x-button>
        </div>
    </form>

    <x-validation-errors class="mt-4" />

    @error('login')
        <span
            x-data
            x-init="$el.style.transform = 'translateX(-100%)'; $el.style.opacity = 0;
                    setTimeout(() => {
                        $el.style.transition = 'transform 0.5s ease, opacity 0.5s ease';
                        $el.style.transform = 'translateX(0)';
                        $el.style.opacity = 1;
                    }, 400);"
            style="color: red;">
            {{ $message }}
        </span>
    @enderror
</x-authentication-layout>
