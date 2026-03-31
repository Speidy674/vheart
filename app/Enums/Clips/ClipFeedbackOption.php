<?php

declare(strict_types=1);

namespace App\Enums\Clips;

use App\Enums\Traits\HasTranslatedLabel;
use Filament\Support\Contracts\HasLabel;

enum ClipFeedbackOption: string implements HasLabel
{
    use HasTranslatedLabel;

    case AudioTooQuiet = 'audio_too_quiet';
    case AudioTooLoud = 'audio_too_loud';
    case BadAudioQuality = 'bad_audio_quality';
    case BadVideoQuality = 'bad_video_quality';
    case ContentUnavailable = 'content_unavailable';
    case Other = 'other';

    private function getTranslatableEnumLabelPrefix(): string
    {
        return 'clips.enums';
    }
}
