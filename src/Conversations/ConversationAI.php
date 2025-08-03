<?php

namespace Collegeman\BotManWebWidget\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use LLPhant\Chat\ChatInterface;
use LLPhant\Chat\FunctionInfo\FunctionInfo;
use LLPhant\Chat\FunctionInfo\Parameter;
use LLPhant\Chat\Message;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use Illuminate\Support\Facades\Log;
use League\CommonMark\CommonMarkConverter;

class ConversationAI extends Conversation
{
    protected ChatInterface | string $chat;

    protected Collection $messages;

    protected Collection $tools;

    protected bool $convertMarkdownToHtml = false;

    /**
     * @param ChatInterface | string $chat Container alias/name for an instance of ChatInterface
     */
    public function __construct(string $chat, string $prompt)
    {
        $this->chat = $chat;
        $this->messages = collect([]);
        $this->tools = collect([]);
        $this->system('You are a helpful assistant. You strive for brevity and clarity.');

        if (!empty($prompt)) {
            $this->user($prompt);
        }
    }

    public function system(string $content): self
    {
        $this->messages = $this->messages->filter(function ($message) {
            if ($message instanceof Message) {
                return $message->role !== 'system';
            } else {
                return !empty($message['role']) && $message['role'] !== 'system';
            }
        });

        $this->messages->prepend(Message::system($content));

        return $this;
    }

    public function user(string $content): self
    {
        $this->messages->push(Message::user($content));

        return $this;
    }

    public function assistant(string $content): self
    {
        $this->messages->push(Message::assistant($content));

        return $this;
    }

    public function withTool(FunctionInfo $tool): self
    {
        $this->tools->push($tool);

        return $this;
    }

    public function withCrawler(): self
    {
        $crawler = new FunctionInfo(
            'crawl',
            $this,
            'If the user provides a URL, you can use this function get get the contents of the URL.',
            [new Parameter('url', 'string', 'The URL to crawl')]
        );

        if (!$this->tools->contains($crawler)) { // this probably doesn't work...
            $this->tools->push($crawler);
        }

        return $this->withTool($crawler);
    }

    public function crawl(string $url): string
    {
        return Http::get($url)->body();
    }

    public function stopsConversation(IncomingMessage $message): bool
    {
        return $message->getText() === 'stop conversation';
    }

    public function loop($response)
    {
//        Log::info($response);

        $this->assistant($response);

        if ($this->convertMarkdownToHtml) {
            if (!$converter = app(CommonMarkConverter::class)) {
                throw new \Exception("CommonMarkConverter not found in container");
            }
            $response = (string) $converter->convert($response);
        }

        $this->ask($response, function (Answer $answer) {
            dump($answer);
            $this->user($answer->getText());
            $this->loop($this->chat());
        });
    }

    public function chat()
    {
        $messages = collect($this->messages)->map(function ($message) {
            if ($message instanceof Message) {
                return $message;
            }

            return match ($message['role']) {
                'system' => Message::system($message['content']),
                'user' => Message::user($message['content']),
                'assistant' => Message::assistant($message['content']),
                default => throw new \Exception('Invalid message role: ' . $message['role'])
            };
        })->toArray();

        $chat = $this->chat;

        if (!$chat instanceof ChatInterface) {
            if (!$chat = app($chat)) {
                throw new \Exception("LLPhant ChatInterface instance [{$this->chat}] not found in container");
            }
        }

        $this->tools->each(fn (FunctionInfo $tool) => $chat->addTool($tool));

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