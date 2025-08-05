# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Overview

This is a Laravel package called `collegeman/botman-web-widget` - a drop-in replacement for the BotMan Web Widget that includes AI-powered chat functionality using OpenAI's GPT models through the LLPhant library.

## Development Commands

### Frontend Assets
- `npm run dev` - Start Vite development server with hot reloading
- `npm run build` - Build production assets
- `npm run preview` - Preview production build

### PHP/Laravel
- `composer test` - Run PHPUnit tests
- `composer test-coverage` - Run tests with HTML coverage report
- `php artisan botman:chat` - Start interactive CLI chat session with the AI bot

### Package Publishing (for Laravel integration)
- `php artisan vendor:publish --tag=botman-web-widget-config` - Publish config file
- `php artisan vendor:publish --tag=botman-web-widget-views` - Publish Blade views
- `php artisan vendor:publish --tag=botman-web-widget-assets` - Publish built frontend assets

## Architecture

### Core Components

**BotMan Integration**: Built on the BotMan chatbot framework with a custom `CommandDriver` that enables CLI-based chat interactions alongside web-based ones.

**AI Chat System**: Uses LLPhant library to integrate OpenAI's GPT-4 model for conversational AI. The `ChatConversation` class handles:
- Message history management
- System prompt configuration  
- Tool/function calling capabilities
- Markdown to HTML conversion
- Web crawling functionality via HTTP requests

**Frontend Stack**: Vue.js 3 + Tailwind CSS components built with Vite, including:
- Chat interface components (`Chat.vue`, `ChatBody.vue`, etc.)
- Beacon/launcher widget
- Responsive design for mobile and desktop

**Laravel Service Provider**: Registers routes, publishes assets, and provides the `@botman` Blade directive for easy widget embedding.

### Key File Structure

- `src/Conversations/ChatConversation.php` - Main AI conversation handler with OpenAI integration
- `src/Drivers/CommandDriver.php` - Custom BotMan driver for CLI interactions
- `src/Console/Commands/ChatCommand.php` - Artisan command for CLI chat interface
- `resources/js/components/` - Vue.js chat widget components  
- `config/config.php` - Widget configuration options (colors, endpoints, behavior)
- `routes/web.php` - Chat and beacon endpoint definitions

### Environment Requirements

- Requires `OPENAI_API_KEY` environment variable for AI functionality
- PHP 8.2+, Laravel 10/11/12 support
- Node.js for frontend asset compilation

### Configuration

The widget behavior is controlled through `config/botman-web-widget.php` including:
- Chat server endpoints (`/botman`, `/botman/chat`, `/botman/beacon`)
- Visual styling (colors, dimensions, branding)
- Real-time features via Laravel Echo (optional)
- Default messages and behavior settings

### Testing

- PHPUnit tests located in `tests/` directory (autoloaded via PSR-4)
- Frontend components can be tested through Vite preview mode