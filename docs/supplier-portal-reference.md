# Supplier Portal - Quick Reference Guide

## Overview
The Supplier Portal allows suppliers to receive RFQ invitations, submit quotes, and track their submissions.

## Access
- **URL Pattern:** `/supplier/*`
- **Middleware:** `auth`, `supplier`
- **Requirement:** User must have `is_supplier` flag set to `true`

## Available Routes

### Dashboard
- **Route:** `/supplier/dashboard`
- **Component:** `App\Livewire\Supplier\Dashboard`
- **Features:**
  - View pending invitations count
  - View active quotes count
  - View won quotes count
  - View total revenue
  - Quick links to all sections

### Invitations
- **Route:** `/supplier/invitations`
- **Component:** `App\Livewire\Supplier\Invitations\Index`
- **Features:**
  - View all RFQ invitations
  - Filter by status (pending, accepted, declined, quoted)
  - Search by RFQ title/description
  - Accept or decline invitations
  - Navigate to quote submission

**Actions:**
- `acceptInvitation($invitationId)` - Accept an invitation
- `declineInvitation($invitationId)` - Decline an invitation

### Quotes
- **Route:** `/supplier/quotes`
- **Component:** `App\Livewire\Supplier\Quotes\Index`
- **Features:**
  - View all submitted quotes
  - Filter by status (draft, submitted, under_review, won, lost, withdrawn)
  - Search by RFQ title
  - View quote details
  - Track quote validity

**Quote Statuses:**
- `draft` - Quote not yet submitted
- `submitted` - Quote submitted, awaiting review
- `under_review` - Buyer is reviewing the quote
- `won` - Quote accepted by buyer
- `lost` - Quote rejected by buyer
- `withdrawn` - Quote withdrawn by supplier

### Quote Creation
- **Route:** `/supplier/quotes/create/{invitation}`
- **Component:** `App\Livewire\Supplier\Quotes\Create`
- **Features:**
  - Multi-item quote form
  - Price calculation with tax
  - Terms & conditions
  - Validity period
  - File attachments (coming soon)

**Form Fields:**
- **Per Item:**
  - Description (required)
  - Quantity (required, decimal)
  - Unit Price (required, decimal)
  - Tax Rate (optional, 0-100%)
  - Notes (optional)

- **Quote Level:**
  - Valid Until (required, date)
  - Notes (optional, max 1000 chars)
  - Terms & Conditions (optional, max 2000 chars)

**Calculations:**
- Subtotal = Quantity × Unit Price
- Tax = Subtotal × (Tax Rate / 100)
- Item Total = Subtotal + Tax
- Quote Total = Sum of all Item Totals

### Messages (Placeholder)
- **Route:** `/supplier/messages`
- **Component:** `App\Livewire\Supplier\Messages\Index`
- **Status:** Placeholder - Coming in future release

## Events Fired

### Quote Submission
When a supplier submits a quote:
1. `QuoteSubmitted` event is fired
2. `NotifyBuyerOfQuoteSubmission` listener sends notification to buyer
3. `RecordWorkflowEvent` listener logs the event
4. Invitation status updated to `quoted`

## Notifications Received

Suppliers receive notifications for:
- **Supplier Invitation:** When invited to quote on an RFQ
- **RFQ Status Changes:** When RFQ status changes affect their quote
- **Quote Status Updates:** When buyer reviews/accepts/rejects quote
- **SLA Reminders:** When deadlines are approaching

## UI Components Used

### From TallStackUI:
- `x-card` - Card containers
- `x-alert` - Alert messages
- `x-table` - Data tables with pagination
- `x-badge` - Status badges
- `x-button` - Action buttons
- `x-input` - Text inputs
- `x-number` - Numeric inputs
- `x-textarea` - Text areas
- `x-select.styled` - Dropdown selects
- `x-stat` - KPI statistics

### Color Scheme:
- Purple/Indigo - Supplier branding
- Blue - Informational
- Green - Success/Won
- Yellow - Pending/Warning
- Red - Declined/Lost
- Gray - Neutral/Draft

