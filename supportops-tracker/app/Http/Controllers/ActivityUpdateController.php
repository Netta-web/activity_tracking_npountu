<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreActivityUpdateRequest;
use App\Models\Activity;
use App\Models\ActivityUpdate;
use App\Models\AuditLog;
use Illuminate\Http\RedirectResponse;

class ActivityUpdateController extends Controller
{
    public function store(StoreActivityUpdateRequest $request, Activity $activity): RedirectResponse
    {
        $this->authorize('addUpdate', $activity);

        $update = ActivityUpdate::create([
            'activity_id' => $activity->id,
            'user_id'     => auth()->id(),
            'status'      => $request->status,
            'remark'      => $request->remark,
        ]);

        $activity->update(['current_status' => $request->status]);

        AuditLog::record('activity_update_added', $activity, [
            'status' => $request->status,
            'remark' => $request->remark,
        ]);

        return redirect()->route('activities.show', $activity)
            ->with('success', 'Update recorded successfully.');
    }
}
