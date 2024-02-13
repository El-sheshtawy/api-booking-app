<?php

namespace App\Services;

class BookingService
{
    public function store(array $bookingData)
    {
        return auth()->user()->bookings()->create($bookingData);
    }
}
