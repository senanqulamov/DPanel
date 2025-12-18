# Global Search & Enhanced Settings Documentation

## 📋 Overview

This document describes the new professional search functionality and comprehensive SAP-level settings interface added to the DPanel application.

---

## 🔍 Global Search Feature

### Description
A powerful, keyboard-driven global search interface that allows users to quickly find and navigate to any resource in the system.

### Key Features

#### 1. **Multi-Entity Search**
Search across multiple data types simultaneously:
- **RFQs (Requests for Quotation)** - Search by ID, title, or description
- **Products** - Search by name, SKU, or description
- **Orders** - Search by order number or status
- **Markets** - Search by name or description
- **Users** - Search by name, email, or company (admin only)

#### 2. **Keyboard Navigation**
- `Ctrl+K` or `Cmd+K` - Open search modal
- `↑` / `↓` - Navigate through results
- `Enter` - Open selected item
- `Esc` - Close search modal

#### 3. **Permission-Based Results**
Search results are filtered based on user permissions, ensuring users only see resources they have access to.

#### 4. **Quick Navigation Shortcuts**
Intelligent shortcuts to common pages that match your search query:
- Dashboard
- Settings
- Users
- Products
- Orders
- Markets
- RFQs
- System Health
- Logs

#### 5. **Beautiful UI**
- Dark-themed modal with glassmorphism effects
- Color-coded result types
- Keyboard shortcut hints
- Real-time search with debouncing
- Smooth animations and transitions

### Usage

#### Access the Search
1. **Keyboard Shortcut**: Press `Ctrl+K` (Windows/Linux) or `Cmd+K` (Mac)
2. **Click**: Click the search bar in the header

#### Search Tips
- Type at least 2 characters to start searching
- Results are grouped by category
- Use arrow keys to navigate quickly
- Press Enter to go to the selected item

### Technical Implementation

**Component Location**: `app/Livewire/Search/GlobalSearch.php`
**View Location**: `resources/views/livewire/search/global-search.blade.php`

**Features**:
- Livewire-powered reactive search
- Efficient database queries with limits
- Permission-based filtering
- Alpine.js for keyboard navigation

---

## ⚙️ Enhanced Settings Interface

### Description
A comprehensive, SAP-style settings interface for administrators to configure all aspects of the system.

### Key Features

#### 1. **Tabbed Interface**
Clean, organized settings divided into logical sections:

##### **General Settings**
- Application Name
- Application URL
- Timezone configuration
- Default Language
- Environment selection
- Debug mode toggle

##### **SAP Integration**
- Enable/disable SAP integration
- SAP host configuration
- Client number
- Authentication credentials
- Language preference
- Auto-sync settings
- Sync interval configuration
- Connection testing

##### **Email Configuration**
- Mail driver selection (SMTP, Sendmail, Mailgun, SES)
- SMTP server settings
- Port and encryption
- Authentication credentials
- From address and name
- Connection testing

##### **Database Configuration**
- Connection type (MySQL, PostgreSQL, SQL Server)
- Host and port
- Database name
- Credentials
- Connection testing

##### **Cache & Queue Settings**
- Cache driver selection (Redis, Memcached, File)
- Cache TTL configuration
- Cache enable/disable
- Queue driver selection
- Retry settings
- Worker configuration

##### **Security Settings**
- Session lifetime
- Password policies:
  - Minimum length
  - Special character requirement
  - Number requirement
  - Uppercase requirement
- Login attempt limits
- Lockout duration
- Force HTTPS
- CSRF protection

##### **API Settings**
- Enable/disable API
- Rate limiting
- API key requirements
- API version management

##### **Notification Settings**
Event-based notifications:
- RFQ created
- Quote submitted
- Order placed
- SLA breach alerts

Notification channels:
- Email
- SMS
- Slack integration
- Webhook configuration

##### **Business Rules**
- RFQ default duration
- Quote validity period
- Minimum/maximum order amounts
- Approval thresholds
- Approval requirements

##### **File Upload Settings**
- Maximum upload size
- Allowed file extensions
- Virus scanning
- Storage configuration

