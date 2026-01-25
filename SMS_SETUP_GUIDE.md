# SMS Campaign System - Setup & Testing Guide

## ✅ Completed Implementation

### 1. Scheduled Campaign Processing System
- **Command**: `campaigns:process-scheduled`
- **Schedule**: Runs every minute via Laravel scheduler
- **Function**: Finds pending campaigns with `scheduled_at <= now()` and dispatches them

### 2. FrogSMS API Integration
- **Service**: Updated `SmsService.php` to use FrogSMS API
- **Configuration**: `config/services.php` - `frogsms` section
- **Logging**: All SMS activity logs to `storage/logs/sms/frogSMS.log`

### 3. Background Job Processing
- **ProcessSmsCampaignJob**: Dispatches individual SMS messages
- **SendSmsMessageJob**: Sends individual SMS via FrogSMS API
- **Queue**: Jobs run on 'sms' queue for better performance

---

## 🔧 Configuration Setup

### Step 1: Environment Variables
Add these to your `.env` file:

```env
# Queue Configuration (IMPORTANT!)
QUEUE_CONNECTION=database

# SMS Driver Configuration
SMS_DRIVER=frogsms

# FrogSMS API Credentials
FROGSMS_BASE_URL=https://frog.wigal.com.gh/ismsweb/sendmsg
FROGSMS_USERNAME=your_username_here
FROGSMS_PASSWORD=your_password_here
FROGSMS_SENDER_ID=your_sender_id_here
```

### Step 2: Run Migrations
Ensure all migrations are up to date:
```bash
php artisan migrate
```

### Step 3: Start Queue Workers
For background SMS processing, start queue workers:

```bash
# Main queue worker for SMS
php artisan queue:work --queue=sms --tries=3 --timeout=60

# Or run all queues
php artisan queue:work --tries=3 --timeout=60
```

**Production**: Use Supervisor to keep queue workers running.

### Step 4: Start Laravel Scheduler
The scheduler processes scheduled campaigns every minute.

**Development**:
```bash
php artisan schedule:work
```

**Production**: Add to crontab:
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

---

## 📋 Testing Guide

### Test 1: Instant Message Sending

1. **Login** as an approved user
2. Navigate to **Campaigns → Create New Campaign**
3. Fill in:
   - Title: "Test Instant Campaign"
   - Message: "Hello! This is a test message."
   - Select Contact Groups
   - **Leave scheduled_at empty** (instant send)
4. Click **Send Campaign**
5. **Expected Behavior**:
   - Redirected to campaign details page
   - Status: "processing" or "completed"
   - Messages appear in SMS Messages list
   - Check logs: `storage/logs/sms/frogSMS.log`

### Test 2: Scheduled Message Sending

1. **Login** as an approved user
2. Navigate to **Campaigns → Create New Campaign**
3. Fill in:
   - Title: "Test Scheduled Campaign"
   - Message: "This is a scheduled test."
   - Select Contact Groups
   - **Set scheduled_at**: 5 minutes from now
4. Click **Schedule Campaign**
5. **Expected Behavior**:
   - Campaign status: "pending"
   - Campaign shows scheduled time
   - After scheduled time + 1 minute:
     - Status changes to "processing" → "completed"
     - Messages are sent
     - Check logs for dispatch confirmation

### Test 3: Manual Command Test

Test the scheduled campaign processor manually:

```bash
php artisan campaigns:process-scheduled
```

**Expected Output**:
```
Checking for scheduled campaigns...
Found X scheduled campaign(s) to process.
Processing campaign ID: 123 - Test Campaign
✓ Campaign 123 dispatched successfully.
Scheduled campaigns processing completed.
```

### Test 4: Check Logs

Monitor SMS activity:

```bash
# Watch SMS logs in real-time
tail -f storage/logs/sms/frogSMS.log

# Watch Laravel logs
tail -f storage/logs/laravel.log
```

---

## 🔍 Monitoring & Troubleshooting

### Check Queue Jobs
```bash
# Check failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all
```

### Verify Scheduled Tasks
```bash
# List all scheduled tasks
php artisan schedule:list
```

### Check Campaign Status
- **Dashboard**: Shows total campaigns, messages sent
- **Campaigns Page**: Lists all campaigns with status
- **Campaign Details**: Shows individual message statuses

### Common Issues

#### 1. Messages Not Sending
- ✅ Check queue worker is running: `ps aux | grep queue:work`
- ✅ Verify FrogSMS credentials in `.env`
- ✅ Check `storage/logs/sms/frogSMS.log` for API errors
- ✅ Ensure `QUEUE_CONNECTION=database` in `.env`

#### 2. Scheduled Campaigns Not Processing
- ✅ Verify scheduler is running: `php artisan schedule:work`
- ✅ Check campaign has `scheduled_at <= now()`
- ✅ Check campaign status is 'pending'
- ✅ Run manual test: `php artisan campaigns:process-scheduled`

#### 3. Queue Jobs Failing
```bash
# Check failed jobs table
php artisan queue:failed

# View specific failure details
php artisan queue:failed --id=1

# Retry all failed jobs
php artisan queue:retry all
```

---

## 📊 Database Queue Setup (if not already done)

If using database queues (recommended):

```bash
# Create jobs table
php artisan queue:table
php artisan migrate
```

---

## 🚀 Production Deployment

### 1. Supervisor Configuration
Create `/etc/supervisor/conf.d/laravel-worker.conf`:

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/your/project/artisan queue:work --queue=sms --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=3
redirect_stderr=true
stdout_logfile=/path/to/your/project/storage/logs/worker.log
stopwaitsecs=3600
```

Reload Supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
```

### 2. Cron Job for Scheduler
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

### 3. Optimize Application
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 📈 Performance Tips

1. **Batch Size**: Adjust chunk size in `ProcessSmsCampaignJob` (currently 100)
2. **Queue Workers**: Run multiple workers for high volume (use Supervisor numprocs)
3. **Timeouts**: Increase timeout for large campaigns
4. **Monitoring**: Set up Laravel Horizon for queue monitoring (optional)

---

## ✅ Next Steps After Testing

Once testing is complete, consider:

1. ✅ Set up email notifications for campaign completion
2. ✅ Add SMS delivery webhooks (if FrogSMS supports them)
3. ✅ Implement SMS credit/balance checking
4. ✅ Add campaign analytics dashboard
5. ✅ Set up monitoring alerts for failed jobs
6. ✅ Implement rate limiting for SMS sending

---

**Last Updated**: December 21, 2025
