import 'package:flutter/material.dart';
import 'package:uuid/uuid.dart';
import 'dart:convert';
import '../services/local_database_service.dart';

class InspectionExecutionScreen extends StatefulWidget {
  const InspectionExecutionScreen({super.key});

  @override
  State<InspectionExecutionScreen> createState() => _InspectionExecutionScreenState();
}

class _InspectionExecutionScreenState extends State<InspectionExecutionScreen> {
  final _formKey = GlobalKey<FormState>();
  final _inspectorNameController = TextEditingController();
  final _notesController = TextEditingController();
  String? _selectedTemplateId;
  String _overallStatus = 'Pass';

  // Mock templates for UI demonstration. In a real scenario, these would be fetched from local DB.
  final List<Map<String, String>> _templates = [
    {'id': 't-001', 'name': 'Daily Vehicle Inspection'},
    {'id': 't-002', 'name': 'Fire Extinguisher Check'},
    {'id': 't-003', 'name': 'Site Safety Walk'},
  ];

  Future<void> _submitInspection() async {
    if (_formKey.currentState!.validate()) {
      final inspectionId = const Uuid().v4();
      final inspectionData = {
        'id': inspectionId,
        'template_id': _selectedTemplateId,
        'inspector_name': _inspectorNameController.text,
        'status': _overallStatus,
        'notes': _notesController.text,
        'conducted_at': DateTime.now().toIso8601String(),
      };

      final payload = jsonEncode(inspectionData);

      final syncItem = {
        'id': const Uuid().v4(),
        'endpoint': '/inspections',
        'method': 'POST',
        'payload': payload,
        'status': 'pending',
        'retry_count': 0,
        'created_at': DateTime.now().millisecondsSinceEpoch,
      };

      await LocalDatabaseService().insertSyncItem(syncItem);

      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Inspection saved offline and queued for sync')),
        );
        Navigator.pop(context);
      }
    }
  }

  @override
  void dispose() {
    _inspectorNameController.dispose();
    _notesController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Execute Inspection'),
        backgroundColor: Theme.of(context).colorScheme.inversePrimary,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16.0),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              DropdownButtonFormField<String>(
                decoration: const InputDecoration(
                  labelText: 'Inspection Template',
                  border: OutlineInputBorder(),
                ),
                initialValue: _selectedTemplateId,
                items: _templates.map((template) {
                  return DropdownMenuItem<String>(
                    value: template['id'],
                    child: Text(template['name']!),
                  );
                }).toList(),
                onChanged: (value) {
                  setState(() {
                    _selectedTemplateId = value;
                  });
                },
                validator: (value) => value == null ? 'Please select a template' : null,
              ),
              const SizedBox(height: 16),
              TextFormField(
                controller: _inspectorNameController,
                decoration: const InputDecoration(
                  labelText: 'Inspector Name',
                  border: OutlineInputBorder(),
                ),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Please enter inspector name';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 16),
              const Text('Overall Status', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
              Row(
                children: [
                  Expanded(
                    child: RadioListTile<String>(
                      title: const Text('Pass'),
                      value: 'Pass',
                      groupValue: _overallStatus,
                      onChanged: (value) {
                        setState(() {
                          _overallStatus = value!;
                        });
                      },
                    ),
                  ),
                  Expanded(
                    child: RadioListTile<String>(
                      title: const Text('Fail'),
                      value: 'Fail',
                      groupValue: _overallStatus,
                      onChanged: (value) {
                        setState(() {
                          _overallStatus = value!;
                        });
                      },
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 16),
              TextFormField(
                controller: _notesController,
                maxLines: 3,
                decoration: const InputDecoration(
                  labelText: 'Inspection Notes',
                  border: OutlineInputBorder(),
                ),
              ),
              const SizedBox(height: 24),
              ElevatedButton(
                onPressed: _submitInspection,
                style: ElevatedButton.styleFrom(
                  padding: const EdgeInsets.symmetric(vertical: 16),
                ),
                child: const Text('Save Inspection'),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
