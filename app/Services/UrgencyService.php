<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UrgencyService
{
    /**
     * Allowed urgency levels (ordered from most to least severe).
     *
     * @var list<string>
     */
    private const URGENCY_LEVELS = ['critical', 'high', 'medium', 'low'];

    /**
     * Keyword tiers used by the fallback analyser.
     * Keys are urgency levels; values are trigger phrases (case-insensitive).
     *
     * @var array<string, list<string>>
     */
    private const KEYWORD_TIERS = [
        'critical' => ['outage', 'down', 'breach', 'critical', 'data loss', 'emergency', 'production'],
        'high'     => ['urgent', 'broken', 'crash', 'failure', 'not working', 'blocked', 'error'],
        'medium'   => ['slow', 'issue', 'degraded', 'intermittent', 'warning'],
    ];

    /**
     * Analyse a ticket description and return urgency + source.
     *
     * Primary path  : OpenAI Chat Completions API (gpt-4o-mini, 8 s timeout).
     * Fallback path : Keyword-tier scoring — always succeeds.
     *
     * @return array{urgency: string, urgency_source: string}
     */
    public function analyse(string $description): array
    {
        $apiKey = config('services.openai.key', '');

        if (!empty($apiKey)) {
            try {
                return $this->analyseWithOpenAI($description, $apiKey);
            } catch (\Throwable $e) {
                Log::warning('UrgencyService: OpenAI call failed, using keyword fallback.', [
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $this->keywordFallback($description);
    }

    /**
     * Call OpenAI Chat Completions and parse the urgency from the response.
     *
     * @return array{urgency: string, urgency_source: string}
     *
     * @throws \RuntimeException When the API returns an unexpected structure.
     */
    private function analyseWithOpenAI(string $description, string $apiKey): array
    {
        $response = Http::withToken($apiKey)
            ->timeout(8)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role'    => 'system',
                        'content' => 'You are a support-ticket triage assistant. '
                            . 'Respond ONLY with valid JSON in the exact format: {"urgency": "<level>"}. '
                            . 'The level must be one of: low, medium, high, critical. '
                            . 'No explanation, no markdown — raw JSON only.',
                    ],
                    [
                        'role'    => 'user',
                        'content' => 'Ticket description: ' . $description,
                    ],
                ],
                'max_tokens'  => 20,
                'temperature' => 0,
            ]);

        if (!$response->successful()) {
            throw new \RuntimeException(
                'OpenAI API returned HTTP ' . $response->status()
            );
        }

        $content = $response->json('choices.0.message.content', '');
        $decoded = json_decode(trim((string) $content), true);

        if (!is_array($decoded) || !isset($decoded['urgency'])) {
            throw new \RuntimeException('OpenAI response missing urgency field.');
        }

        $urgency = strtolower((string) $decoded['urgency']);

        if (!in_array($urgency, self::URGENCY_LEVELS, true)) {
            throw new \RuntimeException("OpenAI returned unknown urgency level: {$urgency}");
        }

        return ['urgency' => $urgency, 'urgency_source' => 'ai'];
    }

    /**
     * Score the description against keyword tiers and return the highest match.
     * Always returns a valid result — this is the guaranteed fallback.
     *
     * @return array{urgency: string, urgency_source: string}
     */
    private function keywordFallback(string $description): array
    {
        $lower = strtolower($description);

        foreach (self::KEYWORD_TIERS as $level => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($lower, $keyword)) {
                    return ['urgency' => $level, 'urgency_source' => 'fallback'];
                }
            }
        }

        return ['urgency' => 'low', 'urgency_source' => 'fallback'];
    }
}
