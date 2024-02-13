<?php

namespace App\Services;

use App\Models\Property;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class UploadPropertyPhotoService
{
    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function uploadImage(Property $property): Media
    {
      return $property->addMediaFromRequest('photo')->toMediaCollection('images');
    }

    public function setPosition(Property $property, Media $photo): void
    {
        $maxPosition = Media::query()
                ->where('model_type', 'App\Models\Property')
                ->where('model_id', $property->id)
                ->max('position') + 1;

        $photo->position = $maxPosition;
        $photo->save();
    }

    public function reorderImage(Media $photo, int $newPosition): void
    {
        $query = Media::query()
            ->where('model_type', 'App\Models\Property')
            ->where('model_id', $photo->model_id);

        if ($newPosition < $photo->position) {
            $query->whereBetween('position', [$newPosition, $photo->position-1])->increment('position');
        } else {
            $query->whereBetween('position', [$photo->position+1, $newPosition])->decrement('position');
        }

        $photo->position = $newPosition;
        $photo->save();
    }
}
