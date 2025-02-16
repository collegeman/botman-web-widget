<?php

namespace Collegeman\BotManWebWidget\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use LLPhant\Chat\Message;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use Illuminate\Support\Facades\Log;
use League\CommonMark\CommonMarkConverter;

class ExampleLLPhantConversation extends Conversation
{
    protected string $chat;

    protected array $messages;

    protected bool $convertMarkdownToHtml = false;

    /**
     * @param string $chat Container alias/name for an instance of ChatInterface
     * @param string $prompt Prompt to send to the LLM to begin the conversation
     */
    public function __construct(string $chat, string $prompt)
    {
        $this->chat = $chat;

        $this->messages = [
            [
                'role' => 'system',
                'content' => 'You are a helpful assistant. You strive for brevity and clarity.',
            ],
            [
                'role' => 'user',
                'content' => $prompt,
            ],
        ];
    }

    public function stopConversation(IncomingMessage $answer)
    {
        return $answer->getText() === 'llphant:stop';
    }

    public function loop($response)
    {
        Log::info($response);

        $this->messages[] = [
            'role' => 'assistant',
            'content' => $response,
        ];

        if ($this->convertMarkdownToHtml) {
            if (!$converter = app(CommonMarkConverter::class)) {
                throw new \Exception("CommonMarkConverter not found in container");
            }
            $response = (string) $converter->convert($response);
        }

        $this->ask($response, function (Answer $answer) {
            $this->messages[] = [
                'role' => 'user',
                'content' => $answer->getText(),
            ];
            $this->loop($this->chat());
        });
    }

    public function chat()
    {
        $messages = collect($this->messages)->map(function ($message) {
            if ($message['role'] === 'system') {
                return Message::system($message['content']);
            } else if ($message['role'] === 'user') {
                return Message::user($message['content']);
            } else if ($message['role'] === 'assistant') {
                return Message::assistant($message['content']);
            } else {
                throw new \Exception('Invalid message role: ' . $message['role']);
            }
        })->toArray();

        if (!$chat = app($this->chat)) {
            throw new \Exception("Chat instance [{$this->chat}] not found in container");
        }

        return $chat->generateChat($messages);
    }

    public function run()
    {
        $this->loop($this->chat());
    }

    public function convertMarkdownToHtml(bool $convertMarkdownToHtml): self
    {
        $this->convertMarkdownToHtml = $convertMarkdownToHtml;
        return $this;
    }

}