## Database Tables Used

### Primary Tables:
- `supplier_invitations` - RFQ invitations to suppliers
- `quotes` - Supplier quotes
- `quote_items` - Individual line items in quotes
- `requests` - RFQ requests (read-only for suppliers)
- `request_items` - RFQ items (read-only for suppliers)

### Supporting Tables:
- `workflow_events` - Audit trail of all events
- `notifications` - In-app notifications

## Model Relationships

```php
// SupplierInvitation
$invitation->request         // RFQ request
$invitation->supplier        // Supplier user
$invitation->quotes          // Submitted quotes

// Quote
$quote->request              // RFQ request
$quote->supplier             // Supplier user
$quote->supplierInvitation   // Original invitation
$quote->items                // Quote line items

// QuoteItem
$quoteItem->quote            // Parent quote
$quoteItem->requestItem      // Related RFQ item
$quoteItem->subtotal         // Calculated
$quoteItem->taxAmount        // Calculated
$quoteItem->total            // Calculated
```

## Workflow

### Typical Supplier Journey:

1. **Receive Invitation**
   - Email notification sent
   - Invitation appears in `/supplier/invitations`
   - Status: `pending`

2. **Review RFQ**
   - View RFQ details
   - Check deadline
   - Review requirements

3. **Accept/Decline**
   - Accept if interested → Status: `accepted`
   - Decline if not interested → Status: `declined`

4. **Submit Quote** (if accepted)
   - Navigate to quote creation form
   - Fill in pricing for each item
   - Add terms & conditions
   - Set validity period
   - Submit quote

5. **Track Quote**
   - View quote in `/supplier/quotes`
   - Monitor status changes
   - Receive notification on buyer decision

6. **Win/Loss**
   - If won → Quote status: `won`, potential for PO
   - If lost → Quote status: `lost`, quote archived

## Security

### Access Control:
- Middleware ensures only authenticated suppliers can access
- Suppliers can only view/edit their own invitations and quotes
- RFQ details visible only when invited

### Validation:
- All form inputs validated
- Price fields must be non-negative
- Dates must be in the future
- Required fields enforced

## API Methods

### Supplier Dashboard Component:
```php
mount()  // Load KPIs and statistics
render() // Render the view
```

### Invitations Index Component:
```php
rows()                         // Paginated invitations list (computed)
acceptInvitation($id)          // Accept an invitation
declineInvitation($id)         // Decline an invitation
```

### Quotes Index Component:
```php
rows()  // Paginated quotes list (computed)
```

### Quotes Create Component:
```php
mount($invitation)    // Initialize form
addItem()            // Add quote item
removeItem($index)   // Remove quote item
calculateTotal()     // Calculate total amount
save()              // Submit the quote
```

## Customization Points

### Extending Functionality:
1. Add file upload for quote attachments
2. Implement messaging between supplier and buyer
3. Add quote versioning
4. Implement quote templates
5. Add bulk quote submission for multiple RFQs

### Styling:
- All views use TallStackUI components
- Dark mode fully supported
- Responsive design for mobile/tablet
- Consistent with main application theme

## Troubleshooting

### Cannot Access Supplier Routes:
- Ensure user has `is_supplier` flag set to `true`
- Check if user is authenticated
- Verify middleware is registered

### Quote Not Submitting:
- Check validation errors
- Ensure all required fields filled
- Verify invitation is still valid
- Check deadline hasn't passed

### Events Not Firing:
- Ensure queue worker is running: `php artisan queue:work`
- Check event listeners are registered in `AppServiceProvider`
- Verify event class is being dispatched

## Future Enhancements (Planned)

- [ ] Direct messaging with buyers
- [ ] File attachments for quotes
- [ ] Quote templates
- [ ] Batch quote submission
- [ ] Performance analytics dashboard
- [ ] Quote comparison tools
- [ ] Contract management integration
