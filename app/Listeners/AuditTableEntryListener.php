<?php

namespace App\Listeners;

use App\Events\AuditTableEntryEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuditTableEntryListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(AuditTableEntryEvent $event): void
    {

        if (!is_array($event->data)) {
            $data = $event->data->toArray();
            $data['created_at'] = Carbon::parse($data['created_at'])->format('Y-m-d H:i:s');
            $data['updated_at'] = Carbon::parse($data['updated_at'])->format('Y-m-d H:i:s');
        } else {
            $data = $event->data;
        }

        $table_name = $event->model;
        $action = $event->action;


        $data['action'] = $action;
        $data['performed_by'] = Auth::id();
        $data['ip_address'] = request()->ip();

        unset($data['id']);
        unset($data['updated_by']);
        unset($data['created_by']);
        unset($data['deleted_by']);

        DB::beginTransaction();
        try {
            DB::table('audit_' . $table_name)->insert($data);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception->getMessage());
        }
    }
}
