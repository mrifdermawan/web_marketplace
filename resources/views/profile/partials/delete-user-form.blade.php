<section class="space-y-6"
         x-data="{ showModal: {{ $errors->userDeletion->isNotEmpty() ? 'true' : 'false' }} }">    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted.') }}
        </p>
    </header>

    <x-danger-button x-on:click="showModal = true">
        🗑️ {{ __('Delete Account') }}
    </x-danger-button>

    <!-- Modal -->
    <div x-show="showModal" x-cloak
         class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-lg max-w-md w-full p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-3">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="text-sm text-gray-600 mb-5">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm.') }}
            </p>

            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <div class="mb-4">
                    <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />
                    <x-text-input id="password" name="password" type="password" class="block w-full"
                                  placeholder="{{ __('Password') }}" />
                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                </div>

                <div class="flex justify-end gap-3">
                    <x-secondary-button type="button" x-on:click="showModal = false">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-danger-button>
                        {{ __('Delete Account') }}
                    </x-danger-button>
                </div>
            </form>
        </div>
    </div>
</section>
