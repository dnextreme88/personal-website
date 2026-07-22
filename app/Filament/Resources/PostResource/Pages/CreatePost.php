<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;
    protected array $related_post_ids = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        $this->related_post_ids = $data['related_post_ids'] ?? [];
        unset($data['related_post_ids']);

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->syncRelatedPosts($this->related_post_ids);
    }
}
