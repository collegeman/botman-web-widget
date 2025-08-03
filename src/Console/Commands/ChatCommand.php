<?php

namespace Collegeman\BotManWebWidget\Console\Commands;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\BotMan\Messages\Conversations\Conversation;
use Collegeman\BotManWebWidget\Conversations\ConversationAI;
use Collegeman\BotManWebWidget\Drivers\CommandDriver;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use LLPhant\Chat\OpenAIChat;

class ChatCommand extends Command
{
    protected $signature = 'botman:chat {--chat=LLPhant\Chat\OpenAIChat}';

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
            $conversation = new ConversationAI($this->option('chat'), $prompt);
            // $conversation->system("You are a helpful assistant. You strive for brevity and clarity.");
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
}