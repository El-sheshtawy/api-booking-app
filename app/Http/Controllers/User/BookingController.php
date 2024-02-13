<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Http\Resources\BookingResource;
use App\Jobs\UpdatePropertyRatingJob;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

/**
 * @group User
 * @subgroup Bookings
 */
class BookingController extends Controller
{
    /**
     * @throws AuthorizationException
     * List of user bookings
     *
     * [Returns preview list of all user bookings]
     *
     * @authenticated
     *
     * @response {"id":1,"apartment_name":"Fugiat saepe sed.: Apartment","start_date":"2023-05-11","end_date":"2023-05-12","guests_adults":1,"guests_children":0,"total_price":0,"cancelled_at":null,"rating":null,"review_comment":null}
     */
    public function index(): AnonymousResourceCollection
    {
        $this->authorize('bookings-manage');

        $bookings = auth()->user()->bookings()
            ->with('apartment.property')
            ->withTrashed()
            ->orderBy('start_date')
            ->paginate();

        return BookingResource::collection($bookings);
    }

    /**
     * Create new booking
     *
     * [Creates new booking for authenticated user]
     *
     * @authenticated
     *
     * @response 201 {"id":1,"apartment_name":"Hic consequatur qui.: Apartment","start_date":"2023-05-11 08:00:51","end_date":"2023-05-12 08:00:51","guests_adults":2,"guests_children":1,"total_price":0,"cancelled_at":null,"rating":null,"review_comment":null}
     */
    public function store(StoreBookingRequest $request, BookingService $bookingService): BookingResource
    {
        return BookingResource::make($bookingService->store($request->validated()));
    }

    /**
     * @throws AuthorizationException
     * View booking
     *
     * [Returns details about a booking]
     *
     * @authenticated
     *
     * @response {"id":1,"apartment_name":"Hic consequatur qui.: Apartment","start_date":"2023-05-11 08:00:51","end_date":"2023-05-12 08:00:51","guests_adults":2,"guests_children":1,"total_price":0,"cancelled_at":null,"rating":null,"review_comment":null}
     */
    public function show(Booking $booking)
    {
        $this->authorize('bookings-manage');

        abort_if($booking->user_id !== auth()->id(), Response::HTTP_FORBIDDEN);

        return BookingResource::make($booking);
    }

    /**
     * Update existing booking rating
     *
     * [Updates booking with new details]
     *
     * @authenticated
     *
     * @response {"id":1,"apartment_name":"Hic consequatur qui.: Apartment","start_date":"2023-05-11 08:00:51","end_date":"2023-05-12 08:00:51","guests_adults":2,"guests_children":1,"total_price":0,"cancelled_at":null,"rating":null,"review_comment":null}
     */
    public function update(Booking $booking, UpdateBookingRequest $request)
    {
        abort_if($booking->user_id !== auth()->id(), Response::HTTP_FORBIDDEN);

        $booking->update($request->validated());

        dispatch(new UpdatePropertyRatingJob($booking));

        return BookingResource::make($booking);
    }

    /**
     * @throws AuthorizationException
     * Delete booking
     *
     * [Deletes a booking]
     *
     * @authenticated
     *
     * @response {}
     */
    public function destroy(Booking $booking): Response
    {
        $this->authorize('bookings-manage');

        abort_if($booking->user_id !== auth()->id(), Response::HTTP_FORBIDDEN);

        $booking->delete();

        return response()->noContent();
    }
}

