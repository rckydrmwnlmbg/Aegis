cd apps/api

php artisan make:model Attachment -m
php artisan make:model AttachmentLink -m
php artisan make:model AiRun -m
php artisan make:controller Api/V1/AttachmentController
php artisan make:request StoreAttachmentRequest
php artisan make:controller Api/V1/SyncController
php artisan make:request SyncIncidentRequest
php artisan make:request SyncHazardRequest
php artisan make:job ProcessIncidentDataJob
php artisan make:job ProcessHazardDataJob
mkdir -p app/Actions/Attachment
mkdir -p app/Actions/Sync
php artisan make:test Api/V1/AttachmentTest
php artisan make:test Api/V1/SyncTest
