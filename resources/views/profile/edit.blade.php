<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Subscription Plan') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ __("Your current plan and upgrade options.") }}
                            </p>
                        </header>

                        <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Current Plan</p>
                                <p class="text-2xl font-black text-gray-900">{{ auth()->user()->planLabel() }}</p>
                            </div>
                            <div>
                                @if(auth()->user()->isStarter())
                                    <a href="{{ route('payment.upgrade', ['plan' => 'personal']) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Upgrade to Personal
                                    </a>
                                @elseif(auth()->user()->isPersonal())
                                    <a href="{{ route('payment.upgrade', ['plan' => 'profesional']) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Upgrade to Profesional
                                    </a>
                                @else
                                    <span class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 text-xs font-bold rounded-full uppercase tracking-widest">
                                        Ultimate Plan Active
                                    </span>
                                @endif
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

