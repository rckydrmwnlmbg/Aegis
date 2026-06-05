import 'package:flutter/material.dart';
import 'package:uuid/uuid.dart';
import 'dart:convert';
import '../services/local_database_service.dart';

class JsaFormScreen extends StatefulWidget {
  const JsaFormScreen({super.key});

  @override
  State<JsaFormScreen> createState() => _JsaFormScreenState();
}

class _JsaFormScreenState extends State<JsaFormScreen> {
  final _formKey = GlobalKey<FormState>();
  final _jobStepController = TextEditingController();
  final _potentialHazardsController = TextEditingController();
  final _controlMeasuresController = TextEditingController();
  String? _selectedRiskLevel;

  final List<String> _riskLevels = [
    'Low',
    'Medium',
    'High',
    'Critical',
  ];

  Future<void> _submitForm() async {
    if (_formKey.currentState!.validate()) {
      final jsaData = {
        'id': const Uuid().v4(),
        'job_step': _jobStepController.text,
        'potential_hazards': _potentialHazardsController.text,
        'risk_level': _selectedRiskLevel,
        'control_measures': _controlMeasuresController.text,
      };

      final syncItem = {
        'id': const Uuid().v4(),
        'endpoint': '/api/v1/jsa',
        'method': 'POST',
        'payload': jsonEncode(jsaData),
        'status': 'pending',
        'retry_count': 0,
        'created_at': DateTime.now().millisecondsSinceEpoch,
      };

      await LocalDatabaseService().insertSyncItem(syncItem);

      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('JSA Form saved offline and queued for sync.')),
        );
        Navigator.pop(context);
      }
    }
  }

  @override
  void dispose() {
    _jobStepController.dispose();
    _potentialHazardsController.dispose();
    _controlMeasuresController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Create JSA'),
        backgroundColor: Theme.of(context).colorScheme.inversePrimary,
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Form(
          key: _formKey,
          child: ListView(
            children: [
              TextFormField(
                controller: _jobStepController,
                decoration: const InputDecoration(
                  labelText: 'Job Step',
                  border: OutlineInputBorder(),
                ),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Please enter a job step';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 16),
              TextFormField(
                controller: _potentialHazardsController,
                maxLines: 3,
                decoration: const InputDecoration(
                  labelText: 'Potential Hazards',
                  border: OutlineInputBorder(),
                ),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Please enter potential hazards';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 16),
              DropdownButtonFormField<String>(
                decoration: const InputDecoration(
                  labelText: 'Risk Level',
                  border: OutlineInputBorder(),
                ),
                initialValue: _selectedRiskLevel,
                items: _riskLevels.map((level) {
                  return DropdownMenuItem(
                    value: level,
                    child: Text(level),
                  );
                }).toList(),
                onChanged: (value) {
                  setState(() {
                    _selectedRiskLevel = value;
                  });
                },
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Please select a risk level';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 16),
              TextFormField(
                controller: _controlMeasuresController,
                maxLines: 3,
                decoration: const InputDecoration(
                  labelText: 'Control Measures',
                  border: OutlineInputBorder(),
                ),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Please enter control measures';
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
