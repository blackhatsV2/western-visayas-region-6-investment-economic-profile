---
description: How to deploy the Laravel project to Northflank for sharing with others
---

# Deploy to Northflank - Complete Step-by-Step Guide

## Prerequisites
- A GitHub account with the project repo pushed
- A [Northflank account](https://app.northflank.com/signup) (free tier works)

---

## Part 1: Prepare Your Code (Local Machine)

### 1. Generate your APP_KEY
// turbo
```bash
cd /home/jayrold-lenovo-thinkpadx240/Documents/Projects/new-app && php artisan key:generate --show
```
**Copy the output** (looks like `base64:xxxxxxx...`). You'll need this later.

### 2. Commit & push all files to GitHub
```bash
cd /home/jayrold-lenovo-thinkpadx240/Documents/Projects/new-app
git add -A
git commit -m "Add production Dockerfile for Northflank deployment"
git push origin main
```

---

## Part 2: Set Up Northflank (In Browser)

### Step 1: Create an Account
1. Go to **https://app.northflank.com/signup**
2. Sign up (you can use your GitHub account for easy linking)
3. After signup, you'll land on the **Dashboard**

### Step 2: Create a Project
1. Click the **"Create Project"** button (top-right or center of dashboard)
2. Name it: `western-visayas-profile`
3. Click **"Create Project"**
4. You're now inside your project

### Step 3: Add a MySQL Database
1. In the left sidebar, click **"Addons"**
2. Click **"Create Addon"**
3. Select **"MySQL"**
4. Configure:
   - **Name**: `mysql-db`
   - **Version**: `8.0`
   - **Plan**: Select the **free/cheapest** tier
5. Click **"Create"**
6. Wait for it to become **"Running"** (takes ~1-2 minutes)
7. Once running, click on **"Connection Details"** tab
8. **IMPORTANT**: Copy these values — you'll need them:
   - `Host`
   - `Port`
   - `Database`
   - `Username`
   - `Password`

### Step 4: Connect Your GitHub Repo
1. In the left sidebar, click **"Account"** → **"Integrations"** (or it may prompt you)
2. Click **"Link GitHub"**
3. Authorize Northflank to access your GitHub repos
4. Select the repository: `western-visayas-region-6-investment-economic-profile`

### Step 5: Create a Service (Your App)
1. Go back to your project
2. In the left sidebar, click **"Services"**
3. Click **"Create Service"** → Select **"Combined Service"**
4. Configure the service:
   - **Name**: `web-app`
   - **Repository**: Select your linked GitHub repo
   - **Branch**: `main`
   - **Build Type**: **Dockerfile** (it should auto-detect your `Dockerfile`)
   - **Dockerfile Path**: `./Dockerfile` (default)
   - **Port**: `80` (this is what Apache listens on)
5. Click **"Create Service"**

### Step 6: Set Environment Variables
1. Inside your service, go to the **"Environment"** tab
2. Click **"Add Variable"** and add these one by one:

| Key | Value |
|---|---|
| `APP_NAME` | `Western Visayas Profile` |
| `APP_ENV` | `production` |
| `APP_KEY` | *(paste the key from Part 1, Step 1)* |
| `APP_DEBUG` | `false` |
| `APP_URL` | *(leave blank for now, update after first deploy)* |
| `DB_CONNECTION` | `mysql` |
| `DB_HOST` | *(from MySQL addon Connection Details)* |
| `DB_PORT` | *(from MySQL addon, usually 3306)* |
| `DB_DATABASE` | *(from MySQL addon)* |
| `DB_USERNAME` | *(from MySQL addon)* |
| `DB_PASSWORD` | *(from MySQL addon)* |
| `SESSION_DRIVER` | `file` |
| `CACHE_STORE` | `file` |
| `LOG_CHANNEL` | `stderr` |

3. Click **"Update & Restart"**

### Step 7: Wait for Build & Deploy
1. Go to the **"Builds"** tab to watch the build progress
2. The multi-stage Docker build will:
   - Install Node dependencies & build frontend assets
   - Install Composer/PHP dependencies
   - Build the final Apache image
3. This takes about **3-5 minutes** the first time
4. Once the build finishes, it automatically deploys

### Step 8: Run Database Seeder (One-Time)
1. Go to the **"Jobs"** section in your project sidebar
2. Click **"Create Job"** → **"Manual Job"**
3. Configure:
   - **Name**: `seed-database`
   - **Image**: Use the same image from your service
   - **Command Override**: `php artisan db:seed --force`
4. Run the job
5. **Alternative**: You can also use the **"Shell"** feature:
   - Go to your service → **"Shell"** tab
   - Run: `php artisan db:seed --force`

### Step 9: Get Your Public URL
1. Go to your service → **"Networking"** or **"Ports"** tab
2. Northflank provides a URL like:
   ```
   https://web-app--western-visayas-profile--xxxxxx.code.run
   ```
3. **Share this URL** with anyone to view your site!
4. Update the `APP_URL` environment variable with this URL

---

## Part 3: Ongoing Updates

Every time you push to `main` on GitHub, Northflank will automatically rebuild and redeploy your app.

```bash
# Make changes locally, then:
git add -A
git commit -m "Update content"
git push origin main
# Northflank auto-deploys in ~3-5 minutes
```

---

## Troubleshooting

| Problem | Solution |
|---|---|
| Build fails | Check the **Builds** tab → click on the failed build → read the logs |
| 500 error on site | Check `APP_KEY` is set, and DB credentials are correct |
| "Connection refused" DB error | Make sure `DB_HOST` uses the Northflank addon host, not `localhost` |
| Blank page | Check `APP_DEBUG=true` temporarily to see errors, then set back to `false` |
| Admin login not working | Run `php artisan db:seed --class=AdminUserSeeder --force` via Shell |

---

## Important URLs
- **Public site**: Your Northflank URL
- **Admin login**: `<your-url>/portal-access-secret`
- **Admin credentials**: `admin@example.com` / `password123`
