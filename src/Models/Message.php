<?php

namespace Collegeman\BotManWebWidget\Models;

use Collegeman\BotManWebWidget\BotManWebWidget;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasUuids;
    use HasTimestamps;
    use SoftDeletes;

    public function getTable(): string
    {
        return BotManWebWidget::config('database.messages_table_name');
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(BotManWebWidget::config('database.conversations_model'));
    }

}
