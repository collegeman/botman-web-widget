<?php

namespace Collegeman\BotManWebWidget\Console\Commands;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use Collegeman\BotManWebWidget\Conversations\ChatConversation;
use Collegeman\BotManWebWidget\Drivers\CommandDriver;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

class ChatCommand extends Command
{
    protected $signature = 'botman:chat {conversation=ChatConversation} {--chat=LLPhant\Chat\OpenAIChat}';

    protected BotMan $botman;

    function handle()
    {
        $config = [
          //
        ];

        DriverManager::loadDriver(CommandDriver::class);
        CommandDriver::setOutput($this->getOutput());
        $this->botman = BotmanFactory::create($config);

        $this->botman->hears('(.+)', function (BotMan $bot, $prompt) {
            $conversation = $this->getChatConversation()->user($prompt);
            $conversation->withCrawler();
            $bot->startConversation($conversation);
        });

        while (true) {
            $this->output->write("> ");
            $text = trim(fgets(STDIN));
            if (strtolower($text) === 'exit') {
                $this->info('Goodbye!');
                break;
            }

            $request = Request::create('/', 'GET', ['text' => $text]);
            $this->botman->getDriver()->buildPayload($request);
            $this->botman->listen();
        }
    }

    public function getChatConversation(): ChatConversation
    {
        $ref = $this->argument('conversation');

        // test container first
        if (app()->has($ref)) {
            return app($ref);
        }

        // then look at class defs
        if (class_exists($ref)) {
            return new $ref($this->option('chat'));
        }

        // look at built-in conversations first
        $builtInRef = "\\Collegeman\\BotManWebWidget\\Conversations\\{$ref}";
        if (class_exists($builtInRef)) {
            return new $builtInRef($this->option('chat'));
        }

        throw new \InvalidArgumentException("Invalid Conversation type: {$ref}");
    }
}