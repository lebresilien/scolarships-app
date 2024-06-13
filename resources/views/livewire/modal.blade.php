<div class="fixed inset-0 flex items-center justify-center" style="display: {{ $isOpen ? 'flex' : 'none' }};">
    <div class="fixed inset-0 bg-black opacity-50" wire:click="hide"></div>
    <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
        <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Modal Title
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">
                            Your content goes here.{{ $course_id }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm" wire:click="hide">
                Close
            </button>
        </div>
    </div>
</div>
