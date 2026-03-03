# TP Assessment System - Quick Start Guide

## Prerequisites

- PHP >= 7.2
- MySQL/MariaDB
- Composer
- Yii2 Framework (already installed)
- XAMPP or similar local server

## Step-by-Step Setup

### Step 1: Verify Database Connection

Ensure your database is accessible and update credentials if needed:

**File: `/common/config/db.php`**
```php
'db' => [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=trials',  // Update dbname if different
    'username' => 'root',              // Your database user
    'password' => '',                  // Your database password
    'charset' => 'utf8mb4',
    'tablePrefix' => 'tp_',            // Tables will use tp_ prefix
],
```

### Step 2: Create Database (if not exists)

```bash
# Open MySQL command line or phpMyAdmin
mysql -u root -p

# Create database
CREATE DATABASE trials CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

### Step 3: Run Database Migrations

This creates all tables and inserts initial data:

```bash
cd c:\xampp\htdocs\trials

# Run all migrations
php yii migrate --migrationPath=@console/migrations --interactive=0

# Or interactively (will prompt for confirmation)
php yii migrate --migrationPath=@console/migrations
```

**Expected output:**
```
*** Yii Database Migration Tool ***

Total 11 new migrations to be applied:
    m260303_120000_create_tp_student_table
    m260303_120001_create_tp_lecturer_table
    ...
    m260303_120010_setup_tp_rbac

Apply the migrations? (yes|no) [no]:yes

*** Applied 11 migrations ***
```

### Step 4: Create Required Directories

```bash
# Create upload directories
mkdir -p backend\web\uploads\assessments
mkdir -p backend\web\uploads\reports
mkdir -p backend\runtime\logs
mkdir -p frontend\runtime\logs
mkdir -p common\runtime
```

### Step 5: Configure RBAC (Authorization)

**File: `/backend/config/main.php`**

Add/ensure this configuration exists:

```php
'components' => [
    'authManager' => [
        'class' => 'yii\rbac\DbManager',
        'defaultRoles' => ['guest'],
    ],
    // ... other components
],
```

### Step 6: Configure Authentication Manager (Optional but Recommended)

**File: `/common/config/main.php`**

```php
'components' => [
    'db' => [ ... ],
    'mailer' => [
        'class' => 'yii\swiftmailer\Mailer',
        'useFileTransport' => true,  // Set to false in production with real email
    ],
],
'params' => [
    'senderEmail' => 'noreply@university.edu',
],
```

### Step 7: Start Development Server

```bash
cd c:\xampp\htdocs\trials

# Start backend server
php yii serve --port=8080

# In another terminal, start frontend (optional)
php yii serve --port=3000 --appConfig=frontend/config/main-local.php
```

Or use XAMPP directly:
- Place files in `C:\xampp\htdocs\trials`
- Access backend: `http://localhost/trials/backend/web`
- Access frontend: `http://localhost/trials/frontend/web`

## Accessing the System

### Backend (Main Assessment System)
```
URL: http://localhost/trials/backend/web
```

### Create Initial User (Database)

Since this is a Yii2 advanced template, you need a user to login. 

**Option 1: Create user via database**

```sql
USE trials;

-- Create a user (password: 123456)
INSERT INTO user (username, email, password_hash, status, created_at, updated_at) VALUES 
('admin', 'admin@university.edu', '$2y$13$...', 10, NOW(), NOW());

-- Get password hash in Yii:
-- Go to Yii shell: php yii
-- Then: Yii::$app->security->generatePasswordHash('123456')
```

**Option 2: Use Yii console command**

```bash
php yii user/add admin admin@university.edu 123456
```

### Assign User to TP Lecturer Role

After creating a user, assign them to TP Lecturer:

```sql
-- Assign user to tp_supervisor role
INSERT INTO auth_assignment (item_name, user_id, created_at) 
VALUES ('tp_supervisor', 1, NOW());

-- Also create tp_lecturer record
INSERT INTO tp_lecturer (user_id, name, tp_assigned_code, role, is_active, created_at, updated_at) 
VALUES (1, 'John Supervisor', 'SUP001', 'supervisor', 1, NOW(), NOW());
```

## Testing the System

### 1. Login to Backend
```
Username: admin (or your created username)
Password: 123456 (or your password)
```

### 2. Access Dashboard
After login, you should see the TP Assessment Dashboard with:
- Total Assessments counter
- Draft Assessments count
- Submitted Assessments count
- Validated Assessments count
- Quick action buttons

### 3. Test Assessment Creation

**Steps:**
1. Click **"Create New Assessment"** button
2. Select a student (you may need to add test students first)
3. Enter assessment date
4. Score each of the 12 competence areas (0-10)
5. Add remarks for each area
6. Upload supporting images (optional)
7. Click **"Save Assessment"**

### 4. Add Test Data

**Add Test Students (SQL):**
```sql
INSERT INTO tp_student (registration_number, full_name, school, zone, class_form, learning_area_subject, pathway, created_at, updated_at) VALUES
('STU001', 'Alice Johnson', 'Kenya Primary School', 'Zone A', 'TP Class A', 'Mathematics', 'CBC', NOW(), NOW()),
('STU002', 'Bob Smith', 'St. Mary School', 'Zone B', 'TP Class B', 'English', 'CBC', NOW(), NOW()),
('STU003', 'Carol White', 'Mombasa Academy', 'Zone C', 'TP Class C', 'Science', 'CBC', NOW(), NOW());
```

