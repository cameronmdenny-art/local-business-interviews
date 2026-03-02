# GitHub Auto-Deploy Setup Guide

This guide walks you through setting up automatic deployment to Hostinger using GitHub Actions.

## Step 1: Initialize Git Repository (Local)

From your Mac terminal in the plugin directory:

```bash
cd /Users/camerondenny/Desktop/local-business-interviews
git init
git add .
git commit -m "Initial commit: Local Business Interviews plugin"
```

## Step 2: Create GitHub Repository

1. Go to [github.com](https://github.com) and sign in
2. Click **+** (top right) → **New repository**
3. Name it: `local-business-interviews`
4. Choose **Private** (keeps code safe)
5. Click **Create repository**
6. **Copy the repository URL** (looks like `https://github.com/yourusername/local-business-interviews.git`)

## Step 3: Add Remote & Push Code (Local)

Replace `YOUR_REPO_URL` with the URL from Step 2:

```bash
git remote add origin YOUR_REPO_URL
git branch -M main
git push -u origin main
```

## Step 4: Get Hostinger FTP Credentials

1. Log in to [Hostinger Dashboard](https://www.hostinger.com)
2. Go to **Hosting** → Your website
3. Click **Manage** → **Files** → **FTP Accounts**
4. You should see your main FTP account
5. Note down:
   - **FTP Host** (usually `ftp.yourdomain.com` or `yourdomain.com`)
   - **FTP User** (usually your login username)
   - **FTP Password** (your FTP password; if unsure, reset it)

If you can't find it, create a new FTP account from the FTP Accounts page.

## Step 5: Add GitHub Secrets

1. Go to your GitHub repository (the one you just created)
2. Click **Settings** → **Secrets and variables** → **Actions** → **New repository secret**
3. Add three secrets:

   **Secret 1:**
   - Name: `FTP_HOST`
   - Value: Your FTP host (e.g., `ftp.yourdomain.com`)
   - Click **Add secret**

   **Secret 2:**
   - Name: `FTP_USER`
   - Value: Your FTP username
   - Click **Add secret**

   **Secret 3:**
   - Name: `FTP_PASSWORD`
   - Value: Your FTP password
   - Click **Add secret**

## Step 6: Test the Deployment

1. Make a small change locally (e.g., edit a comment in `README.md`)
2. Commit and push:
   ```bash
   git add .
   git commit -m "Test deployment"
   git push origin main
   ```
3. Go to your GitHub repository → **Actions** tab
4. You should see a workflow running
5. Wait for it to complete (green checkmark = success)
6. Visit your site: WordPress should still work, plugin active

## From Now On...

Every time you want to update the plugin on Hostinger:

```bash
# Make your changes locally
# Then commit and push:
git add .
git commit -m "Your change description"
git push origin main
```

GitHub Actions automatically:
1. Checks PHP syntax for errors
2. Builds the release package
3. Deploys to Hostinger via FTP

**No more manual uploads!**

## Troubleshooting

**Workflow fails with "FTP connection refused"**
- Check that `FTP_HOST`, `FTP_USER`, `FTP_PASSWORD` are correct
- Test them in your FTP client first (Cyberduck, Transmit, etc.)

**Plugin doesn't update on the site**
- Check Actions tab for errors
- Clear WordPress cache (if using caching plugin)
- Hard refresh browser (Cmd+Shift+R on Mac)

**Files not syncing correctly**
- The workflow uploads all files except:
  - `.git/` (git files)
  - `.github/` (workflow files)
  - `dist/` (build artifacts)
  - Build scripts
  - Backup files
- This keeps Hostinger clean and avoids extra files

## Security Notes

- **Secrets are encrypted** in GitHub (you can't see them after adding)
- **Never commit** FTP credentials to code
- **Use SFTP if possible** (more secure than FTP)
- If your Hostinger plan supports SFTP, change `FTP_HOST` to use `sftp://` prefix

## Need Help?

Check the Actions tab in your GitHub repository to see:
- Workflow logs
- What commands ran
- Any error messages
- Deployment status
