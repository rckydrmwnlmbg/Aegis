import 'package:flutter/material.dart';
import 'package:uuid/uuid.dart';
import 'dart:convert';
import '../services/local_database_service.dart';

class SmartCaptureScreen extends StatefulWidget {
  const SmartCaptureScreen({super.key});

  @override
  State<SmartCaptureScreen> createState() => _SmartCaptureScreenState();
}

class _SmartCaptureScreenState extends State<SmartCaptureScreen> {
  bool _isRecording = false;
  final TextEditingController _manualTextController = TextEditingController();

  Future<void> _submitReport(String content, String type) async {
    final payload = {
      'id': const Uuid().v4(),
      'content': content,
      'type': type,
      'captured_at': DateTime.now().toIso8601String(),
    };

    final syncItem = {
      'id': const Uuid().v4(),
      'endpoint': '/api/v1/sync/incidents',
      'method': 'POST',
      'payload': jsonEncode(payload),
      'status': 'pending',
      'retry_count': 0,
      'created_at': DateTime.now().millisecondsSinceEpoch,
    };

    await LocalDatabaseService().insertSyncItem(syncItem);

    if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Report saved offline and queued for AI structuring.'),
        ),
      );
      Navigator.pop(context);
    }
  }

  void _showManualEntryDialog() {
    showDialog(
      context: context,
      builder: (context) {
        return AlertDialog(
          title: const Text('Manual Entry'),
          content: TextField(
            controller: _manualTextController,
            maxLines: 5,
            decoration: const InputDecoration(
              hintText: 'Describe the incident or hazard...',
              border: OutlineInputBorder(),
            ),
          ),
          actions: [
            TextButton(
              onPressed: () => Navigator.pop(context),
              child: const Text('Cancel'),
            ),
            ElevatedButton(
              onPressed: () {
                Navigator.pop(context);
                if (_manualTextController.text.isNotEmpty) {
                  _submitReport(_manualTextController.text, 'text');
                }
              },
              child: const Text('Submit'),
            ),
          ],
        );
      },
    );
  }

  @override
  void dispose() {
    _manualTextController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Smart Capture'),
        backgroundColor: Theme.of(context).colorScheme.inversePrimary,
      ),
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            GestureDetector(
              onLongPressStart: (_) {
                setState(() {
                  _isRecording = true;
                });
              },
              onLongPressEnd: (_) {
                setState(() {
                  _isRecording = false;
                });
                // Simulate saving a recorded audio file path
                _submitReport('audio_file_path_mock.m4a', 'audio');
              },
              child: AnimatedContainer(
                duration: const Duration(milliseconds: 200),
                width: _isRecording ? 180 : 150,
                height: _isRecording ? 180 : 150,
                decoration: BoxDecoration(
                  color: _isRecording
                      ? Colors.red
                      : Theme.of(context).primaryColor,
                  shape: BoxShape.circle,
                  boxShadow: [
                    if (_isRecording)
                      BoxShadow(
                        color: Colors.red.withOpacity(0.5),
                        blurRadius: 20,
                        spreadRadius: 10,
                      ),
                  ],
                ),
                child: const Icon(Icons.mic, size: 80, color: Colors.white),
              ),
            ),
            const SizedBox(height: 32),
            const Text(
              'Hold to Record Voice',
              style: TextStyle(fontSize: 18, fontWeight: FontWeight.w500),
            ),
            const SizedBox(height: 64),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceEvenly,
              children: [
                Column(
                  children: [
                    IconButton(
                      icon: const Icon(Icons.camera_alt),
                      iconSize: 40,
                      onPressed: () {
                        ScaffoldMessenger.of(context).showSnackBar(
                          const SnackBar(
                            content: Text('Camera access mocked for now.'),
                          ),
                        );
                      },
                    ),
                    const Text('Add Photo'),
                  ],
                ),
                Column(
                  children: [
                    IconButton(
                      icon: const Icon(Icons.edit_note),
                      iconSize: 40,
                      onPressed: _showManualEntryDialog,
                    ),
                    const Text('Type Manual'),
                  ],
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}
