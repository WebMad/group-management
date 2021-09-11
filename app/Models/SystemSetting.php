<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    use HasFactory;

    const START_YEAR_SETTING = 'start_year';
    const COMMUNITY_CHAT_ID_SETTING = 'community_chat_id';
    const VK_TOKEN_SETTING = 'vk_token';

    protected $fillable = ['name', 'value'];
}
