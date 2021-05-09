<?php

namespace LocalheroPortal\Core\Feature\CustomerProgress;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Models\LLI\Location;
use LocalheroPortal\Models\LLI\LocationState;
use Response;
use function Sentry\captureException;


//TODO: refactor to location state controller?
class CustomerProgressApiController extends Controller
{
    public function activateLocation(Location $location) {
        DB::beginTransaction();
        try {
            $location->addState(LocationState::ACTIVATED);

            if(in_array(LocationState::ACCESS_DATA_SENT, $location->states)) {
                $notification = new LocationReadyNotification($location);
            } else {
                $customer = $location->company->user;
                $password = Str::random(10);
                $customer->password = Hash::make($password);
                $customer->save();
                $location->addState(LocationState::ACCESS_DATA_SENT);
                $notification = new AccountReadyNotification($customer, $password);
            }
            $location->save();
            Notification::send($location->company->user()->get(), $notification);
        } catch (Exception $e) {
            DB::rollBack();
            captureException($e);
            return Response::json(['errors' => ['Freischalten fehlgeschlagen.']], 500);
        }
        DB::commit();
        return Response::json(['message' => ['Standort freigeschaltet.']]);
    }
}
