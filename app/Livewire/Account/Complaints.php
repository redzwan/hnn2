<?php

namespace App\Livewire\Account;

use App\Models\Complaint;
use Livewire\Component;

class Complaints extends Component
{
    public string $subject = '';

    public string $message = '';

    public bool $showForm = false;

    protected function rules(): array
    {
        return [
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:20'],
        ];
    }

    public function submit(): void
    {
        $this->validate();

        Complaint::create([
            'user_id' => auth()->id(),
            'subject' => $this->subject,
            'message' => $this->message,
        ]);

        $this->reset('subject', 'message', 'showForm');

        session()->flash('success', 'Your complaint has been submitted. We will get back to you soon.');
    }

    public function render()
    {
        $complaints = auth()->user()->complaints()->latest()->get();

        return view('livewire.account.complaints', compact('complaints'))
            ->layout('layouts.account');
    }
}
