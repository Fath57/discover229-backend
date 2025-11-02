<?php

namespace App\Traits\Common;

use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\LogOptions;
use Stevebauman\Location\Facades\Location;
use Suntech\MainService\Models\Order;

trait ActivityTrait
{
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable();
    }

    /***
     * @param Activity $activity
     * @param string $eventName
     */
    public function tapActivity(Activity $activity, string $eventName): void
    {
        switch ($eventName) {
            case 'created':
                if (self::class == Order::class) {
                    $action = 'Nouvelle commande effectué';

                } else {
                    $action = $this->getEntityName().' créé';
                }
                $actionName = 'Création';
                break;
            case 'updated':
                $action = $this->getEntityName().' mis à jour';
                $actionName = 'Modification';
                break;
            case 'deleted':
                $action = $this->getEntityName().' supprimé';
                $actionName = 'Suppression';
                break;
            default:
                $action = 'Une opération est effectué sur un '.$this->getEntityName();
                $actionName = 'Default';
                break;
        }

        // dd(request()->user()->);
        $position = Location::get();
        if ($position) {
            // Successfully retrieved position.
            $address = $position->cityName.' - '.$position->countryName." ($position->latitude,$position->longitude)";
        } else {
            $address = '--';
        }

        $activity->ip_address = request()->ip();
        $activity->description = $action;
        $activity->log_action = $actionName;
        $activity->imei = request()->input('imei');
        $activity->phone_model = request()->input('model');
        $activity->address = $address;
    }
}
