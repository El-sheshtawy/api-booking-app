<?php

namespace App\Http\Resources;

use App\Services\pricingService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApartmentSearchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->apartment_type?->name,
            'size' => $this->size,
            'beds_list' => $this->beds_list,
            'bathrooms' => $this->bathrooms,
            'facilities' => FacilityResource::collection($this->whenLoaded('facilities')),
            'price' => (new PricingService())->calculateApartmentPriceForDates($this->prices, $request->start_date,
                $request->end_date)
        ];
    }
}
