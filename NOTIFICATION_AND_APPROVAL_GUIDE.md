# Notification and Approval System Guide

## Overview
This guide explains the new notification system and flexible approval workflow for supervisors.

## New Features

### 1. **Automatic Notifications for Supervisors**
- When a student submits a new log entry or project entry, **all supervisors** receive notifications
- Notifications are sent via:
  - **Database** (in-app notifications)
  - **Email** (if configured)

### 2. **Flexible Approval Workflow**
Supervisors now have two approval options:

#### Option A: Quick Approve (Daily Use)
- Click the **"Approve"** button to approve an entry without signature
- Use this for daily approvals
- Fast and efficient for regular review

#### Option B: Sign Entry (Every 3 Weeks)
- Click the **"Sign"** button to add your signature
- Opens a modal with signature canvas
- Required only once every three weeks per the logbook requirements
- Signature is stored and displayed in PDF exports

## How It Works

### For Students
1. Student creates a new log entry or project entry
2. System automatically notifies all supervisors
3. Entry appears in supervisor's pending list
4. Student can continue working while waiting for approval

### For Supervisors

#### Viewing Notifications
1. **Navbar Badge**: Red notification count appears on "Supervisor" dropdown
2. **Dashboard**: Unread notifications displayed at the top with details:
   - Student name
   - Entry type (log/project)
   - Entry date
   - Activity preview
   - Time submitted
3. **Mark as Read**: Click "Mark All as Read" to clear notifications

#### Approving Entries

**Quick Approve (No Signature)**
1. Navigate to "Pending Log Entries" or "Pending Project Entries"
2. Click the green **"Approve"** button
3. Confirm approval in popup dialog
4. Entry is marked as approved without signature

**Approve with Signature**
1. Navigate to "Pending Log Entries" or "Pending Project Entries"
2. Click the blue **"Sign"** button
3. Modal opens with:
   - Optional comment field
   - Signature canvas
4. Draw your signature with mouse/touchscreen
5. Click "Add Signature" to approve and sign
6. Signature is saved and entry is approved

## Email Notifications

### Configuration
To enable email notifications, configure your `.env` file:

```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email@example.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourapp.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Email Content
Supervisors receive emails with:
- Subject: "New [Log/Project] Entry Submitted - [Student Name]"
- Entry date and activity preview
- Direct link to review page

## Database Structure

### Notifications Table
```sql
- id
- type (App\Notifications\NewEntrySubmitted)
- notifiable_type (App\Models\User)
- notifiable_id (supervisor user ID)
- data (JSON with entry details)
- read_at (timestamp when marked as read)
- created_at
- updated_at
```

### Notification Data Fields
```json
{
  "entry_id": 123,
  "entry_type": "log|project",
  "student_id": 456,
  "student_name": "John Doe",
  "entry_date": "2025-12-10",
  "activity": "Activity description...",
  "message": "John Doe submitted a new log entry for Dec 10, 2025"
}
```

## UI Components

### Supervisor Dashboard
- **Notification Card**: Displays up to 5 recent unread notifications
- **Statistics Cards**: Show pending counts for log and project entries
- **Quick Links**: Direct access to pending entry lists

### Pending Entries Pages
- **View Button** (Blue): View entry details
- **Approve Button** (Green): Quick approve without signature
- **Sign Button** (Info Blue): Add signature and approve

### Signature Modal
- **Title**: "Add Signature"
- **Comment Field**: Optional supervisor notes
- **Canvas**: Draw signature with mouse/touch
- **Clear Button**: Reset canvas
- **Submit Button**: "Add Signature"

## Best Practices

### For Supervisors
1. **Daily Review**: Use "Approve" button for daily entry reviews
2. **Weekly Signature**: Add signature once every three weeks as required
3. **Check Notifications**: Review dashboard notifications regularly
4. **Add Comments**: Provide feedback when needed

### For Students
1. **Daily Entries**: Submit entries regularly for timely review
2. **Complete Information**: Include detailed activity descriptions
3. **Check Status**: Monitor approval status in your entry list

## Troubleshooting

### Notifications Not Appearing
1. Check that you're logged in as a supervisor
2. Verify notifications table exists: `php artisan migrate`
3. Test by creating a new entry as a student

### Email Not Sending
1. Verify `.env` email configuration
2. Test email: `php artisan tinker` then `Mail::raw('Test', function($m) { $m->to('test@example.com')->subject('Test'); });`
3. Check mail logs in `storage/logs/laravel.log`

### Signature Not Saving
1. Ensure storage is linked: `php artisan storage:link`
2. Check `storage/app/public/signatures/` directory permissions
3. Verify signature validation in browser console (F12)

## API Routes

### Supervisor Routes
```php
POST /supervisor/approve-entry/{entry}           // Approve log entry
POST /supervisor/approve-project-entry/{entry}   // Approve project entry
POST /supervisor/mark-all-read                   // Mark notifications as read
GET  /supervisor/dashboard                       // View dashboard with notifications
GET  /supervisor/pending-entries                 // View pending log entries
GET  /supervisor/pending-project-entries         // View pending project entries
```

## Code Examples

### Sending Notification (LogEntryController.php)
```php
// Notify all supervisors about the new entry
$supervisors = User::where('role', 'supervisor')->get();
foreach ($supervisors as $supervisor) {
    $supervisor->notify(new NewEntrySubmitted($entry, auth()->user(), 'log'));
}
```

### Quick Approve (JavaScript)
```javascript
function quickApprove(entryId, type) {
    if (!confirm('Approve this entry without signature?')) {
        return;
    }
    
    const form = document.getElementById('quickApproveForm');
    form.action = `/supervisor/approve-entry/${entryId}`;
    form.submit();
}
```

### Signature Approval (JavaScript)
```javascript
function openSignatureModal(entryId, type) {
    // Clear previous data
    clearSignature();
    document.getElementById('supervisor_comment').value = '';
    
    // Set form action
    const form = document.getElementById('signatureForm');
    form.action = `/supervisor/approve-entry/${entryId}`;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('signatureModal'));
    modal.show();
}
```

## Summary

The new system provides:
- ✅ Real-time notifications for supervisors
- ✅ Flexible approval workflow (approve or sign)
- ✅ Email notifications (if configured)
- ✅ Signature requirement flexibility (every 3 weeks)
- ✅ Better user experience for daily workflow
- ✅ Complete audit trail of approvals and signatures

This improves efficiency while maintaining the requirement for supervisors to sign every three weeks, as documented in the logbook export template.
