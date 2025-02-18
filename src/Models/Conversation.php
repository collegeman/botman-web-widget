<?php

namespace Collegeman\BotManWebWidget\Models;

use Collegeman\BotManWebWidget\BotManWebWidget;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conversation extends Model
{
    use HasUuids;
    use HasTimestamps;
    use SoftDeletes;

    public function getTable(): string
    {
        return BotManWebWidget::config('database.conversations_table_name');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(BotManWebWidget::config('database.messages_model'));
    }
}
