<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\Message;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NotificationController extends Controller
{
    public function storeMessage(Request $request): RedirectResponse
    {
        Message::create($request->validate([
            'sender_name' => ['required', 'string', 'max:100'],
            'recipient_team' => ['required', 'string', 'max:100'],
            'subject' => ['required', 'string', 'max:150'],
            'body' => ['required', 'string', 'max:1200'],
            'priority' => ['required', Rule::in(['normal', 'urgent'])],
        ]) + ['status' => 'open']);

        return redirect()
            ->route('dashboard')
            ->with('status', 'Pesan komunikasi terkirim.');
    }

    public function markAlertRead(Alert $alert): RedirectResponse
    {
        $alert->update([
            'status' => 'read',
            'read_at' => $alert->read_at ?? now(),
        ]);

        return redirect()
            ->route('dashboard')
            ->with('status', 'Notifikasi ditandai sudah dibaca.');
    }

    public function resolveMessage(Message $message): RedirectResponse
    {
        $message->update([
            'status' => 'done',
            'resolved_at' => now(),
        ]);

        return redirect()
            ->route('dashboard')
            ->with('status', 'Pesan ditandai selesai.');
    }
}
