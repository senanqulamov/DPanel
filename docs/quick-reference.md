# Quick Reference Card

## 🔍 Global Search Shortcuts

| Action | Windows/Linux | Mac |
|--------|---------------|-----|
| Open Search | `Ctrl + K` | `Cmd + K` |
| Navigate Down | `↓` Arrow | `↓` Arrow |
| Navigate Up | `↑` Arrow | `↑` Arrow |
| Select Item | `Enter` | `Enter` |
| Close Search | `Esc` | `Esc` |

## 📍 Search Coverage

- **RFQs** - ID, title, description
- **Products** - Name, SKU, description
- **Orders** - Order number, status
- **Markets** - Name, description
- **Users** - Name, email, company (admin only)
- **Navigation** - Quick shortcuts to pages

## ⚙️ Settings Categories

| # | Category | Key Settings |
|---|----------|-------------|
| 1 | General | App name, URL, timezone, locale |
| 2 | SAP Integration | Host, client, credentials, sync |
| 3 | Email | SMTP settings, credentials |
| 4 | Database | Connection, host, credentials |
| 5 | Cache & Queue | Drivers, TTL, workers |
| 6 | Security | Passwords, sessions, HTTPS |
| 7 | API | Rate limits, authentication |
| 8 | Notifications | Events, channels, webhooks |
| 9 | Business Rules | RFQ duration, approvals, limits |
| 10 | File Uploads | Size limits, allowed types |
| 11 | System Info | Version info, feature flags |

## 🚀 Quick Actions (Settings Page)

- **Clear Cache** - Flush application cache
- **Clear Logs** - Remove activity logs
- **Toggle Maintenance** - Enable/disable maintenance mode

## 🧪 Connection Tests

Available in Settings:
- ✉️ **Test Mail Connection** - Verify SMTP settings
- 🔌 **Test SAP Connection** - Verify SAP integration
- 💾 **Test Database Connection** - Verify database access

## 🔐 Required Permissions

### Search:
- Searches respect existing permissions
- Users only see results they can access

### Settings:
- `view_settings` - View settings page
- `edit_settings` - Modify settings
- `manage_feature_flags` - Toggle features
- **Admin status required** - All settings require admin

## 📂 File Locations

### Search:
```
app/Livewire/Search/GlobalSearch.php
resources/views/livewire/search/global-search.blade.php
```

### Settings:
```
app/Livewire/Settings/Index.php
resources/views/livewire/settings/index.blade.php
database/seeders/SettingsPermissionsSeeder.php
```

## 💡 Tips

### Search:
- Type at least 2 characters to start
- Results are grouped by category
- Use keyboard for fastest navigation
- Recent items appear first

### Settings:
- Test connections before saving
- Changes are cached immediately
- All changes are logged
- Use Quick Actions for common tasks

## 🐛 Troubleshooting

### Search not working?
1. Check browser console
2. Verify Livewire is loaded
3. Clear browser cache
4. Check user permissions

### Settings not saving?
1. Verify admin status
2. Check cache driver
3. Check database connection
4. Review error messages

## 📞 Support

For help, check:
- `docs/search-and-settings-features.md` - Full documentation
- `docs/implementation-summary.md` - Implementation details
- Contact system administrator

---

**Version**: 1.0.0 | **Updated**: December 18, 2025
