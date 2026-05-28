<?php

namespace App\Actions;

use App\Models\AppUser;
use App\Models\AuditEvent;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class AuthAction
{
    /**
     * Authenticate user and return token.
     *
     * @param string $email
     * @param string $password
     * @param string|null $deviceName
     * @param string|null $userAgent
     * @param string|null $correlationId
     * @return array
     * @throws ValidationException
     */
    public function login(string $email, string $password, ?string $deviceName = null, ?string $userAgent = null, ?string $correlationId = null): array
    {
        $user = AppUser::where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {

            // Log failed attempt if user exists
            if ($user) {
                $this->logAuthEvent($user, clone $user, 'login_failed', $correlationId, ['reason' => 'invalid_password', 'device' => $deviceName ?: $userAgent]);
            }

            throw ValidationException::withMessages([
                'email' => ['Kredensial yang diberikan salah.'],
            ]);
        }

        $tokenName = $deviceName ?? $userAgent ?? 'unknown_device';
        $token = $user->createToken($tokenName)->plainTextToken;

        $this->logAuthEvent($user, $user, 'login', $correlationId, ['device' => $tokenName]);

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Revoke current access token.
     *
     * @param AppUser $user
     * @param string|null $correlationId
     * @return void
     */
    public function logout(AppUser $user, ?string $correlationId = null): void
    {
        $user->currentAccessToken()->delete();
        $this->logAuthEvent($user, $user, 'logout', $correlationId);
    }

    /**
     * Get authenticated user profile with roles and permissions.
     *
     * @param AppUser $user
     * @return array
     */
    public function me(AppUser $user): array
    {
        $user->load(['roles', 'permissions']);

        return [
            'id' => $user->id,
            'tenant_id' => $user->tenant_id,
            'email' => $user->email,
            'status' => $user->status,
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name'),
        ];
    }

    /**
     * Log auth events to audit_events.
     */
    private function logAuthEvent($actor, $entity, string $actionType, ?string $correlationId = null, array $metadata = []): void
    {
        AuditEvent::create([
            'tenant_id' => $actor->tenant_id,
            'domain' => 'core_identity',
            'entity_type' => 'app_user',
            'entity_id' => $entity->id,
            'action_type' => $actionType,
            'actor_id' => $actor->id,
            'actor_type' => 'app_user',
            'occurred_at' => now(),
            'correlation_id' => $correlationId ?? Str::uuid()->toString(),
            'metadata_json' => $metadata,
        ]);
    }
}
