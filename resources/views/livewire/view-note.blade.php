<div class="flex flex-wrap gap-y-3 gap-x-3">
   @foreach($record->group->courses as $coursee)
      <x-filament::section>
         <x-slot name="heading">
            {{ ucfirst($coursee->name) }}
         </x-slot>
      
         <div class="flex flex-col gap-y-2">
            @foreach($sequences as $sequence)
               <x-filament::button 
                  wire:click="show({{$coursee}}, {{$sequence}})"
               >
                  {{ $sequence->name }}
               </x-filament::button>
            @endforeach
         </div>
      </x-filament::section>
   @endforeach
   
   <x-filament::modal id="modal" sticky-header :autofocus="false" width="xl">

      <x-slot name="heading">
         Enregistrement des notes
      </x-slot>

      <x-slot name="description">
         {{ $course ? ucfirst($course['name']).'('.$se['name'].')' : '' }}
      </x-slot>
   
      @if($se && !$se['status'])
         <div class="flex items-center p-2 mb-1 gap-x-2 text-sm rounded-lg" role="alert" style="background-color: rgb(254 252 232); color: rgb(202 138 4);">
            <x-filament::icon
               icon="heroicon-o-information-circle"
               class="w-10 text-yellow-50 bg-yellow-50"
            />
            <div>
               <span class="font-medium">La séquence a été clôturée. Vous ne pouvez pas effectuer de modification</span>
            </div>
         </div>
      @endif

      <form wire:submit.prevent="save">
         <table class="w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
               <tr>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 tracking-wider">Nom</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 tracking-wider">Note</th>
               </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
               @foreach($students as $index => $student)
                  <tr wire:key="tr-{{ $index }}">
                     <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $student['name'] }}</td>
                     <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" wire:key="field-{{ $index }}">
                        <input type="hidden" value="{{ $student['id'] }}" wire:model="form.{{ $index }}.policy" />
                           @if($se && $se['status'])
                              <x-filament::input.wrapper>
                                 <x-filament::input
                                    type="number"
                                    wire:model="form.{{ $index }}.value"
                                 />
                              </x-filament::input.wrapper>
                           @else
                              <x-filament::input.wrapper>
                                 <x-filament::input
                                    type="number"
                                    wire:model="form.{{ $index }}.value"
                                    disabled
                                 />
                              </x-filament::input.wrapper>
                           @endif
                     </td>
                  </tr>
               @endforeach 
            </tbody>
         </table>
      </form>
      <x-slot name="footerActions">
         <div class="flex justify-end gap-x-2 w-full">
         @if($se && $se['status'])
            <x-filament::button wire:click="save">
               Enregistrer
            </x-filament::button>
         @else
            <x-filament::button disabled>
               Enregistrer
            </x-filament::button>
         @endif
         <x-filament::button color="danger" wire:click="hide">
            Annuler
         </x-filament::button>
         </div>
      </x-slot>
   </x-filament::modal>
</div>