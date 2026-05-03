<div class="flex flex-col min-h-full -mx-6 -mb-6">

    {{-- Page Header --}}
    <div class="px-6 pt-6 pb-4">
        <h1 class="text-2xl font-semibold tracking-tight text-foreground">{{ __('Settings') }}</h1>
        <p class="text-sm text-muted-foreground mt-1">{{ __('Manage your global application settings.') }}</p>
    </div>

    {{-- Twitter-style Sticky Tab Bar --}}
    <div class="sticky top-0 z-10 bg-background/80 backdrop-blur-md border-b border-border">
        <nav class="flex px-6" aria-label="Settings tabs">
            <button
                wire:click="setTab('general')"
                class="relative px-4 py-3.5 text-sm font-medium transition-colors outline-none
                       {{ $activeTab === 'general'
                           ? 'text-foreground after:absolute after:bottom-0 after:left-0 after:right-0 after:h-0.5 after:bg-primary after:rounded-full'
                           : 'text-muted-foreground hover:text-foreground hover:bg-accent/50' }}"
            >
                {{ __('General') }}
            </button>
            <button
                wire:click="setTab('social')"
                class="relative px-4 py-3.5 text-sm font-medium transition-colors outline-none
                       {{ $activeTab === 'social'
                           ? 'text-foreground after:absolute after:bottom-0 after:left-0 after:right-0 after:h-0.5 after:bg-primary after:rounded-full'
                           : 'text-muted-foreground hover:text-foreground hover:bg-accent/50' }}"
            >
                {{ __('Social Media') }}
            </button>
            <button
                wire:click="setTab('google')"
                class="relative px-4 py-3.5 text-sm font-medium transition-colors outline-none
                       {{ $activeTab === 'google'
                           ? 'text-foreground after:absolute after:bottom-0 after:left-0 after:right-0 after:h-0.5 after:bg-primary after:rounded-full'
                           : 'text-muted-foreground hover:text-foreground hover:bg-accent/50' }}"
            >
                {{ __('Google API') }}
            </button>
        </nav>
    </div>

    @if (session('status'))
        <div class="mx-6 mt-4 p-4 text-sm text-green-700 rounded-xl bg-green-500/10 border border-green-500/20" role="alert">
            {{ session('status') }}
        </div>
    @endif

    {{-- Tab Content --}}
    <div class="flex-1 px-6 py-6">
        <form wire:submit.prevent="save" class="max-w-2xl space-y-8">

            {{-- General Tab --}}
            <div class="{{ $activeTab === 'general' ? 'block' : 'hidden' }} space-y-6">
                <div class="space-y-1">
                    <h2 class="text-base font-semibold text-foreground">{{ __('General Information') }}</h2>
                    <p class="text-sm text-muted-foreground">{{ __('Basic details about your website.') }}</p>
                </div>
                <div class="space-y-4">
                    <div class="space-y-1.5">
                        <label for="site_name" class="text-sm font-medium text-foreground">{{ __('Site Name') }}</label>
                        <x-input wire:model="general.site_name" id="site_name" type="text" placeholder="{{ __('My Laravel App') }}" />
                    </div>
                    <div class="space-y-1.5">
                        <label for="site_description" class="text-sm font-medium text-foreground">{{ __('Site Description') }}</label>
                        <textarea
                            wire:model="general.site_description"
                            id="site_description"
                            rows="3"
                            placeholder="{{ __('A short description of your website.') }}"
                            class="flex w-full rounded-xl border border-input bg-background px-3.5 py-2.5 text-sm text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 transition-colors"
                        ></textarea>
                    </div>
                </div>
            </div>

            {{-- Social Tab --}}
            <div class="{{ $activeTab === 'social' ? 'block' : 'hidden' }} space-y-6">
                <div class="space-y-1">
                    <h2 class="text-base font-semibold text-foreground">{{ __('Social Profiles') }}</h2>
                    <p class="text-sm text-muted-foreground">{{ __('Links to your social media accounts.') }}</p>
                </div>
                <div class="space-y-4">
                    <div class="space-y-1.5">
                        <label for="facebook" class="text-sm font-medium text-foreground">{{ __('Facebook URL') }}</label>
                        <x-input wire:model="social.facebook_url" id="facebook" type="url" placeholder="https://facebook.com/..." />
                    </div>
                    <div class="space-y-1.5">
                        <label for="twitter" class="text-sm font-medium text-foreground">{{ __('Twitter / X URL') }}</label>
                        <x-input wire:model="social.twitter_url" id="twitter" type="url" placeholder="https://x.com/..." />
                    </div>
                </div>
            </div>

            {{-- Google API Tab --}}
            <div class="{{ $activeTab === 'google' ? 'block' : 'hidden' }} space-y-6">
                <div class="space-y-1">
                    <h2 class="text-base font-semibold text-foreground">{{ __('Google OAuth Settings') }}</h2>
                    <p class="text-sm text-muted-foreground">{{ __('Configure Google API credentials to enable "Login with Google" on the login and register pages.') }}</p>
                </div>
                <div class="space-y-4">
                    <div class="space-y-1.5">
                        <label for="google_client_id" class="text-sm font-medium text-foreground">{{ __('Google Client ID') }}</label>
                        <x-input wire:model="google.google_client_id" id="google_client_id" type="text" placeholder="xxxxxxxxxxxx.apps.googleusercontent.com" />
                    </div>
                    <div class="space-y-1.5">
                        <label for="google_client_secret" class="text-sm font-medium text-foreground">{{ __('Google Client Secret') }}</label>
                        <x-input wire:model="google.google_client_secret" id="google_client_secret" type="password" placeholder="GOCSPX-xxxxxxxxxxxx" />
                    </div>
                </div>
                @if($google['google_client_id'] && $google['google_client_secret'])
                    <div class="flex items-center gap-2 p-3 text-sm text-green-700 rounded-xl bg-green-500/10 border border-green-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                        {{ __('Google Login is active. The "Continue with Google" button is visible on login and register pages.') }}
                    </div>
                @else
                    <div class="flex items-center gap-2 p-3 text-sm text-amber-700 rounded-xl bg-amber-500/10 border border-amber-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                        {{ __('Google Login is inactive. Fill in both Client ID and Client Secret to enable it.') }}
                    </div>
                @endif
            </div>

            <div class="pt-2">
                <x-button type="submit" variant="primary" size="sm">
                    {{ __('Save Changes') }}
                </x-button>
            </div>
        </form>
    </div>
</div>
