<?php

namespace App\Http\Livewire;

use App\Models\Room;
use Livewire\Component;
use Livewire\WithPagination;

class RoomLivewire extends Component
{
    use WithPagination;

    public $showModal = false;
    public $showRecapModal = false;

    public $editMode = false;
    public $editId = null;

    public $confirmDelete = false;
    public $deleteId = null;

    public $room_name = null;
    public $description = null;

    public $recapIds = [];
    public $recapTitle = null;

    protected $rules = [
        'room_name' => 'required|string',
        'description' => 'max:100',
    ];

    protected $messages = [
        'required' => ':attribute is required',
        'string' => ':attribute must be string',
        'max' => ':attribute max value is :max'
    ];

    protected $validationAttributes = [
        'room_name' => 'Room name',
        'description' => 'Room description'
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        return view('livewire.room', [
            'rooms' => Room::where('user_id', auth()->user()->id)->orderBy('created_at', 'DESC')->paginate(10),
            'recapRooms' => Room::where('user_id', auth()->user()->id)->where('status', 'CLOSE')->orderBy('created_at', 'ASC')->get()
        ]);
    }

    // Create room
    public function store()
    {
        $validatedData = $this->validate();
        $validatedData['user_id'] = auth()->user()->id;
        $validatedData['code'] = $this->genCode();

        if(Room::create($validatedData))
            $this->closeModal();
    }

    // Export recap
    public function exportRecap()
    {
        if(isset($this->recapIds) && count($this->recapIds) > 0) {
            return redirect()->route('recap', [
                'title' => $this->recapTitle ?? 'Recap Data',
                'ids' => implode(',', $this->recapIds)
            ]);
        } else {
            $this->closeRecapModal();
        }
    }

    // Show edit modal with specific data
    public function edit($roomId)
    {
        if(isset($roomId) && $roomId != null) {
            $room = Room::where('id', $roomId)->first();

            if($room) {
                $this->room_name = $room->room_name;
                $this->description = $room->description;
                $this->editMode = true;
                $this->editId = $room->id;
    
                $this->showModal();
            }
        }
    }

    // Update room
    public function update()
    {
        $validatedData = $this->validate();

        $room = Room::where('id', $this->editId)->first();
        if($room->update($validatedData)) {
            $this->resetPage();
            $this->resetData();
            $this->closeModal();
        }
    }

    // Confirm delete modal
    public function confirmDelete($roomId)
    {
        if(isset($roomId) && $roomId != null) {
            $this->deleteId = $roomId;
            $this->confirmDelete = true;
        }
    }

    // Delete room
    public function destroy()
    {
        $room = Room::where('id', $this->deleteId)->first();
        if($room->delete()) {
            $this->closeDeleteModal();
            $this->resetPage();
        }
    }

    public function resetData()
    {
        $this->editMode = false;
        $this->editId = null;
        $this->deleteId = null;
        $this->room_name = null;
        $this->description = null;
    }

    public function showModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetData();
    }

    public function closeDeleteModal()
    {
        $this->deleteId = null;
        $this->confirmDelete = false;
    }

    public function showRecapModal()
    {
        $this->showRecapModal = true;
    }

    public function closeRecapModal()
    {
        $this->showRecapModal = false;
    }

    // Generate unique code for room
    private function genCode($length = 6) {
        $availableChar = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $availableChar[rand(0, strlen($availableChar) - 1)];
        }
        return $code;
    }
}