##### **System Information**
- PHP version
- Laravel version
- Database type
- Cache driver
- Queue driver
- Timezone and locale
- Environment status
- Feature flags management

#### 2. **Admin-Only Access**
- Settings page is restricted to administrators
- Permission checks on all save operations
- Activity logging for all changes

#### 3. **Quick Actions Sidebar**
Convenient shortcuts for common tasks:
- Clear cache
- Clear logs
- Toggle maintenance mode

#### 4. **Connection Testing**
Test connections before saving:
- Test mail server connection
- Test SAP connection
- Test database connection

#### 5. **Persistent Storage**
- Settings cached for performance
- Changes logged to database
- Easy rollback capability

### Usage

#### Accessing Settings
1. Must be logged in as an administrator
2. Click your avatar in the header
3. Select "Settings" from the dropdown
4. Or navigate directly to `/settings`

#### Managing Settings
1. Select a category from the left sidebar
2. Modify the desired settings
3. Click the "Save" button for that section
4. Success/error messages will appear

#### Testing Connections
- Configure your settings first
- Click the "Test Connection" button
- Wait for the test result
- Adjust settings if test fails

### Technical Implementation

**Component Location**: `app/Livewire/Settings/Index.php`
**View Location**: `resources/views/livewire/settings/index.blade.php`

**Features**:
- Comprehensive validation
- Cache-based storage
- Database logging
- Permission checks
- Real-time updates

---

## 🚀 Installation & Setup

### Prerequisites
- Laravel 12.x
- Livewire 3.x
- TallStackUI components
- MySQL/PostgreSQL database

### Files Created/Modified

**New Files:**
1. `app/Livewire/Search/GlobalSearch.php`
2. `resources/views/livewire/search/global-search.blade.php`

**Modified Files:**
1. `app/Livewire/Settings/Index.php` - Enhanced with comprehensive settings
2. `resources/views/livewire/settings/index.blade.php` - New tabbed interface
3. `resources/views/layouts/app.blade.php` - Added global search component
4. `resources/views/components/layout/role-header.blade.php` - Updated search trigger

### No Database Migration Required
Settings are stored in cache. For persistent storage, you can optionally create a settings table.

---

## 🎨 Styling

Both features use:
- **Dark theme** with slate color palette
- **TallStackUI** components
- **Alpine.js** for interactivity
- **Tailwind CSS** for styling
- **Hero Icons** for icons

---

## 🔐 Security

### Search Feature
- Permission-based result filtering
- No direct database access from frontend
- Sanitized query inputs

### Settings Feature
- Admin-only access (enforced in mount method)
- Permission checks on all actions
- Activity logging
- CSRF protection
- Validation on all inputs

---

## 🧪 Testing

### Test Search Functionality
1. Log in as any user
2. Press `Ctrl+K`
3. Type "test" or any search term
4. Verify results appear based on your permissions
5. Use arrow keys to navigate
6. Press Enter to open an item

### Test Settings Functionality
1. Log in as an administrator
2. Navigate to Settings
3. Try each tab
4. Modify some settings
5. Click Save
6. Verify success message
7. Test connection testers

---

## 📝 Future Enhancements

### Search
- [ ] Search history
- [ ] Recent searches
- [ ] Search filters
- [ ] Advanced search syntax
- [ ] Search analytics

### Settings
- [ ] Settings import/export
- [ ] Settings templates
- [ ] Settings comparison
- [ ] Rollback functionality
- [ ] Settings audit trail

---

## 🐛 Troubleshooting

### Search Not Working
1. Verify Livewire is properly installed
2. Check browser console for JavaScript errors
3. Ensure Alpine.js is loaded
4. Verify user permissions

### Settings Not Saving
1. Verify you're logged in as admin
2. Check cache driver is working
3. Verify database connection
4. Check permissions table

### Performance Issues
1. Add database indexes on searchable columns
2. Configure Redis for caching
3. Limit search results per category
4. Add pagination if needed

---

## 📞 Support

For issues or questions, please contact the development team or create an issue in the project repository.

---

**Last Updated**: December 18, 2025
**Version**: 1.0.0
**Author**: Development Team
