<div id="default-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full" style="display: {{ $isOpen ? 'flex' : 'none' }};">
    <div class="fixed inset-0 bg-black opacity-50" wire:click="hide"></div>    
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl text-center font-semibold text-gray-900 dark:text-white">
                    Enregistrement des notes: {{ $course ? ucfirst($course['name']) : '' }}
                </h3>
                <button wire:click="hide" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="default-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            @if (session()->has('message'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="bg-green-500 p-2 mb-4">
                    {{ session('message') }}
                </div>
            @endif
            <!-- Modal body -->
            <form wire:submit.prevent="save">
                <div class="p-4 md:p-5 space-y-4">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-900 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" style="padding-top: 20px; padding-bottom: 20px;">Noms et Prénoms</th>
                                <th scope="col" style="padding-top: 20px; padding-bottom: 20px;">Note{{ $seq ? '('.$seq['name'].')' : '' }}</th>
                            </tr>
                        </thead>
                        <tbody style="">
                            @foreach($students as $index => $student)
                                <tr wire:key="tr-{{ $index }}">
                                    <td style="padding-top: 10px; padding-bottom: 10px">{{ $student['name'] }}</td>
                                    <td style="padding-top: 10px; padding-bottom: 10px" wire:key="field-{{ $index }}">
                                        <input type="number" wire:model="form.{{ $index }}.value" @if(!$seq['status']) disabled @endif />
                                        <input type="hidden" value="{{ $student['id'] }}" wire:model="form.{{ $index }}.policy" />
                                    </td>
                                </tr>
                            @endforeach 
                        </tbody>
                    </table>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center gap-x-3 p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button type="submit" class="py-2.5 px-2.5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Enregistrer</button>
                    <button type="button" class="py-2.5 px-2.5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700" wire:click="hide">Fermer</button>
                </div>
            </form>
        </div>
    </div>
</div>