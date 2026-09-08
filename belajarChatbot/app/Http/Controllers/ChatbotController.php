<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Chat;
use App\Models\ChatSession;

class ChatbotController extends Controller
{
    public function index()
    {
        $sessions = ChatSession::latest()->get();
        return view('chat', compact('sessions'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'session_id' => 'nullable|exists:chat_sessions,id',
        ]);

        if ($request->session_id) {
            $session = ChatSession::find($request->session_id);
        } else {
            $session = ChatSession::create(['name' => now()->format('Y-m-d H:i:s ')]);
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.groq.com/openai/v1/chat/completions', [
            'model' => env('GROQ_MODEL', 'openai/gpt-oss-20b'),
            'messages' => [
                ['role' => 'system', 'content' => 'You are a helpful assistant that formats messages neatly for readability.'],
                ['role' => 'user', 'content' => $request->message],
            ]
        ]);

        if ($response->failed()) {
            return response()->json([
                'message' => 'Groq API gagal memproses pesan.',
                'error' => $response->json('error.message', 'Unknown API error'),
                'status' => $response->status(),
            ], $response->status() ?: 502);
        }

        $answer = $response->json('choices.0.message.content');
        if (!$answer) {
            return response()->json(['message' => 'Groq tidak mengembalikan jawaban.'], 502);
        }

        $chat = Chat::create([
            'question' => $request->message,
            'answer' => $answer,
            'session_id' => $session->id,
        ]);

        return response()->json([
            'session_id' => $session->id,
            'chat' => $chat,
        ]);
    }

    public function getSessionChats($sessionId)
    {
        $chats = Chat::where('session_id', $sessionId)->latest()->get();
        return response()->json($chats);
    }
}
