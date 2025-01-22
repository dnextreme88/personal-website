<?php

namespace App\Livewire;

use App\Mail\SendEmailToMe;
use App\Models\ContactMe;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class CreateContactMe extends Component
{
    public $first_name;
    public $last_name;
    public $email;
    public $company;
    public $message;

    public function create_contact_me()
    {
        // Trim inputs first
        $this->first_name = trim($this->first_name);
        $this->last_name = trim($this->last_name);
        $this->email = trim($this->email);
        $this->company = trim($this->company);
        $this->message = trim($this->message);

        $this->validate([
            'first_name' => ['required', 'min:2', 'max:64'],
            'last_name' => ['required', 'min:2', 'max:64'],
            'email' => ['required', 'email', 'min:6', 'max:128'],
            'company' => ['sometimes', 'min:2', 'max:128'],
            'message' => ['required', 'min:10', 'max:255'],
        ]);

        $contact_me = ContactMe::create([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'company' => !empty($this->company) ? $this->company : null,
            'message' => $this->message,
        ]);

        Mail::to(env('MAIL_TO_PERSONAL_ADDRESS', 'cross.defect@gmail.com'))->send(new SendEmailToMe($contact_me));

        $this->reset();

        $this->dispatch('created-contact-me');
    }

    public function render()
    {
        return view('livewire.create-contact-me');
    }
}
