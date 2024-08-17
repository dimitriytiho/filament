<?php

namespace App\Observers;

use App\Models\Dummy;

class DummyObserver
{
    /**
     * Handle the AppModelsAnalog "created" event.
     */
    public function created(Dummy $data): void
    {
        //
    }

    /**
     * Handle the AppModelsAnalog "updated" event.
     */
    public function updated(Dummy $data): void
    {
        //
    }

    /**
     * Handle the AppModelsAnalog "deleted" event.
     */
    public function deleted(Dummy $data): void
    {
        //
    }

    /**
     * Handle the AppModelsAnalog "restored" event.
     */
    /*public function restored(Dummy $data): void
    {
        //
    }*/

    /**
     * Handle the AppModelsAnalog "force deleted" event.
     */
    /*public function forceDeleted(Dummy $data): void
    {
        //
    }*/
}
