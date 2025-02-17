<?php

namespace Collegeman\BotManWebWidget\Http\Controllers;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Cache\LaravelCache;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\BotMan\Interfaces\Middleware\Sending;
use BotMan\Drivers\Web\WebDriver;
use Collegeman\BotManWebWidget\Conversations\ExampleLLPhantConversation;
use Collegeman\BotManWebWidget\Conversations\TestConversation;
use Illuminate\Http\Request;

class BotManWebWidgetTestController
{
    public function listen(Request $request)
    {
        dump($request);

        DriverManager::loadDriver(WebDriver::class);

        $botman = BotManFactory::create([
            //
        ], new LaravelCache());

        $botman->middleware->sending(new class implements Sending {
            public function sending($payload, $next, BotMan $bot) { 
                dump($payload);
                return $next($payload);
            }
        });

        $botman->hears("llphant:(.*?)\s+(.*)", function (BotMan $bot, $chat, $prompt) {
            $bot->startConversation((new ExampleLLPhantConversation($chat, $prompt))->convertMarkdownToHtml(true));
        });

        $botman->hears("test", function (BotMan$bot) {
            $bot->startConversation(new TestConversation());
        });

        $botman->hears("llphant:stop", function(BotMan $bot) {
            $bot->reply("You are no longer chatting with the LLM.");
        });

        $botman->fallback(function (BotMan $bot) {
            $responses = [
                "I'm not quite sure what you mean. Could you rephrase that?",
                "Hmm, that's not something I recognize. Can you try again?", 
                "I didn't catch that. Mind saying it another way?",
                "I'm drawing a blank on that one. Could you clarify?",
                "That's not computing for me. Can you word it differently?",
                "I'm not following. Could you be more specific?",
                "Sorry, that went over my head. Try asking in a different way?",
                "I'm lost on that request. Mind rephrasing?",
                "That's not in my playbook. Could you try another approach?",
                "I'm having trouble understanding. Can you rephrase your question?"
            ];
            $bot->reply($responses[array_rand($responses)]);
        });

        $botman->middleware->sending(new class implements Sending {
            public function sending($payload, $next, BotMan $bot) { 
                dump($payload);
                return $next($payload);
            }
        });

        $botman->listen();
    }
}