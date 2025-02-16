<?php

namespace Collegeman\BotManWebWidget\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\BotMan\Messages\Incoming\Answer;

class TestConversation extends Conversation
{
    public function askForName()
    {
        $this->ask("Hi there! Before we get started, what should I call you?", function ($answer) {
            $this->say("Well, nice to meet you, {$answer->getText()}");
        });
    }

    public function run()
    {
        $this->askForName();
    }
}