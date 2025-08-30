# Epic 6: QR Payment Generation

## Story 6.1: SPAYD QR Code Generator

As a user,
I want to generate QR codes for payments,
so that I can pay with two clicks in my banking app.

### Acceptance Criteria

1: Generate SPAYD format QR codes for CZK payments
2: Include all payment details (account, amount, VS, message)
3: QR code image generation in PNG format
4: Validate bank account format (Czech standards)
5: Error handling for invalid payment data

## Story 6.2: Batch QR Display

As a user,
I want to see all pending payment QRs on one screen,
so that I can quickly process multiple payments.

### Acceptance Criteria

1: Grid layout showing multiple QR codes
2: Payment details under each QR
3: Mark as paid button for each
4: Print-friendly layout option
5: Mobile responsive design for scanning

## Story 6.3: Payment Status Management

As a user,
I want to mark payments as completed,
so that I can track what's been paid.

### Acceptance Criteria

1: Mark payment as paid with timestamp
2: Optional note field for payment reference
3: Paid payments move to history
4: Undo payment marking within 24 hours
5: Payment history with search and filters
