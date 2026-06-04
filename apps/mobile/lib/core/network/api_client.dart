import 'package:dio/dio.dart';
import '../../services/secure_storage_service.dart';

class ApiClient {
  final Dio _dio;
  final SecureStorageService _secureStorageService;

  ApiClient({
    required SecureStorageService secureStorageService,
    String baseUrl = 'http://localhost:8000/api/v1',
  })  : _secureStorageService = secureStorageService,
        _dio = Dio(BaseOptions(
          baseUrl: baseUrl,
          connectTimeout: const Duration(seconds: 10),
          receiveTimeout: const Duration(seconds: 10),
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
          },
        )) {
    _dio.interceptors.add(
      InterceptorsWrapper(
        onRequest: (options, handler) async {
          final token = await _secureStorageService.getToken();
          if (token != null) {
            options.headers['Authorization'] = 'Bearer $token';
          }
          return handler.next(options);
        },
        onError: (DioException e, handler) {
          // Handle global errors, e.g. 401 Unauthorized
          return handler.next(e);
        },
      ),
    );
  }

  Dio get dio => _dio;
}
