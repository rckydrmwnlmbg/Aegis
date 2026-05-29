<?php

namespace App\Actions\Permits;

use App\Enums\PermitStatus;
use App\Models\PermitToWork;
use App\Models\PermitApproval;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;
use Exception;
use InvalidArgumentException;

class UpdatePermitStatusAction
{
    protected array $allowedTransitions = [
        PermitStatus::DRAFT->value => [PermitStatus::PENDING_APPROVAL->value],
        PermitStatus::PENDING_APPROVAL->value => [PermitStatus::APPROVED->value],
        PermitStatus::APPROVED->value => [PermitStatus::ACTIVE->value],
        PermitStatus::ACTIVE->value => [PermitStatus::SUSPENDED->value, PermitStatus::CLOSED->value],
        PermitStatus::SUSPENDED->value => [PermitStatus::ACTIVE->value, PermitStatus::CLOSED->value],
    ];

    public function execute(PermitToWork $permit, string $newStatus, ?string $notes = null): PermitToWork
    {
        if (!in_array($newStatus, PermitStatus::values())) {
            throw new InvalidArgumentException("Invalid status: {$newStatus}");
        }

        $currentStatus = $permit->status;

        if (!isset($this->allowedTransitions[$currentStatus]) || !in_array($newStatus, $this->allowedTransitions[$currentStatus])) {
            throw new Exception("Invalid transition from {$currentStatus} to {$newStatus}");
        }

        // PTW MUST link to an approved JSA before approval
        if ($newStatus === PermitStatus::APPROVED->value) {
            if (!$permit->jsa_id) {
                throw new Exception("Permit must be linked to a valid JSA.");
            }

            if (!Gate::allows('permit:approve')) {
                throw new AuthorizationException("User does not have permission to approve permits.");
            }
        }

        DB::transaction(function () use ($permit, $newStatus, $notes) {
            if ($newStatus === PermitStatus::APPROVED->value) {
                $user = auth()->user();
                $roleName = $user && method_exists($user, 'getRoleNames') ? $user->getRoleNames()->first() : null;

                PermitApproval::create([
                    'tenant_id' => $permit->tenant_id,
                    'permit_id' => $permit->id,
                    'approver_id' => $user->id,
                    'role_saat_menyetujui' => $roleName,
                    'decision' => 'approved',
                    'decision_notes' => $notes,
                    'decided_at' => now(),
                ]);
            }

            $permit->status = $newStatus;
            $permit->save();
        });

        return $permit;
    }
}
