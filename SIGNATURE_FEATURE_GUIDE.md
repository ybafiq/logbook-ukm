# Online Signature Feature - Implementation Guide

## Overview
This guide documents the online signature feature for supervisors to sign and approve student logbook entries. The signature will appear on the exported PDF in the "Cop dan tandatangan penyelia industri (setiap 3 minggu)" section.

## Features Implemented

### 1. Supervisor Signature Functionality
- **Digital Signature Pad**: Supervisors can draw their signature using mouse or touch input
- **Approval with Signature**: When approving entries, supervisors must provide their signature
- **Optional Comments**: Supervisors can add comments along with the signature
- **Signature Storage**: Signatures are stored as PNG images in `storage/app/public/signatures/`

### 2. PDF Integration
- Supervisor signatures automatically appear on exported PDFs
- Signatures are displayed in the "Industry supervisor's cop and signature (every 3 weeks)" section
- Shows the most recent approved entry's signature, date, and comments
- Maintains proper formatting for professional appearance

### 3. Admin Management
- **Signature Dashboard**: Admins can view all signatures for both log and project entries
- **View Signatures**: Click to preview any signature in a modal
- **Delete Signatures**: Remove signatures if needed (with confirmation)
- **Separate Tabs**: Organized view for log entries and project entries

## Database Changes

### Migration: `2025_11_12_030708_add_signature_fields_to_log_and_project_entries.php`
Added to both `log_entries` and `project_entries` tables:
- `supervisor_signature` (string, nullable): Path to signature image file
- `supervisor_comment` (text, nullable): Supervisor's comments during approval

## Files Modified/Created

### Controllers
1. **SupervisorController.php**
   - Updated `approveEntry()` method to handle signature upload
   - Updated `approveProjectEntry()` method to handle signature upload
   - Signatures are saved as base64-decoded PNG files

2. **AdminController.php** (NEW)
   - `manageSignatures()`: Display signature management dashboard
   - `deleteLogSignature()`: Delete signature from log entry
   - `deleteProjectSignature()`: Delete signature from project entry

### Models
1. **LogEntry.php**
   - Added `supervisor_signature` and `supervisor_comment` to `$fillable`

2. **ProjectEntry.php**
   - Added `supervisor_signature` and `supervisor_comment` to `$fillable`

### Views

#### Supervisor Views
1. **pending-entries.blade.php**
   - Changed approval button to "Approve & Sign" button
   - Added signature modal with canvas for drawing
   - JavaScript for signature pad functionality
   - Validation to ensure signature is provided

2. **pending-project-entries.blade.php**
   - Same changes as pending-entries.blade.php
   - Signature modal works for both entry types

#### Admin Views
1. **admin/signatures.blade.php** (NEW)
   - Tabbed interface for log and project entries
   - Table showing student, supervisor, dates, and signatures
   - View signature modal
   - Delete signature functionality

#### PDF Template
1. **exports/logbook.blade.php**
   - Updated supervisor section to display signature if available
   - Shows signature image, approval date, and comments
   - Falls back to empty section if no signature exists

### Routes (web.php)
Added admin routes:
```php
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('admin/signatures', [AdminController::class, 'manageSignatures'])
        ->name('admin.manageSignatures');
    Route::delete('admin/signatures/log/{entry}', [AdminController::class, 'deleteLogSignature'])
        ->name('admin.deleteLogSignature');
    Route::delete('admin/signatures/project/{entry}', [AdminController::class, 'deleteProjectSignature'])
        ->name('admin.deleteProjectSignature');
});
```

### Navigation
- Added "Manage Signatures" button in users index page (admin only)

## How It Works

### For Supervisors:
1. Go to "Pending Log Entries" or "Pending Project Entries"
2. Click "Approve & Sign" button for an entry
3. A modal appears with:
   - Optional comment field
   - Signature canvas for drawing
   - Clear button to reset signature
4. Draw signature using mouse or touch
5. Click "Approve & Sign" to submit
6. Signature is saved and entry is approved

### For Students:
1. Export logbook as usual
2. If a supervisor has signed any entry, the signature will automatically appear in the PDF
3. The PDF shows the most recent signature, date, and comments in the supervisor section

### For Admins:
1. Go to Users page
2. Click "Manage Signatures" button
3. View all signatures in organized tabs
4. Click "View" to preview a signature
5. Click "Delete" to remove a signature (if needed)

## Technical Details

### Signature Storage
- Location: `storage/app/public/signatures/`
- Format: PNG images
- Naming: `signature_{timestamp}_{entry_id}.png`
- Public access via: `public/storage/signatures/` (symlink)

### Canvas Signature Pad
- Size: 700x200 pixels
- Drawing: Mouse and touch support
- Color: Black (#000)
- Line width: 2px
- Validation: Ensures signature is not blank before submission

### PDF Rendering
- Uses PHP code to find most recent signed entry
- Displays signature image from storage
- Shows approval date and comments
- Gracefully handles missing signatures

## Security Considerations
- Signatures are only accessible by authenticated users
- Admin role required to delete signatures
- Supervisor role required to add signatures
- File validation ensures only PNG images are stored
- Signature deletion also removes the file from storage

## Future Enhancements (Optional)
- Allow supervisors to upload image files as signatures
- Add signature history/log for each student
- Email notifications when entries are signed
- Weekly/monthly signature reports
- Signature templates for supervisors

## Troubleshooting

### Storage Link Issues
If signatures don't appear, ensure storage is linked:
```bash
php artisan storage:link
```

### Permission Issues
Ensure `storage/app/public/signatures/` has write permissions:
```bash
chmod -R 775 storage/app/public/signatures/
```

### Missing Signatures in PDF
- Check if the entry actually has a signature
- Verify the signature file exists in storage
- Ensure the PDF template is using the correct path

## Testing Checklist
- [ ] Supervisor can draw and submit signature
- [ ] Signature appears in database
- [ ] Signature file is saved to storage
- [ ] Entry is marked as approved
- [ ] Signature appears on exported PDF
- [ ] Admin can view all signatures
- [ ] Admin can delete signatures
- [ ] Signature modal works on both log and project entries
- [ ] Touch devices can draw signatures
- [ ] Empty signatures are rejected
