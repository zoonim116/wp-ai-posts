<?php

namespace Wpapi\Admin;

use Wpapi\Core\KeyStatus;

class AiRequest
{
    protected $client;
    public function __construct(string $api_key)
    {
        $this->client = \OpenAI::client($api_key);
    }

    public function test_connection() {
        $this->client->chat()->create([
            'model' => 'gpt-4',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => 'Hello, world!'
                ]
            ]
        ]);
    }

    public function generate_post(string $prompt): \stdClass
    {
        $data = $this->client->chat()->create([
            'model' => 'gpt-4',
            'temperature' => 0.7,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => <<<'PROMPT'
                        Use the provided subject to generate article with a 5000-10000 symbols using HTML formating. Reply with the following JSON: 
                        
                        {
                            "title": "summary of article",
                            "content": "article content"
                        }
                        
                        The "title" is a summary of the article approx 200 symbols and "content" is an article.
PROMPT
                ], [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ]
        ]);
        $content = preg_replace('/[[:cntrl:]]/', '', $data->choices[0]->message->content);
        return json_decode($content);
    }
}