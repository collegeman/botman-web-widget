<?php

namespace Collegeman\BotManWebWidget\Drivers;

use BotMan\BotMan\Drivers\HttpDriver;
use BotMan\BotMan\Http\Curl;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use Illuminate\Console\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;

class CommandDriver extends HttpDriver
{
    const DRIVER_NAME = 'Command';

    protected static OutputInterface $output;

    public static function setOutput(OutputInterface $output)
    {
        self::$output = $output;
    }

    public static function make(Command $command, array $config = [], array $httpOptions = []): self
    {
        self::setOutput($command->getOutput());
        return new self(request(), $config, new Curl($httpOptions));
    }

    public function matchesRequest()
    {
        return true;
    }

    public function isConfigured()
    {
        return !empty($this->output);
    }

    public function buildPayload(Request $request)
    {
        $this->payload = collect([
            'text' => $request->get('text')
        ]);
    }

    public function sendRequest($endpoint, array $parameters, IncomingMessage $matchingMessage)
    {
        // not needed
    }

    public function getMessages()
    {
        $text = $this->payload->get('text');
        return [new IncomingMessage($text, 'cli-user', 'cli')];
    }

    public function getUser(IncomingMessage $matchingMessage)
    {
        // TODO: Implement getUser() method.
    }

    public function getConversationAnswer(IncomingMessage $message)
    {
        return Answer::create($message->getText())->setMessage($message)->setValue($message->getText());
    }

    public function buildServicePayload($message, $matchingMessage, $additionalParameters = [])
    {
        return $message;
    }

    public function sendPayload($payload)
    {
        if ($payload instanceof OutgoingMessage) {
            $this->writeln($payload->getText());
            // TODO: Add support for attachments
        }
    }

    protected function writeln($text)
    {
        if (self::$output) {
            self::$output->writeln($text);
        } else {
            echo $text . PHP_EOL;
        }
    }

    public function types(IncomingMessage $matchingMessage)
    {
        // Optional: could simulate typing here
    }

}