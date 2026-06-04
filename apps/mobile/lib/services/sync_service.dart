import 'dart:convert';
import 'package:dio/dio.dart';
import '../core/network/api_client.dart';
import 'local_database_service.dart';
import '../models/sync_queue_item.dart';
import 'secure_storage_service.dart';

class SyncService {
  final LocalDatabaseService _dbService;
  final ApiClient _apiClient;

  SyncService({
    LocalDatabaseService? dbService,
    ApiClient? apiClient,
  })  : _dbService = dbService ?? LocalDatabaseService(),
        _apiClient = apiClient ?? ApiClient(secureStorageService: SecureStorageService());

  Future<void> processSyncQueue() async {
    final pendingItemsRaw = await _dbService.getPendingSyncItems();
    final pendingItems = pendingItemsRaw.map((e) => SyncQueueItem.fromMap(e)).toList();

    for (var item in pendingItems) {
      try {
        Response response;
        final data = item.payload != null ? jsonDecode(item.payload!) : null;

        if (item.method.toUpperCase() == 'POST') {
          response = await _apiClient.dio.post(item.endpoint, data: data);
        } else if (item.method.toUpperCase() == 'PUT') {
          response = await _apiClient.dio.put(item.endpoint, data: data);
        } else if (item.method.toUpperCase() == 'PATCH') {
          response = await _apiClient.dio.patch(item.endpoint, data: data);
        } else if (item.method.toUpperCase() == 'DELETE') {
          response = await _apiClient.dio.delete(item.endpoint, data: data);
        } else {
          // Unsupported method, skip
          continue;
        }

        if (response.statusCode == 200 || response.statusCode == 201 || response.statusCode == 202) {
          // Success, remove from queue
          await _dbService.removeSyncItem(item.id);
        } else {
          // Failed with non-2xx status code
          await _handleFailure(item);
        }
      } catch (e) {
        // Network error or other exception
        await _handleFailure(item);
      }
    }
  }

  Future<void> _handleFailure(SyncQueueItem item) async {
    final newRetryCount = item.retryCount + 1;
    // We could set a max retry count before marking as failed, e.g., 5
    final status = newRetryCount >= 5 ? 'failed' : 'pending';

    await _dbService.updateSyncItemStatus(item.id, status, newRetryCount);
  }
}
