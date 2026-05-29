sed -i 's/if ($attachmentIds) {/if ($attachmentIds) {\n                $attachmentIds = array_unique($attachmentIds);/g' apps/api/app/Actions/Sync/SyncPayloadAction.php
