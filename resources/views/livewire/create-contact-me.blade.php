<div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <x-slot name="nav_menu">
        <x-navigation-menu />
    </x-slot>

    <div class="relative bg-white dark:bg-gray-800">
        <div class="lg:absolute lg:inset-0 lg:left-1/2">
            {{-- TODO: TO REPLACE IMAGE WITH AN IMAGE OF ME PROBABLY IN FRONT OF A COMPUTER? --}}
            <img class="object-cover h-64 w-full bg-gray-50 sm:h-80 lg:absolute lg:h-full" src="https://images.unsplash.com/photo-1559136555-9303baea8ebd?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&crop=focalpoint&fp-x=.4&w=2560&h=3413&&q=80" alt="Contact Me background image" title="Contact Me background image" loading="lazy" />
        </div>

        <div class="pt-16 pb-24 sm:pb-32 sm:pt-24 lg:mx-auto lg:grid lg:max-w-7xl lg:grid-cols-2 lg:pt-32">
            <div class="px-6 lg:px-8">
                <div class="max-w-2xl mx-auto lg:mx-0 lg:max-w-lg">
                    <h2 class="text-4xl font-semibold tracking-tight text-gray-800 dark:text-gray-200 sm:text-5xl">How can I help you?</h2>

                    <p class="mt-6 text-lg text-gray-600 lg:mt-8 dark:text-gray-400">Thank you for your interest in contacting me. In order to help you further, please fill out the form below. I will reach out to you within a few days!</p>

                    <p class="mt-6 text-gray-600 dark:text-gray-400">All fields marked with <x-red-asterisk /> are required.</p>

                    <form wire:submit.prevent="create_contact_me" class="mt-12">
                        <div class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2">
                            <div>
                                <x-forms.input-label is_required="true" value="First name" for="first_name" />

                                <div class="mt-3">
                                    <x-forms.input-text class="w-full" for="first_name" placeholder_text="Juan" />
                                </div>

                                <x-forms.input-error class="mt-2" for="first_name" />
                            </div>

                            <div>
                                <x-forms.input-label is_required="true" value="Last name" for="last_name" />

                                <div class="mt-3">
                                    <x-forms.input-text class="w-full" for="last_name" placeholder_text="Santos" />
                                </div>

                                <x-forms.input-error class="mt-2" for="last_name" />
                            </div>

                            <div class="sm:col-span-2">
                                <x-forms.input-label is_required="true" value="Email" for="email" />

                                <div class="mt-3">
                                    <x-forms.input-text class="w-full" for="email" placeholder_text="username@domain.com" />
                                </div>

                                <x-forms.input-error class="mt-2" for="email" />
                            </div>

                            <div class="sm:col-span-2">
                                <x-forms.input-label value="Company" for="company" />

                                <div class="mt-3">
                                    <x-forms.input-text class="w-full" for="company" />
                                </div>

                                <x-forms.input-error class="mt-2" for="company" />
                            </div>

                            <div class="sm:col-span-2">
                                <div class="flex items-center justify-between">
                                    <x-forms.input-label is_required="true" value="Message" for="message" />

                                    <p class="text-xs text-gray-600 dark:text-gray-400" id="message-description">Max 255 characters</p>
                                </div>

                                <div class="mt-3">
                                    <textarea
                                        wire:model="message"
                                        class="block w-full rounded-md bg-white px-3.5 py-2 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-blue-400 resize-none" placeholder="Please enter your message here"
                                        maxlength="255"
                                        rows="5"
                                        aria-describedby="message-description"
                                    >
                                    </textarea>
                                </div>

                                <x-forms.input-error class="mt-2" for="message" />
                            </div>
                        </div>

                        <div class="flex justify-end mt-10 lg:mt-16">
                            <button
                                class="rounded-md bg-green-600 dark:bg-green-500 min-w-[130px] px-3.5 py-2.5 text-center text-sm font-semibold text-white shadow-sm hover:bg-green-500 dark:hover:bg-green-700 focus-visible:outline transition duration-300"
                                type="submit"
                            >
                                <span wire:loading.flex wire:target="create_contact_me" class="justify-self-center">
                                    <x-loading-indicator
                                        :loader_color_bg="'fill-gray-200'"
                                        :loader_color_spin="'fill-gray-200'"
                                        :showText="true"
                                        :size="4"
                                        :text="'Sending'"
                                        :text_color="'text-white'"
                                    />
                                </span>

                                <span wire:loading.remove wire:target="create_contact_me">Send message</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
