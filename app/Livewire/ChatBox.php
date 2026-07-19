<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;
use App\Events\MessageSent;

use App\Models\Message;
use App\Models\Channel;
use App\Models\Attachment;

class ChatBox extends Component
{
    use WithFileUploads;

    public $channel;

    public $content = '';

    public $file;

    public $reference_date;

    public $editingMessage = null;

    public $editing = false;

    public $shouldScroll = false;

    public function mount(Channel $channel = null)
    {
        if (!$channel) {

            if (auth()->user()->role === 'admin') {

                $channel = Channel::where('active', true)->first();
            } else {

                $channel = auth()->user()
                    ->channels()
                    ->where('active', true)
                    ->first();
            }
        }

        $this->channel = $channel;
    }

    public $refreshKey = 0;

    #[On('refresh-chat')]
    public function refreshChat()
    {
        $this->refreshKey++;
    }



    public function sendMessage()
    {


        $this->validate([
            'content' => 'nullable',
            'file' => 'nullable|file|max:30720'
        ]);

        if (!$this->content && !$this->file) {
            return;
        }
        if ($this->editing) {

            $message = Message::findOrFail($this->editingMessage);

            $message->update([
                'content' => $this->content,
                'reference_date' => $this->reference_date,
            ]);
        } else {



            $message = Message::create([
                'channel_id' => $this->channel->id,
                'user_id' => auth()->id(),
                'content' => $this->content,
                'reference_date' => $this->reference_date,
            ]);

        $this->channel->touch();



            // Solo cuando es un mensaje nuevo
            $this->dispatch('scroll-bottom');
        }

        if ($this->file) {
            logger($this->file->getClientOriginalName());
            logger($this->file->getMimeType());

            try {

                $path = $this->file->store('attachments', 'public');

                Attachment::create([
                    'message_id' => $message->id,
                    'file_name' => $this->file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $this->file->getMimeType(),
                    'file_size' => $this->file->getSize(),
                    'uploaded_by' => auth()->id()
                ]);
            } catch (\Exception $e) {

                logger($e->getMessage());
            }
        }

        try {

            event(new MessageSent($message));
        } catch (\Exception $e) {

            logger($e->getMessage());
        }

        $this->reset([
            'content',
            'file',
            'reference_date',
        ]);

        $this->editing = false;

        $this->editingMessage = null;



        $this->dispatch('clear-date');
    }

    public function render()
    {
        $messages = Message::where('channel_id', $this->channel->id)
            ->with([
                'user',
                'attachments'
            ])
            ->latest()
            ->get()
            ->reverse();

        return view('livewire.chat-box', [
            'messages' => $messages
        ]);
    }

    public function editMessage($id)
    {
        if (auth()->user()->role !== 'admin') {
            return;
        }

        $message = Message::findOrFail($id);

        $this->editing = true;

        $this->editingMessage = $message->id;

        $this->content = $message->content;

        $this->reference_date = $message->reference_date;

        $message->update([
    'content' => $this->content,
    'reference_date' => $this->reference_date,
]);

$this->channel->touch();
    }
}
