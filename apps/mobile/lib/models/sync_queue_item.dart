class SyncQueueItem {
  final String id;
  final String endpoint;
  final String method;
  final String? payload;
  final String status;
  final int retryCount;
  final int createdAt;

  SyncQueueItem({
    required this.id,
    required this.endpoint,
    required this.method,
    this.payload,
    this.status = 'pending',
    this.retryCount = 0,
    required this.createdAt,
  });

  factory SyncQueueItem.fromMap(Map<String, dynamic> map) {
    return SyncQueueItem(
      id: map['id'],
      endpoint: map['endpoint'],
      method: map['method'],
      payload: map['payload'],
      status: map['status'],
      retryCount: map['retry_count'],
      createdAt: map['created_at'],
    );
  }

  Map<String, dynamic> toMap() {
    return {
      'id': id,
      'endpoint': endpoint,
      'method': method,
      'payload': payload,
      'status': status,
      'retry_count': retryCount,
      'created_at': createdAt,
    };
  }
}
