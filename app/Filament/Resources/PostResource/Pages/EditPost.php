<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use App\Models\Blog\Post;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;
    protected array $related_post_ids = [];

    protected function getHeaderActions(): array
    {
        return [
            Action::make('view_in_site')
                ->color('gray')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->label('View in Site')
                ->openUrlInNewTab()
                ->url(fn (): string => route('blog.post.detail', [
                    'id' => $this->record->id,
                    'slug' => $this->record->slug,
                ]))
                ->visible(fn (): bool => Post::published()->whereKey($this->record->getKey())->exists()),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['related_post_ids'] = $this->record->related_post_ids;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->related_post_ids = $data['related_post_ids'] ?? [];
        unset($data['related_post_ids']);

        return $data;
    }

    protected function afterSave(): void
    {
        $this->record->syncRelatedPosts($this->related_post_ids);
    }
}
