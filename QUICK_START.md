# Quick Start - SMS Campaign System

## 🚀 Quick Setup (5 Steps)

### 1. Update .env
```env
QUEUE_CONNECTION=database
SMS_DRIVER=frogsms
FROGSMS_USERNAME=your_username
FROGSMS_PASSWORD=your_password
FROGSMS_SENDER_ID=your_sender_id
```

### 2. Run Migrations
```bash
php artisan migrate
```

### 3. Start Queue Worker
```bash
php artisan queue:work --queue=sms
```

### 4. Start Scheduler (Development)
```bash
php artisan schedule:work
```

### 5. Test It
- Create a campaign (instant or scheduled)
- Watch logs: `tail -f storage/logs/sms/frogSMS.log`

---

## 📱 User Flow

### Instant Messaging
1. Campaigns → Create New
2. Fill message & select groups
3. Click "Send" (no schedule date)
4. Status: pending → processing → completed

### Scheduled Messaging
1. Campaigns → Create New
2. Fill message & select groups
3. Set future schedule date/time
4. Click "Schedule"
5. Status: pending (until scheduled_at)
6. Auto-sends when time arrives
7. Status: processing → completed

---

## 🔍 Quick Checks

**Check if scheduler is running:**
```bash
php artisan schedule:list
```

**Check if queue worker is running:**
```bash
ps aux | grep queue:work
```

**Manually process scheduled campaigns:**
```bash
php artisan campaigns:process-scheduled
```

**Check failed jobs:**
```bash
php artisan queue:failed
```

**Retry failed jobs:**
```bash
php artisan queue:retry all
```

---

## 📋 Campaign Status Flow

```
INSTANT:    pending → processing → completed/failed
SCHEDULED:  pending → (wait) → processing → completed/failed
```

---

## 📊 Where to Look

- **SMS Logs**: `storage/logs/sms/frogSMS.log`
- **App Logs**: `storage/logs/laravel.log`
- **Dashboard**: `/users/dashboard`
- **Campaigns**: `/campaigns`
- **Messages**: `/sms-messages`

---

## ⚠️ Common Issues

| Problem | Solution |
|---------|----------|
| Messages not sending | Start queue worker |
| Scheduled not processing | Start scheduler |
| API errors | Check FrogSMS credentials |
| Queue stuck | Restart queue worker |

---

## 🎯 Testing Checklist

- [ ] Create instant campaign
- [ ] Verify messages queued
- [ ] Check logs for sending
- [ ] Create scheduled campaign (5 min future)
- [ ] Wait & verify auto-processing
- [ ] Check campaign status updates
- [ ] Review SMS logs

---

**Full Guide**: See `SMS_SETUP_GUIDE.md`
