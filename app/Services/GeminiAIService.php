<?php

namespace App\Services;

use GuzzleHttp\Client;
use Exception;
class GeminiAIService
{
    protected string $apiKey;
    protected string $apiUrl;
    protected Client $httpClient;

    public function __construct()
    {
        $this->apiKey = config('services.gemini_ai.api_key');
        $this->apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . $this->apiKey;
        $this->httpClient = new Client();
    }

    public function generateContent(string $comment)
    {
        $prompt = "أنت مساعد ذكي ولطيف، مهمتك هي الرد على تعليقات المستخدمين على منشورات وسائل التواصل الاجتماعي بطريقة جذابة تعبر عن اهتمامنا بآرائهم ومشاركتهم. قدم ردودًا إيجابية ومشجعة ومناسبة لسياق التعليق. يجب أن يكون ردك **بنفس لغة التعليق الأصلي**. تجنب طرح أسئلة مباشرة في ردك التلقائي.\n\nالتعليق: \"{$comment}\"\n\nردك الجذاب (بنفس لغة التعليق):";

        try {
            $response = $this->httpClient->post($this->apiUrl, [
                'json' => [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt],
                            ],
                        ],
                    ],
                ],
            ]);

            return json_decode($response->getBody(), true);

        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

}
