<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        Log::info("🔥🔥🔥🔥 INI CHATBOT CONTROLLER YANG KEPAKE 🔥🔥🔥🔥");

        $message = $request->input('message');
        $apiKey = env('GEMINI_API_KEY');
        $model = 'gemini-2.5-pro'; // atau ganti ke 'gemini-1.5-flash-002' biar lebih cepat

        Log::info("🧪 Menggunakan model: $model");

        try {
            $response = Http::withOptions([
    'timeout' => 50,
])->retry(3, 5000)->post( // coba 3x, delay 5 detik antar percobaan
    "https://generativelanguage.googleapis.com/v1/models/{$model}:generateContent?key={$apiKey}",
    [
        'contents' => [
            [
                'role' => 'user',
                'parts' => [
                    ['text' => $message]
                ]
            ]
        ]
    ]
);


            Log::info('🧾 Status: ' . $response->status());

            $data = $response->json();
            Log::info('🔥 Gemini response: ', $data);

            if ($response->failed() || !isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                return response()->json([
                    'reply' => '⚠️ Gagal menghubungi AI.',
                    'error' => $data
                ]);
            }

            $reply = $data['candidates'][0]['content']['parts'][0]['text'];

            return response()->json([
                'reply' => $reply
            ]);
        } catch (\Exception $e) {
            Log::error('Gemini Chat Exception', ['message' => $e->getMessage()]);
            return response()->json([
                'reply' => '⚠️ Terjadi kesalahan saat menghubungi AI.',
                'error' => $e->getMessage()
            ]);
        }
    }
}