## Role-Based Testing

### Test Different Roles:

#### Supervisor Role Access:
- ✅ Create assessments
- ✅ Edit own assessments (before submission)
- ✅ Submit assessments
- ✅ View assessment details
- ✅ View own reports
- ❌ Cannot validate
- ❌ Cannot view all assessments

**Setup:**
```sql
INSERT INTO auth_assignment (item_name, user_id, created_at) 
VALUES ('tp_supervisor', 1, NOW());
```

#### Zone Coordinator Role:
- ✅ Review all assessments in zone
- ✅ Validate assessments
- ✅ Reject assessments
- ✅ View audit logs
- ✅ Create/manage reports
- ❌ Cannot create new assessments (inherits supervisor permissions)

**Setup:**
```sql
INSERT INTO auth_assignment (item_name, user_id, created_at) 
VALUES ('tp_coordinator', 2, NOW());
```

#### TP Office Role:
- ✅ View all reports
- ✅ Download reports
- ✅ Manage student records
- ✅ Generate analytics
- ✅ View audit logs
- ❌ Cannot create assessments

**Setup:**
```sql
INSERT INTO auth_assignment (item_name, user_id, created_at) 
VALUES ('tp_office', 3, NOW());
```

#### Department Chair Role:
- ✅ All permissions (inherits coordinator)
- ✅ Oversight of all assessments
- ✅ Can manage all users
- ✅ Can approve special cases

**Setup:**
```sql
INSERT INTO auth_assignment (item_name, user_id, created_at) 
VALUES ('tp_department_chair', 4, NOW());
```

## Complete Workflow Example

### Scenario: Create and Validate an Assessment

**1. Supervisor creates assessment:**
```
Login as: supervisor user
Action: Create New Assessment
  - Select: Alice Johnson (STU001)
  - Date: 2026-03-03
  - Score RA1 (Professional Records): 8
  - Score RA2 (Lesson Planning): 7
  - ... score all 12 areas
  - Add remarks for each
  - Click: Submit Assessment
```

**2. Notification sent:**
- Supervisor gets confirmation
- Zone Coordinator gets review notification
- TP Office gets notification

**3. Zone Coordinator reviews:**
```
Login as: coordinator user
Navigate: Assessment > Index
Find: Alice Johnson assessment (Status: Submitted)
Click: View
Review the scores and remarks
Click: Validate (or Reject)
```

**4. Reports generated:**
```
Navigate: Reports > Index
Download: Student Report (PDF) - no marks shown
Download: Office Report (PDF) - includes all marks
```

**5. Check audit log:**
```
Verify all actions logged:
- Assessment created
- Assessment submitted
- Assessment reviewed
- Assessment validated
- Reports generated
```

## Troubleshooting

### Issue: "Access Denied" on creating assessment
**Solution:** Assign user to `tp_supervisor` role
```sql
INSERT INTO auth_assignment (item_name, user_id, created_at) 
VALUES ('tp_supervisor', [your_user_id], NOW());
```

### Issue: Tables not created
**Solution:** Run migrations again
```bash
php yii migrate/fresh --migrationPath=@console/migrations --interactive=0
```

### Issue: Upload directory permission denied
**Solution:** Set correct permissions
```bash
# Windows (skip this if using XAMPP)
icacls "backend\web\uploads" /grant Users:F

# Linux/Mac
chmod -R 755 backend/web/uploads
```

### Issue: Cannot access backend
**Solution:** Try these URLs:
```
http://localhost/trials/backend/web
http://localhost:8080/index.php?r=assessment/index
```

### Issue: Assessments not showing in list
**Solution:** Verify students exist in database
```sql
SELECT * FROM tp_student;
SELECT * FROM tp_assessment;
```

## Email Configuration (Optional)

For actual email notifications, update `/common/config/main.php`:

```php
'mailer' => [
    'class' => 'yii\swiftmailer\Mailer',
    'useFileTransport' => false,  // Enable real sending
    'transport' => [
        'class' => 'Swift_SmtpTransport',
        'host' => 'smtp.gmail.com',
        'port' => 587,
        'encryption' => 'tls',
        'username' => 'your-email@gmail.com',
        'password' => 'your-app-password',
    ],
],
```

## Database Backup

**Backup before making changes:**
```bash
mysqldump -u root -p trials > trials_backup.sql
```

**Restore from backup:**
```bash
mysql -u root -p trials < trials_backup.sql
```

## Performance Optimization

### Add search optimization:
```sql
CREATE INDEX idx_assessment_student ON tp_assessment(student_id);
CREATE INDEX idx_assessment_supervisor ON tp_assessment(supervisor_id);
CREATE INDEX idx_notification_user ON tp_notification(user_id);
```

### Enable query caching:
In `/common/config/db.php`:
```php
'db' => [
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 3600,
    'schemaCache' => 'cache',
],
```

## Help & Support

For issues or questions:
1. Check `backend/runtime/logs/app.log` for error messages
2. Check browser console for JavaScript errors (F12)
3. Verify database connectivity with `php yii db`
4. Test RBAC: `php yii rbac/check admin tp_supervisor`

---

**System Ready!** You now have a fully functional Teaching Practice Assessment System running on your Yii2 application.
