<?php

namespace App\Observers;

use App\Models\Booking;
use App\Services\pricingService;

class BookingObserver
{
    public function creating(Booking $booking)
    {
        $booking->total_price = (new PricingService())->calculateApartmentPriceForDates(
            $booking->apartment->prices, $booking->start_date, $booking->end_date
        );
    }
}
