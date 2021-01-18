<div>

    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Rooms
                </h2>
                <div class="ml-auto">
                    <button wire:click="showRecapModal" class="px-4 py-2 bg-cool-gray-600 outline-none rounded-md text-sm text-gray-200 hover:bg-cool-gray-800 focus:outline-none">
                        Recap
                        <svg wire:loading wire:target="toggleRecapModal" class="animate-spin -mr-1 ml-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                    <button wire:click="showModal" class="px-4 py-2 bg-cool-gray-600 outline-none rounded-md text-sm text-gray-200 hover:bg-cool-gray-800 focus:outline-none">
                        Create Room
                        <svg wire:loading wire:target="showModal" class="animate-spin -mr-1 ml-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <table class="w-full">
                    <thead class="font-bold text-sm uppercase h-6 text-left bg-cool-gray-700 text-white">
                        <tr>
                            <th class="p-4 w-15">#</th>
                            <th class="p-4">Room Name</th>
                            <th class="p-4 w-1/12">Code</th>
                            <th class="p-4 w-1/12">Status</th>
                            <th class="p-4 w-48"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rooms as $room)
                        <tr class="hover:bg-cool-gray-200">
                            <td class="p-4">{{ $loop->iteration }}</td> 
                            <td class="p-4">
                                <a href="{{ route('candidate', $room->id) }}">
                                    <p class="font-semibold text-lg hover:underline">{{ $room->room_name }}</p>
                                    <div class="leading-tight mt-1 text-sm text-cool-gray-600">
                                        <p>Total Candidate: {{ count($room->candidates) }}</p>
                                        <p>Description: {{ $room->description ?? '-' }}</p>
                                    </div>
                                </a>
                            </td>
                            <td class="p-4 font-bold">{{ $room->code }}</td>
                            <td class="p-4 font-bold {{ $room->status == 'OPEN' ? 'text-blue-600' : 'text-red-600' }}">{{ $room->status }}</td>
                            <td class="p-4">
                                <button wire:click="edit({{$room->id}})" class="px-3 py-1 bg-cool-gray-600 outline-none rounded-md text-sm text-gray-200 hover:bg-cool-gray-800 focus:outline-none">
                                    Edit
                                </button>
                                <button wire:click="confirmDelete({{$room->id}})" class="px-3 py-1 bg-red-600 outline-none rounded-md text-sm text-gray-200 hover:bg-red-800 focus:outline-none">
                                    Delete
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-20 text-center">
                                <p>Tidak ada data.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $rooms->links() }}

        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div class="fixed z-10 inset-0 overflow-y-auto {{ $showModal ? '' : 'hidden'}}">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-semibold text-gray-900 mb-5" id="modal-headline">
                            {{ !$editMode ? 'Create Room' : 'Update Room' }}
                        </h3>
                        <div class="mt-2">
                            <div class="group mb-3">
                                <label for="room_name" class="text-sm">Room Name <span class="text-red-600">*</span></label>
                                <input type="text" wire:model="room_name" name="room_name" placeholder="Input room name" class="form-input rounded-md shadow-sm mt-1 block w-full {{ $errors->has('room_name') ? 'border-red-600 border' : '' }}"/>
                                @error('room_name') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="group">
                                <label for="description" class="text-sm">Description:</label>
                                <textarea wire:model="description" name="description" placeholder="Room description" class="form-input rounded-md shadow-sm mt-1 block w-full {{ $errors->has('description') ? 'border-red-600 border' : '' }}"></textarea>
                                @error('description') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 mb-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" wire:click="{{ !$editMode ? 'store' : 'update' }}" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-cool-gray-600 text-base font-medium text-white hover:bg-cool-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Save
                        <svg wire:loading wire:target="store" class="animate-spin -mr-1 ml-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                    <button wire:click="closeModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete alert Modal -->
    <div class="fixed z-10 inset-0 overflow-y-auto {{ !$confirmDelete ? 'hidden' : '' }}">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-semibold text-gray-900" id="modal-headline">Delete Room?</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to delete this room?.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" wire:click="destroy" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Delete
                        <svg wire:loading wire:target="destroy" class="animate-spin -mr-1 ml-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                    <button type="button" wire:click="closeDeleteModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Show recap Modal -->
    <div class="fixed z-10 inset-0 overflow-y-auto {{ $showRecapModal ? '' : 'hidden'}}">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                <form wire:submit.prevent="exportRecap">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-semibold text-gray-900 mb-5" id="modal-headline">
                                Recap data
                            </h3>
                            <div class="mt-2">
                                <div class="group mb-3">
                                    <label for="room_name" class="text-sm">Recap title</label>
                                    <input type="text" wire:model="recapTitle" placeholder="Optional" class="form-input rounded-md shadow-sm mt-1 block w-full"/>
                                </div>

                                <div class="group mb-3">
                                    <label for="room_name" class="text-sm">Select room</label>
                                    @forelse($recapRooms as $recap)
                                    <div class="flex gap-4 mt-2">
                                        <div class="checkbox">
                                            <input type="checkbox" wire:model="recapIds" value="{{$recap->id}}"/>
                                        </div>
                                        <div class="label text-sm text-cool-gray-800">{{ $recap->room_name }}</div>
                                    </div>
                                    @empty
                                    <p>Nothing to recap.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 mb-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-cool-gray-600 text-base font-medium text-white hover:bg-cool-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Export as PDF
                            <svg wire:loading wire:target="exportRecap" class="animate-spin -mr-1 ml-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                        <button wire:click="closeRecapModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>                
                </form>
            </div>
        </div>
    </div>
</div>