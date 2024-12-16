<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;
use App\Events\MessageSent;
class ChatController
{
    public function index(User $user)
    {
        return Message::query()
            ->where(function ($query) use ($user) {
                $query->where('sender_id', auth()->id())
                    ->where('receiver_id', $user->id);
            })
            ->orWhere(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                    ->where('receiver_id', auth()->id());
            })
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();
    }
    public function show(User $user)
    {
        return view('chat', [
            'user' => $user
        ]);
    }
    public function sendMessage(Request $request, User $user)
    {
         $request->validate([
            'message' => 'required|string'
         ]);
        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $user->id,
            'text' => $request->input('message')
        ]);
        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message);
    }


    public function getMessages(User $user){
        $messages = Message::query()
            ->where(function ($query) use ($user) {
                $query->where('sender_id', auth()->id())
                    ->where('receiver_id', $user->id);
            })
            ->orWhere(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                    ->where('receiver_id', auth()->id());
            })
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();
        return response()->json($messages);

    }
}
