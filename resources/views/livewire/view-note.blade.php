<div class="flex flex-wrap gap-y-3 gap-x-3">
   @foreach($record->group->courses as $coursee)
      <div class="max-w-xs p-6 bg-white text-wrap border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
         <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
            {{ $coursee->name }}
         </h5>
         <div class="flex flex-col gap-y-2">
            @foreach($sequences as $sequence)
               <button
                  type="button"
                  wire:click="show({{$coursee}}, {{$sequence}})"
               >
                  {{ $sequence->name }}
               </button>
            @endforeach
         </div>
      </div>
   @endforeach

   @include('livewire.modal')
</div>