# AWS Deployment Guide - Route Gh

This comprehensive guide walks you through deploying Route Gh on AWS infrastructure using best practices and managed services.

---

## 📋 Table of Contents

- [Architecture Overview](#-architecture-overview)
- [Prerequisites](#-prerequisites)
- [AWS Services Used](#-aws-services-used)
- [Step 1: RDS Database Setup](#step-1-rds-database-setup)
- [Step 2: Secrets Manager Configuration](#step-2-secrets-manager-configuration)
- [Step 3: SQS Queue Setup](#step-3-sqs-queue-setup)
- [Step 4: ECR Container Registry](#step-4-ecr-container-registry)
- [Step 5: ECS Fargate Deployment](#step-5-ecs-fargate-deployment)
- [Step 6: Application Load Balancer](#step-6-application-load-balancer)
- [Step 7: CloudWatch Monitoring](#step-7-cloudwatch-monitoring)
- [Step 8: Lambda Functions](#step-8-lambda-functions)
- [Step 9: Route 53 & SSL](#step-9-route-53--ssl)
- [Step 10: CI/CD Pipeline](#step-10-cicd-pipeline)
- [Troubleshooting](#-troubleshooting)
- [Cost Optimization](#-cost-optimization)

---

## 🏗️ Architecture Overview

```
┌─────────────────┐
│   Route 53      │ (DNS)
└────────┬────────┘
         │
┌────────▼────────┐
│  CloudFront     │ (CDN - Optional)
└────────┬────────┘
         │
┌────────▼────────────────┐
│ Application Load        │
│ Balancer (ALB)          │
└────────┬────────────────┘
         │
         ├──────────────────────┬──────────────────────┐
         │                      │                      │
┌────────▼────────┐   ┌────────▼────────┐   ┌────────▼────────┐
│  ECS Fargate    │   │  ECS Fargate    │   │  ECS Fargate    │
│  (Web App)      │   │  (Queue Worker) │   │  (Scheduler)    │
└────────┬────────┘   └────────┬────────┘   └────────┬────────┘
         │                      │                      │
         └──────────────────────┼──────────────────────┘
                                │
         ┌──────────────────────┼──────────────────────┐
         │                      │                      │
┌────────▼────────┐   ┌────────▼────────┐   ┌────────▼────────┐
│  RDS MySQL      │   │  AWS SQS        │   │ Secrets Manager │
│  (Database)     │   │  (Job Queue)    │   │ (Credentials)   │
└─────────────────┘   └─────────────────┘   └─────────────────┘
                                │
                      ┌─────────▼─────────┐
                      │  CloudWatch Logs  │
                      │  & Metrics        │
                      └───────────────────┘
```

---

## ✅ Prerequisites

- AWS Account with appropriate permissions
- AWS CLI installed and configured
- Docker installed locally
- Domain name (for SSL and Route 53)
- Basic knowledge of AWS services

### Install AWS CLI
```bash
# macOS
brew install awscli

# Windows
choco install awscli

# Linux
curl "https://awscli.amazonaws.com/awscli-exe-linux-x86_64.zip" -o "awscliv2.zip"
unzip awscliv2.zip
sudo ./aws/install
```

### Configure AWS CLI
```bash
aws configure
# AWS Access Key ID: YOUR_ACCESS_KEY
# AWS Secret Access Key: YOUR_SECRET_KEY
# Default region name: us-east-1
# Default output format: json
```

---

## 🔧 AWS Services Used

| Service | Purpose | Estimated Monthly Cost |
|---------|---------|----------------------|
| **RDS MySQL** | Database (db.t3.micro) | $15-20 |
| **ECS Fargate** | Container hosting | $30-50 |
| **ALB** | Load balancing | $20-25 |
| **SQS** | Message queue | $0-5 |
| **Secrets Manager** | Secure credentials | $1-2 |
| **CloudWatch** | Logging & monitoring | $5-10 |
| **ECR** | Container registry | $1-5 |
| **S3** | File storage | $1-5 |
| **Route 53** | DNS management | $0.50/month + $0.40/million queries |
| **Certificate Manager** | SSL certificates | Free |
| **Total** | | **~$75-125/month** |

> **Note**: Costs vary based on traffic, storage, and usage.

---

## Step 1: RDS Database Setup

### 1.1 Create RDS Subnet Group

```bash
aws rds create-db-subnet-group \
  --db-subnet-group-name routegh-db-subnet-group \
  --db-subnet-group-description "Subnet group for Route Gh database" \
  --subnet-ids subnet-xxxxxx subnet-yyyyyy \
  --region us-east-1
```

### 1.2 Create Security Group for RDS

```bash
# Create security group
aws ec2 create-security-group \
  --group-name routegh-rds-sg \
  --description "Security group for Route Gh RDS" \
  --vpc-id vpc-xxxxxx \
  --region us-east-1

# Allow MySQL access from ECS tasks
aws ec2 authorize-security-group-ingress \
  --group-id sg-xxxxxx \
  --protocol tcp \
  --port 3306 \
  --source-group sg-yyyyyy \
  --region us-east-1
```

### 1.3 Create RDS MySQL Instance

```bash
aws rds create-db-instance \
  --db-instance-identifier routegh-production \
  --db-instance-class db.t3.micro \
  --engine mysql \
  --engine-version 8.0.35 \
  --master-username admin \
  --master-user-password "YourSecurePassword123!" \
  --allocated-storage 20 \
  --storage-type gp3 \
  --db-subnet-group-name routegh-db-subnet-group \
  --vpc-security-group-ids sg-xxxxxx \
  --backup-retention-period 7 \
  --preferred-backup-window "03:00-04:00" \
  --preferred-maintenance-window "mon:04:00-mon:05:00" \
  --multi-az \
  --publicly-accessible false \
  --storage-encrypted \
  --enable-cloudwatch-logs-exports '["error","general","slowquery"]' \
  --region us-east-1
```

### 1.4 Wait for Database Creation

```bash
aws rds wait db-instance-available \
  --db-instance-identifier routegh-production \
  --region us-east-1
```

### 1.5 Get RDS Endpoint

```bash
aws rds describe-db-instances \
  --db-instance-identifier routegh-production \
  --query 'DBInstances[0].Endpoint.Address' \
  --output text \
  --region us-east-1
```

---

## Step 2: Secrets Manager Configuration

### 2.1 Create Database Credentials Secret

```bash
aws secretsmanager create-secret \
  --name routegh/production/database \
  --description "Route Gh database credentials" \
  --secret-string '{
    "host": "routegh-production.xxxxxxxxx.us-east-1.rds.amazonaws.com",
    "port": "3306",
    "database": "routegh",
    "username": "admin",
    "password": "YourSecurePassword123!"
  }' \
  --region us-east-1
```

### 2.2 Create Application Secrets

```bash
aws secretsmanager create-secret \
  --name routegh/production/app \
  --description "Route Gh application secrets" \
  --secret-string '{
    "APP_KEY": "base64:GENERATED_KEY_HERE",
    "APP_ENV": "production",
    "APP_DEBUG": "false",
    "APP_URL": "https://routegh.com"
  }' \
  --region us-east-1
```

### 2.3 Create SMS Provider Credentials

```bash
aws secretsmanager create-secret \
  --name routegh/production/sms \
  --description "SMS provider credentials" \
  --secret-string '{
    "SMS_DRIVER": "frogsms",
    "FROGSMS_BASE_URL": "https://frog.wigal.com.gh/ismsweb/sendmsg",
    "FROGSMS_USERNAME": "your_username",
    "FROGSMS_PASSWORD": "your_password",
    "FROGSMS_SENDER_ID": "RouteGH",
    "TWILIO_ACCOUNT_SID": "ACxxxxxxxxxxxx",
    "TWILIO_AUTH_TOKEN": "your_auth_token",
    "TWILIO_FROM": "+1234567890"
  }' \
  --region us-east-1
```

### 2.4 Create Mail Configuration Secret

```bash
aws secretsmanager create-secret \
  --name routegh/production/mail \
  --description "Mail configuration" \
  --secret-string '{
    "MAIL_MAILER": "smtp",
    "MAIL_HOST": "smtp.gmail.com",
    "MAIL_PORT": "587",
    "MAIL_USERNAME": "noreply@routegh.com",
    "MAIL_PASSWORD": "your_app_password",
    "MAIL_ENCRYPTION": "tls",
    "MAIL_FROM_ADDRESS": "noreply@routegh.com",
    "MAIL_FROM_NAME": "Route Gh"
  }' \
  --region us-east-1
```

---

## Step 3: SQS Queue Setup

### 3.1 Create Main Queue

```bash
aws sqs create-queue \
  --queue-name routegh-production-queue \
  --attributes '{
    "DelaySeconds": "0",
    "MaximumMessageSize": "262144",
    "MessageRetentionPeriod": "1209600",
    "ReceiveMessageWaitTimeSeconds": "20",
    "VisibilityTimeout": "300"
  }' \
  --region us-east-1
```

### 3.2 Create Dead Letter Queue

```bash
aws sqs create-queue \
  --queue-name routegh-production-dlq \
  --attributes '{
    "MessageRetentionPeriod": "1209600"
  }' \
  --region us-east-1
```

### 3.3 Configure Redrive Policy

```bash
# Get DLQ ARN
DLQ_ARN=$(aws sqs get-queue-attributes \
  --queue-url https://sqs.us-east-1.amazonaws.com/YOUR_ACCOUNT_ID/routegh-production-dlq \
  --attribute-names QueueArn \
  --query 'Attributes.QueueArn' \
  --output text \
  --region us-east-1)

# Set redrive policy on main queue
aws sqs set-queue-attributes \
  --queue-url https://sqs.us-east-1.amazonaws.com/YOUR_ACCOUNT_ID/routegh-production-queue \
  --attributes "{
    \"RedrivePolicy\": \"{\\\"deadLetterTargetArn\\\":\\\"$DLQ_ARN\\\",\\\"maxReceiveCount\\\":\\\"3\\\"}\"
  }" \
  --region us-east-1
```

### 3.4 Update Laravel Queue Configuration

Add to your `.env` or Secrets Manager:
```env
QUEUE_CONNECTION=sqs
SQS_PREFIX=https://sqs.us-east-1.amazonaws.com/YOUR_ACCOUNT_ID
SQS_QUEUE=routegh-production-queue
SQS_REGION=us-east-1
```

Update `config/queue.php`:
```php
'sqs' => [
    'driver' => 'sqs',
    'key' => env('AWS_ACCESS_KEY_ID'),
    'secret' => env('AWS_SECRET_ACCESS_KEY'),
    'prefix' => env('SQS_PREFIX'),
    'queue' => env('SQS_QUEUE'),
    'region' => env('SQS_REGION', 'us-east-1'),
],
```

---

## Step 4: ECR Container Registry

### 4.1 Create ECR Repository

```bash
aws ecr create-repository \
  --repository-name routegh \
  --image-scanning-configuration scanOnPush=true \
  --region us-east-1
```

### 4.2 Authenticate Docker to ECR

```bash
aws ecr get-login-password --region us-east-1 | \
  docker login --username AWS --password-stdin \
  YOUR_ACCOUNT_ID.dkr.ecr.us-east-1.amazonaws.com
```

### 4.3 Create Dockerfile

Create `Dockerfile` in project root:
```dockerfile
# Multi-stage build for smaller image
FROM composer:2 AS composer
FROM php:8.1-fpm-alpine AS base

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    bash \
    mysql-client \
    nodejs \
    npm \
    git \
    zip \
    unzip \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    icu-dev \
    libzip-dev

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install \
    pdo \
    pdo_mysql \
    mysqli \
    gd \
    intl \
    zip \
    opcache \
    bcmath

# Copy Composer from official image
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Install Node dependencies and build assets
RUN npm ci && npm run build && rm -rf node_modules

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Copy Nginx configuration
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf

# Copy Supervisor configuration
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expose port
EXPOSE 80

# Start Supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
```

### 4.4 Create Nginx Configuration

Create `docker/nginx/default.conf`:
```nginx
server {
    listen 80;
    server_name _;
    root /var/www/html/public;
    index index.php index.html;

    client_max_body_size 20M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

### 4.5 Create Supervisor Configuration

Create `docker/supervisor/supervisord.conf`:
```ini
[supervisord]
nodaemon=true
user=root
logfile=/var/log/supervisor/supervisord.log
pidfile=/var/run/supervisord.pid

[program:php-fpm]
command=php-fpm
autostart=true
autorestart=true
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0

[program:nginx]
command=nginx -g 'daemon off;'
autostart=true
autorestart=true
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0
```

### 4.6 Build and Push Docker Image

```bash
# Build image
docker build -t routegh:latest .

# Tag image
docker tag routegh:latest \
  YOUR_ACCOUNT_ID.dkr.ecr.us-east-1.amazonaws.com/routegh:latest

# Push to ECR
docker push YOUR_ACCOUNT_ID.dkr.ecr.us-east-1.amazonaws.com/routegh:latest
```

---

## Step 5: ECS Fargate Deployment

### 5.1 Create ECS Cluster

```bash
aws ecs create-cluster \
  --cluster-name routegh-production \
  --capacity-providers FARGATE FARGATE_SPOT \
  --region us-east-1
```

### 5.2 Create IAM Roles

#### ECS Task Execution Role
```bash
# Create trust policy
cat > ecs-task-execution-role-trust-policy.json <<EOF
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Effect": "Allow",
      "Principal": {
        "Service": "ecs-tasks.amazonaws.com"
      },
      "Action": "sts:AssumeRole"
    }
  ]
}
EOF

# Create role
aws iam create-role \
  --role-name ecsTaskExecutionRole \
  --assume-role-policy-document file://ecs-task-execution-role-trust-policy.json

# Attach policies
aws iam attach-role-policy \
  --role-name ecsTaskExecutionRole \
  --policy-arn arn:aws:iam::aws:policy/service-role/AmazonECSTaskExecutionRolePolicy

aws iam attach-role-policy \
  --role-name ecsTaskExecutionRole \
  --policy-arn arn:aws:iam::aws:policy/SecretsManagerReadWrite
```

#### ECS Task Role (for app to access AWS services)
```bash
cat > ecs-task-role-trust-policy.json <<EOF
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Effect": "Allow",
      "Principal": {
        "Service": "ecs-tasks.amazonaws.com"
      },
      "Action": "sts:AssumeRole"
    }
  ]
}
EOF

aws iam create-role \
  --role-name routeghTaskRole \
  --assume-role-policy-document file://ecs-task-role-trust-policy.json

# Create inline policy for SQS access
cat > sqs-policy.json <<EOF
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Effect": "Allow",
      "Action": [
        "sqs:SendMessage",
        "sqs:ReceiveMessage",
        "sqs:DeleteMessage",
        "sqs:GetQueueAttributes"
      ],
      "Resource": "arn:aws:sqs:us-east-1:YOUR_ACCOUNT_ID:routegh-*"
    }
  ]
}
EOF

aws iam put-role-policy \
  --role-name routeghTaskRole \
  --policy-name SQSAccess \
  --policy-document file://sqs-policy.json
```

### 5.3 Create Task Definition

Create `task-definition.json`:
```json
{
  "family": "routegh-web",
  "networkMode": "awsvpc",
  "requiresCompatibilities": ["FARGATE"],
  "cpu": "512",
  "memory": "1024",
  "executionRoleArn": "arn:aws:iam::YOUR_ACCOUNT_ID:role/ecsTaskExecutionRole",
  "taskRoleArn": "arn:aws:iam::YOUR_ACCOUNT_ID:role/routeghTaskRole",
  "containerDefinitions": [
    {
      "name": "routegh-app",
      "image": "YOUR_ACCOUNT_ID.dkr.ecr.us-east-1.amazonaws.com/routegh:latest",
      "essential": true,
      "portMappings": [
        {
          "containerPort": 80,
          "protocol": "tcp"
        }
      ],
      "environment": [
        {
          "name": "APP_NAME",
          "value": "Route Gh"
        },
        {
          "name": "LOG_CHANNEL",
          "value": "stderr"
        }
      ],
      "secrets": [
        {
          "name": "APP_KEY",
          "valueFrom": "arn:aws:secretsmanager:us-east-1:YOUR_ACCOUNT_ID:secret:routegh/production/app:APP_KEY::"
        },
        {
          "name": "DB_HOST",
          "valueFrom": "arn:aws:secretsmanager:us-east-1:YOUR_ACCOUNT_ID:secret:routegh/production/database:host::"
        },
        {
          "name": "DB_DATABASE",
          "valueFrom": "arn:aws:secretsmanager:us-east-1:YOUR_ACCOUNT_ID:secret:routegh/production/database:database::"
        },
        {
          "name": "DB_USERNAME",
          "valueFrom": "arn:aws:secretsmanager:us-east-1:YOUR_ACCOUNT_ID:secret:routegh/production/database:username::"
        },
        {
          "name": "DB_PASSWORD",
          "valueFrom": "arn:aws:secretsmanager:us-east-1:YOUR_ACCOUNT_ID:secret:routegh/production/database:password::"
        },
        {
          "name": "FROGSMS_USERNAME",
          "valueFrom": "arn:aws:secretsmanager:us-east-1:YOUR_ACCOUNT_ID:secret:routegh/production/sms:FROGSMS_USERNAME::"
        },
        {
          "name": "FROGSMS_PASSWORD",
          "valueFrom": "arn:aws:secretsmanager:us-east-1:YOUR_ACCOUNT_ID:secret:routegh/production/sms:FROGSMS_PASSWORD::"
        }
      ],
      "logConfiguration": {
        "logDriver": "awslogs",
        "options": {
          "awslogs-group": "/ecs/routegh-production",
          "awslogs-region": "us-east-1",
          "awslogs-stream-prefix": "ecs"
        }
      },
      "healthCheck": {
        "command": ["CMD-SHELL", "curl -f http://localhost/api/health || exit 1"],
        "interval": 30,
        "timeout": 5,
        "retries": 3,
        "startPeriod": 60
      }
    }
  ]
}
```

Register task definition:
```bash
aws ecs register-task-definition \
  --cli-input-json file://task-definition.json \
  --region us-east-1
```

### 5.4 Create Queue Worker Task Definition

Create `task-definition-worker.json`:
```json
{
  "family": "routegh-worker",
  "networkMode": "awsvpc",
  "requiresCompatibilities": ["FARGATE"],
  "cpu": "256",
  "memory": "512",
  "executionRoleArn": "arn:aws:iam::YOUR_ACCOUNT_ID:role/ecsTaskExecutionRole",
  "taskRoleArn": "arn:aws:iam::YOUR_ACCOUNT_ID:role/routeghTaskRole",
  "containerDefinitions": [
    {
      "name": "routegh-queue-worker",
      "image": "YOUR_ACCOUNT_ID.dkr.ecr.us-east-1.amazonaws.com/routegh:latest",
      "essential": true,
      "command": ["php", "/var/www/html/artisan", "queue:work", "--tries=3", "--timeout=60"],
      "environment": [
        {
          "name": "QUEUE_CONNECTION",
          "value": "sqs"
        }
      ],
      "secrets": [
        {
          "name": "DB_HOST",
          "valueFrom": "arn:aws:secretsmanager:us-east-1:YOUR_ACCOUNT_ID:secret:routegh/production/database:host::"
        }
      ],
      "logConfiguration": {
        "logDriver": "awslogs",
        "options": {
          "awslogs-group": "/ecs/routegh-worker",
          "awslogs-region": "us-east-1",
          "awslogs-stream-prefix": "worker"
        }
      }
    }
  ]
}
```

Register worker task:
```bash
aws ecs register-task-definition \
  --cli-input-json file://task-definition-worker.json \
  --region us-east-1
```

### 5.5 Create Security Groups

```bash
# ALB Security Group
aws ec2 create-security-group \
  --group-name routegh-alb-sg \
  --description "Security group for Route Gh ALB" \
  --vpc-id vpc-xxxxxx \
  --region us-east-1

aws ec2 authorize-security-group-ingress \
  --group-id sg-alb-xxxx \
  --protocol tcp \
  --port 80 \
  --cidr 0.0.0.0/0 \
  --region us-east-1

aws ec2 authorize-security-group-ingress \
  --group-id sg-alb-xxxx \
  --protocol tcp \
  --port 443 \
  --cidr 0.0.0.0/0 \
  --region us-east-1

# ECS Tasks Security Group
aws ec2 create-security-group \
  --group-name routegh-ecs-sg \
  --description "Security group for Route Gh ECS tasks" \
  --vpc-id vpc-xxxxxx \
  --region us-east-1

aws ec2 authorize-security-group-ingress \
  --group-id sg-ecs-xxxx \
  --protocol tcp \
  --port 80 \
  --source-group sg-alb-xxxx \
  --region us-east-1
```

### 5.6 Create ECS Service

```bash
aws ecs create-service \
  --cluster routegh-production \
  --service-name routegh-web-service \
  --task-definition routegh-web:1 \
  --desired-count 2 \
  --launch-type FARGATE \
  --platform-version LATEST \
  --network-configuration "awsvpcConfiguration={
    subnets=[subnet-xxxxxx,subnet-yyyyyy],
    securityGroups=[sg-ecs-xxxx],
    assignPublicIp=ENABLED
  }" \
  --load-balancers "targetGroupArn=arn:aws:elasticloadbalancing:us-east-1:YOUR_ACCOUNT_ID:targetgroup/routegh-tg/xxxxx,containerName=routegh-app,containerPort=80" \
  --health-check-grace-period-seconds 60 \
  --region us-east-1
```

---

## Step 6: Application Load Balancer

### 6.1 Create ALB

```bash
aws elbv2 create-load-balancer \
  --name routegh-alb \
  --subnets subnet-xxxxxx subnet-yyyyyy \
  --security-groups sg-alb-xxxx \
  --scheme internet-facing \
  --type application \
  --ip-address-type ipv4 \
  --region us-east-1
```

### 6.2 Create Target Group

```bash
aws elbv2 create-target-group \
  --name routegh-tg \
  --protocol HTTP \
  --port 80 \
  --vpc-id vpc-xxxxxx \
  --target-type ip \
  --health-check-enabled \
  --health-check-protocol HTTP \
  --health-check-path /api/health \
  --health-check-interval-seconds 30 \
  --health-check-timeout-seconds 5 \
  --healthy-threshold-count 2 \
  --unhealthy-threshold-count 3 \
  --matcher HttpCode=200 \
  --region us-east-1
```

### 6.3 Create ALB Listener (HTTP)

```bash
aws elbv2 create-listener \
  --load-balancer-arn arn:aws:elasticloadbalancing:us-east-1:YOUR_ACCOUNT_ID:loadbalancer/app/routegh-alb/xxxxx \
  --protocol HTTP \
  --port 80 \
  --default-actions Type=forward,TargetGroupArn=arn:aws:elasticloadbalancing:us-east-1:YOUR_ACCOUNT_ID:targetgroup/routegh-tg/xxxxx \
  --region us-east-1
```

### 6.4 Create HTTPS Listener (after SSL certificate)

```bash
aws elbv2 create-listener \
  --load-balancer-arn arn:aws:elasticloadbalancing:us-east-1:YOUR_ACCOUNT_ID:loadbalancer/app/routegh-alb/xxxxx \
  --protocol HTTPS \
  --port 443 \
  --certificates CertificateArn=arn:aws:acm:us-east-1:YOUR_ACCOUNT_ID:certificate/xxxxx \
  --default-actions Type=forward,TargetGroupArn=arn:aws:elasticloadbalancing:us-east-1:YOUR_ACCOUNT_ID:targetgroup/routegh-tg/xxxxx \
  --region us-east-1
```

---

## Step 7: CloudWatch Monitoring

### 7.1 Create Log Groups

```bash
# Web app logs
aws logs create-log-group \
  --log-group-name /ecs/routegh-production \
  --region us-east-1

# Worker logs
aws logs create-log-group \
  --log-group-name /ecs/routegh-worker \
  --region us-east-1

# Set retention (30 days)
aws logs put-retention-policy \
  --log-group-name /ecs/routegh-production \
  --retention-in-days 30 \
  --region us-east-1

aws logs put-retention-policy \
  --log-group-name /ecs/routegh-worker \
  --retention-in-days 30 \
  --region us-east-1
```

### 7.2 Create CloudWatch Alarms

#### High CPU Alarm
```bash
aws cloudwatch put-metric-alarm \
  --alarm-name routegh-high-cpu \
  --alarm-description "Alert when CPU exceeds 80%" \
  --metric-name CPUUtilization \
  --namespace AWS/ECS \
  --statistic Average \
  --period 300 \
  --evaluation-periods 2 \
  --threshold 80 \
  --comparison-operator GreaterThanThreshold \
  --dimensions Name=ClusterName,Value=routegh-production Name=ServiceName,Value=routegh-web-service \
  --region us-east-1
```

#### High Memory Alarm
```bash
aws cloudwatch put-metric-alarm \
  --alarm-name routegh-high-memory \
  --alarm-description "Alert when memory exceeds 80%" \
  --metric-name MemoryUtilization \
  --namespace AWS/ECS \
  --statistic Average \
  --period 300 \
  --evaluation-periods 2 \
  --threshold 80 \
  --comparison-operator GreaterThanThreshold \
  --dimensions Name=ClusterName,Value=routegh-production Name=ServiceName,Value=routegh-web-service \
  --region us-east-1
```

#### SQS Queue Depth Alarm
```bash
aws cloudwatch put-metric-alarm \
  --alarm-name routegh-high-queue-depth \
  --alarm-description "Alert when queue has too many messages" \
  --metric-name ApproximateNumberOfMessagesVisible \
  --namespace AWS/SQS \
  --statistic Average \
  --period 300 \
  --evaluation-periods 2 \
  --threshold 1000 \
  --comparison-operator GreaterThanThreshold \
  --dimensions Name=QueueName,Value=routegh-production-queue \
  --region us-east-1
```

### 7.3 Create Dashboard

```bash
aws cloudwatch put-dashboard \
  --dashboard-name RouteGh-Production \
  --dashboard-body file://dashboard-definition.json \
  --region us-east-1
```

Create `dashboard-definition.json`:
```json
{
  "widgets": [
    {
      "type": "metric",
      "properties": {
        "metrics": [
          ["AWS/ECS", "CPUUtilization", {"stat": "Average"}],
          [".", "MemoryUtilization", {"stat": "Average"}]
        ],
        "period": 300,
        "stat": "Average",
        "region": "us-east-1",
        "title": "ECS Resource Utilization"
      }
    },
    {
      "type": "metric",
      "properties": {
        "metrics": [
          ["AWS/SQS", "ApproximateNumberOfMessagesVisible", {"stat": "Sum"}],
          [".", "NumberOfMessagesSent", {"stat": "Sum"}],
          [".", "NumberOfMessagesDeleted", {"stat": "Sum"}]
        ],
        "period": 300,
        "stat": "Sum",
        "region": "us-east-1",
        "title": "SQS Queue Metrics"
      }
    }
  ]
}
```

---

## Step 8: Lambda Functions

### 8.1 Database Backup Lambda

Create `lambda/db-backup/index.js`:
```javascript
const AWS = require('aws-sdk');
const rds = new AWS.RDS();

exports.handler = async (event) => {
    const params = {
        DBInstanceIdentifier: 'routegh-production',
        DBSnapshotIdentifier: `routegh-snapshot-${Date.now()}`
    };
    
    try {
        const result = await rds.createDBSnapshot(params).promise();
        console.log('Snapshot created:', result);
        return { statusCode: 200, body: 'Backup successful' };
    } catch (error) {
        console.error('Backup failed:', error);
        throw error;
    }
};
```

Deploy Lambda:
```bash
# Create deployment package
cd lambda/db-backup
npm install aws-sdk
zip -r function.zip .

# Create Lambda function
aws lambda create-function \
  --function-name routegh-db-backup \
  --runtime nodejs18.x \
  --role arn:aws:iam::YOUR_ACCOUNT_ID:role/lambda-rds-backup-role \
  --handler index.handler \
  --zip-file fileb://function.zip \
  --timeout 60 \
  --region us-east-1
```

### 8.2 Schedule Lambda with EventBridge

```bash
# Create rule to run daily at 2 AM
aws events put-rule \
  --name routegh-daily-backup \
  --schedule-expression "cron(0 2 * * ? *)" \
  --region us-east-1

# Add Lambda as target
aws events put-targets \
  --rule routegh-daily-backup \
  --targets "Id"="1","Arn"="arn:aws:lambda:us-east-1:YOUR_ACCOUNT_ID:function:routegh-db-backup" \
  --region us-east-1

# Grant EventBridge permission to invoke Lambda
aws lambda add-permission \
  --function-name routegh-db-backup \
  --statement-id AllowEventBridgeInvoke \
  --action lambda:InvokeFunction \
  --principal events.amazonaws.com \
  --source-arn arn:aws:events:us-east-1:YOUR_ACCOUNT_ID:rule/routegh-daily-backup \
  --region us-east-1
```

---

## Step 9: Route 53 & SSL

### 9.1 Create Hosted Zone

```bash
aws route53 create-hosted-zone \
  --name routegh.com \
  --caller-reference $(date +%s) \
  --hosted-zone-config Comment="Route Gh production domain"
```

### 9.2 Request SSL Certificate

```bash
aws acm request-certificate \
  --domain-name routegh.com \
  --subject-alternative-names www.routegh.com \
  --validation-method DNS \
  --region us-east-1
```

### 9.3 Validate Certificate

```bash
# Get validation CNAME records
aws acm describe-certificate \
  --certificate-arn arn:aws:acm:us-east-1:YOUR_ACCOUNT_ID:certificate/xxxxx \
  --region us-east-1
```

### 9.4 Create DNS Records

```bash
# Create A record for ALB
cat > change-batch.json <<EOF
{
  "Changes": [
    {
      "Action": "CREATE",
      "ResourceRecordSet": {
        "Name": "routegh.com",
        "Type": "A",
        "AliasTarget": {
          "HostedZoneId": "Z35SXDOTRQ7X7K",
          "DNSName": "routegh-alb-xxxxx.us-east-1.elb.amazonaws.com",
          "EvaluateTargetHealth": true
        }
      }
    }
  ]
}
EOF

aws route53 change-resource-record-sets \
  --hosted-zone-id Z1234567890ABC \
  --change-batch file://change-batch.json
```

---

## Step 10: CI/CD Pipeline

### 10.1 Create CodePipeline

```bash
aws codepipeline create-pipeline \
  --cli-input-json file://pipeline-definition.json \
  --region us-east-1
```

### 10.2 GitHub Actions Deployment

Create `.github/workflows/deploy.yml`:
```yaml
name: Deploy to AWS ECS

on:
  push:
    branches: [main]

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      
      - name: Configure AWS credentials
        uses: aws-actions/configure-aws-credentials@v2
        with:
          aws-access-key-id: ${{ secrets.AWS_ACCESS_KEY_ID }}
          aws-secret-access-key: ${{ secrets.AWS_SECRET_ACCESS_KEY }}
          aws-region: us-east-1
      
      - name: Login to Amazon ECR
        id: login-ecr
        uses: aws-actions/amazon-ecr-login@v1
      
      - name: Build, tag, and push image to Amazon ECR
        env:
          ECR_REGISTRY: ${{ steps.login-ecr.outputs.registry }}
          ECR_REPOSITORY: routegh
          IMAGE_TAG: ${{ github.sha }}
        run: |
          docker build -t $ECR_REGISTRY/$ECR_REPOSITORY:$IMAGE_TAG .
          docker push $ECR_REGISTRY/$ECR_REPOSITORY:$IMAGE_TAG
          docker tag $ECR_REGISTRY/$ECR_REPOSITORY:$IMAGE_TAG $ECR_REGISTRY/$ECR_REPOSITORY:latest
          docker push $ECR_REGISTRY/$ECR_REPOSITORY:latest
      
      - name: Update ECS service
        run: |
          aws ecs update-service \
            --cluster routegh-production \
            --service routegh-web-service \
            --force-new-deployment
```

---

## 🔍 Troubleshooting

### Common Issues

#### 1. ECS Tasks Not Starting
```bash
# Check task status
aws ecs describe-tasks \
  --cluster routegh-production \
  --tasks TASK_ARN \
  --region us-east-1

# Check logs
aws logs tail /ecs/routegh-production --follow --region us-east-1
```

#### 2. Database Connection Issues
```bash
# Test RDS connectivity
aws rds describe-db-instances \
  --db-instance-identifier routegh-production \
  --query 'DBInstances[0].Endpoint' \
  --region us-east-1

# Check security groups
aws ec2 describe-security-groups \
  --group-ids sg-xxxxxx \
  --region us-east-1
```

#### 3. Queue Worker Not Processing Jobs
```bash
# Check SQS queue
aws sqs get-queue-attributes \
  --queue-url https://sqs.us-east-1.amazonaws.com/YOUR_ACCOUNT_ID/routegh-production-queue \
  --attribute-names All \
  --region us-east-1

# View worker logs
aws logs tail /ecs/routegh-worker --follow --region us-east-1
```

#### 4. High Memory Usage
```bash
# Increase task memory
aws ecs register-task-definition \
  --cli-input-json file://updated-task-definition.json

# Update service
aws ecs update-service \
  --cluster routegh-production \
  --service routegh-web-service \
  --task-definition routegh-web:2
```

---

## 💰 Cost Optimization

### 1. Use Fargate Spot for Workers
```bash
aws ecs create-service \
  --capacity-provider-strategy capacityProvider=FARGATE_SPOT,weight=70 capacityProvider=FARGATE,weight=30
```

### 2. Enable RDS Auto Scaling
```bash
aws rds modify-db-instance \
  --db-instance-identifier routegh-production \
  --max-allocated-storage 100 \
  --region us-east-1
```

### 3. Use S3 Lifecycle Policies
```bash
aws s3api put-bucket-lifecycle-configuration \
  --bucket routegh-uploads \
  --lifecycle-configuration file://lifecycle.json
```

### 4. Enable CloudWatch Logs Retention
Already configured in Step 7.1 (30 days retention)

### 5. Use Reserved Instances (for predictable workloads)
Purchase RDS Reserved Instances through AWS Console for 30-60% savings.

---

## 📊 Monitoring Best Practices

1. **Set up SNS alerts** for CloudWatch alarms
2. **Monitor SQS dead letter queue** regularly
3. **Track RDS performance metrics** (connections, IOPS)
4. **Enable AWS X-Ray** for request tracing
5. **Set up AWS Cost Explorer alerts** for budget management

---

## 🎯 Next Steps

1. Set up automated backups with retention policies
2. Implement blue/green deployments
3. Configure auto-scaling based on metrics
4. Set up multi-region failover (DR)
5. Implement caching with ElastiCache

---

## 📞 Support

For AWS-specific issues:
- AWS Support Center
- AWS Documentation: https://docs.aws.amazon.com

For Route Gh application issues:
- See main [README.md](./README.md)
- GitHub Issues

---

<p align="center">Successfully deployed to AWS! 🚀</p>
