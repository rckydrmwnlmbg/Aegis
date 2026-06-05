import 'package:flutter/material.dart';
import 'package:uuid/uuid.dart';
import 'dart:convert';
import '../services/local_database_service.dart';

class PtwFormScreen extends StatefulWidget {
  const PtwFormScreen({super.key});

  @override
  State<PtwFormScreen> createState() => _PtwFormScreenState();
}

class _PtwFormScreenState extends State<PtwFormScreen> {
  final _formKey = GlobalKey<FormState>();
  final _jobTitleController = TextEditingController();
  final _locationController = TextEditingController();
  String? _selectedWorkType;

  final List<String> _workTypes = [
    'Hot Work',
    'Cold Work',
    'Confined Space',
    'Excavation',
  ];

  Future<void> _submitForm() async {
    if (_formKey.currentState!.validate()) {
      final ptwData = {
        'id': const Uuid().v4(),
        'job_title': _jobTitleController.text,
        'location': _locationController.text,
        'work_type': _selectedWorkType,
      };

      final syncItem = {
        'id': const Uuid().v4(),
        'endpoint': '/api/v1/ptw',
        'method': 'POST',
        'payload': jsonEncode(ptwData),
        'status': 'pending',
        'retry_count': 0,
        'created_at': DateTime.now().millisecondsSinceEpoch,
      };

      await LocalDatabaseService().insertSyncItem(syncItem);

      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('PTW Form saved offline and queued for sync.')),
        );
        Navigator.pop(context);
      }
    }
  }

  @override
  void dispose() {
    _jobTitleController.dispose();
    _locationController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Create PTW'),
        backgroundColor: Theme.of(context).colorScheme.inversePrimary,
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Form(
          key: _formKey,
          child: ListView(
            children: [
              TextFormField(
                controller: _jobTitleController,
                decoration: const InputDecoration(
                  labelText: 'Job Title',
                  border: OutlineInputBorder(),
                ),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Please enter a job title';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 16),
              TextFormField(
                controller: _locationController,
                decoration: const InputDecoration(
                  labelText: 'Location',
                  border: OutlineInputBorder(),
                ),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Please enter a location';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 16),
              DropdownButtonFormField<String>(
                decoration: const InputDecoration(
                  labelText: 'Work Type',
                  border: OutlineInputBorder(),
                ),
                initialValue: _selectedWorkType,
                items: _workTypes.map((type) {
                  return DropdownMenuItem(
                    value: type,
                    child: Text(type),
                  );
                }).toList(),
                onChanged: (value) {
                  setState(() {
                    _selectedWorkType = value;
                  });
                },
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Please select a work type';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 32),
              ElevatedButton(
                onPressed: _submitForm,
                style: ElevatedButton.styleFrom(
                  padding: const EdgeInsets.all(16),
                ),
                child: const Text('Submit Offline'),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
