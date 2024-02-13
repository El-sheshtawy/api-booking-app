<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadPropertyPhotoRequest;
use App\Models\Property;
use App\Services\UploadPropertyPhotoService;
use Illuminate\Http\Response;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @group Owner
 * @subgroup Property photo management
 */
class PropertyPhotoController extends Controller
{
    /**
     * Add a photo to a property
     *
     * [Adds a photo to a property and returns the filename, thumbnail and position of the photo]
     *
     * @authenticated
     *
     * @response {"filename": "http://localhost:8000/storage/properties/1/photos/1/IMG_20190601_123456.jpg", "thumbnail": "http://localhost:8000/storage/properties/1/photos/1/conversions/thumbnail.jpg", "position": 1}
     * @response 422 {"message":"The photo must be an image.","errors":{"photo":["The photo must be an image."]}}
     */
    public function store(Property $property, UploadPropertyPhotoRequest $request, UploadPropertyPhotoService $service)
    {
        abort_if($property->owner_id !== auth()->id(), Response::HTTP_FORBIDDEN);

        $request->validated();

        try {
            $photo = $service->uploadImage($property);
        } catch (FileDoesNotExist $e) {
            return response()->json(['error' => 'file is not exists.']);
        } catch (FileIsTooBig $e) {
            return response()->json(['error' => 'file is to big.']);
        }

        $service->setPosition($property, $photo);

        return [
            'filename' => $photo->getUrl(),
            'thumbnail' => $photo->getUrl('thumbnail'),
            'position' => $photo->position
        ];
    }

    /**
     * Reorder photos of a property
     *
     * [Reorders photos of a property and returns the new position of the photo]
     *
     * @authenticated
     *
     * @urlParam newPosition integer The new position of the photo. Example: 2
     *
     * @response {"newPosition": 2}
     */
    public function reorder(Property $property, Media $photo, int $newPosition, UploadPropertyPhotoService $service): array
    {
        abort_if($property->owner_id !== auth()->id()
            || $photo->model_id !== $property->id, Response::HTTP_FORBIDDEN);

        $service->reorderImage($photo, $newPosition);

        return [
            'newPosition' => $photo->position
        ];
    }
}
