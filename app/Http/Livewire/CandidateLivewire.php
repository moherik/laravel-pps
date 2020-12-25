<?php

namespace App\Http\Livewire;

use App\Models\Candidate;
use App\Models\Room;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class CandidateLivewire extends Component
{
    use WithFileUploads;

    public $room;

    public $showModal = false;
    public $editMode = false;
    public $editId = null;
    public $confirmDelete = false;
    public $deleteId = null;

    public $order = null;
    public $name = null;
    public $image = null;

    protected $rules = [
        'order' => 'required',
        'name' => 'required|string',
        'image' => 'nullable|image|max:1024',
    ];

    protected $validationAttributes = [
        'order' => 'Order number',
        'name' => 'Candidate name',
        'image' => 'Photo'
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function mount($id)
    {
        $this->room = Room::where('id', $id)->first();
    }

    public function render()
    {
        return view('livewire.candidate', [
            'candidates' => Candidate::where('room_id', $this->room->id)->orderBy("order", "ASC")->get(), 
        ]);
    }

    public function store()
    {
        $validatedData = $this->validate();
        $validatedData['room_id'] = $this->room->id;
        
        if($this->image != null) {
            if($uploadedImage = $this->image->storeAs('photos', time() . '-candidate.png', 'public'))
                $validatedData['image'] = $uploadedImage;
        }

        if(Candidate::create($validatedData))
            $this->closeModal();
    }

    public function edit($candidateId)
    {
        if(isset($candidateId) && $candidateId != null) {
            $candidate = Candidate::where('id', $candidateId)->first();

            if($candidate) {
                $this->order = $candidate->order;
                $this->name = $candidate->name;
                $this->editId = $candidate->id;
                $this->editMode = true;
    
                $this->showModal();
            }
        }
    }

    public function update()
    {
        $validatedData = $this->validate();

        if($this->image != null) {
            if($uploadedImage = $this->image->storeAs('photos', time() . '-candidate.png', 'public'))
                $validatedData['image'] = $uploadedImage;
        } else {
            $validatedData = array_diff_key($validatedData, ['image' => '']);
        }

        $candidate = Candidate::where('id', $this->editId)->first();
        if($candidate) {
            if($this->image != null)
                Storage::disk('public')->delete($candidate->image);
    
            $candidate->update($validatedData);

            $this->resetData();
            $this->closeModal();
        }
    }

    public function confirmDelete($candidateId)
    {
        if(isset($candidateId) && $candidateId != null) {
            $this->deleteId = $candidateId;
            $this->confirmDelete = true;
        }
    }

    public function destroy()
    {
        $candidate = Candidate::where('id', $this->deleteId)->first();
        if($candidate->delete()) {
            Storage::disk('public')->delete($candidate->image);
            $this->closeDeleteModal();
        }
    }

    public function resetData()
    {
        $this->editMode = false;
        $this->editId = null;
        $this->deleteId = null;
        $this->order = null;
        $this->name = null;
        $this->image = null;
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
}
