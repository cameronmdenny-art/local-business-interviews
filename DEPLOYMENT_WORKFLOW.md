# Automated Workflow for Local Business Interviews

## Your New Workflow (Simple & Fast!)

### When You Want Changes:

1. **Tell me what you want changed** (via chat)
2. **I make the changes** (edit the files)
3. **Run the deploy script:**
   ```bash
   ./quick-deploy.sh
   ```
4. **Two windows open automatically:**
   - WordPress plugin upload page
   - Folder with the new plugin zip
5. **Drag & drop** the zip file to WordPress
6. **Click "Replace current with uploaded"**
7. **Refresh your site** - changes are live!

## Total Time: ~30 seconds

---

## Example Flow:

**You:** "Change the homepage hero title to 'Discover Amazing Local Businesses'"

**Me:** ✅ *Updates front-page.php*

**You:** *In terminal:* `./quick-deploy.sh`
- ✅ Builds new package
- ✅ Opens WordPress upload page
- ✅ Opens folder with zip file

**You:** *Drag → Drop → Replace → Done!*

**Result:** Site updated in 30 seconds

---

## Files That Matter:

- **front-page.php** - Homepage template
- **includes/home-shortcode.php** - If you use [lbi_homepage] shortcode
- **includes/templates/home-page.php** - Alternative homepage template
- **single-interview.php** - Individual interview pages
- **single-directory.php** - Individual directory listing pages
- **includes/cpt.php** - Custom post type definitions
- **includes/shortcodes.php** - All shortcodes

---

## Future: Once FTP Password Works

If you get the FTP password working, I can set up true one-command deployment:
```bash
./deploy-to-hostinger.sh
```
This would upload directly without the WordPress admin step.

---

## Future: Once GitHub Works

If we fix the GitHub authentication, every `git push` would auto-deploy via GitHub Actions - no manual steps at all.

---

## Current Setup: Best Available

Given the constraints (no FTP password, GitHub auth issues), this is the fastest possible workflow. It's:
- ✅ Fast (30 seconds)
- ✅ Reliable (uses WordPress's own upload system)
- ✅ Simple (drag and drop)
- ✅ Safe (WordPress validates the plugin before installing)

You're welcome to try resetting your FTP password again if you want even more automation!
