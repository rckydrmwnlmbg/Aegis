import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';

import 'package:mobile/main.dart';

void main() {
  testWidgets('App renders correctly', (WidgetTester tester) async {
    // Build our app and trigger a frame.
    await tester.pumpWidget(const MyApp());

    // Verify that the title and buttons exist
    expect(find.text('Aegis Home'), findsOneWidget);
    expect(find.text('Create PTW'), findsOneWidget);
    expect(find.text('Create JSA'), findsOneWidget);
  });
}
