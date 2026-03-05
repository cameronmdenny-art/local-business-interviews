# Version Control Guide – Simple & Descriptive

## The Simple System
**Name every save based on WHAT YOU DID or WHAT THE OUTPUT IS.** When you save a version, describe it in a way that immediately tells you what's in that snapshot.

### Examples of Good Names:
- `save-removed-domain-name` – What you did
- `save-dark-header-footer` – What the feature is
- `save-before-contact-form-changes` – What you're about to do
- `save-working-navigation` – What's working
- `save-fixed-mobile-layout` – What was fixed

---

## Quick Commands (Copy & Paste)

### 1️⃣ SAVE YOUR CURRENT WORK
**Use when:** You've made changes and they work. Do this BEFORE making risky changes.

```bash
cd /Users/camerondenny/Desktop/local-business-interviews

# Replace DESCRIPTION with what you did
git add -A && git commit -m "Save: DESCRIPTION" && git tag "save-DESCRIPTION" && git push origin main --tags
```

**Example:**
```bash
git add -A && git commit -m "Save: dark header footer working" && git tag "save-dark-header-footer-working" && git push origin main --tags
```

This creates:
- A commit with clear message
- A labeled tag (shows up in repo as restore point)
- Pushes to GitHub so you don't lose it

---

### 2️⃣ SEE ALL YOUR SAVED VERSIONS
```bash
git tag -l | sort
```

Shows all your save points with their descriptive names.

---

### 3️⃣ RESTORE A SAVED VERSION
**Use when:** Something broke and you want to go back to a working version.

```bash
# Find the version name from: git tag -l | sort

# Restore it locally
git fetch --all --tags
git checkout save-DESCRIPTION

# After checking everything works, merge back to main:
git checkout main
git merge save-DESCRIPTION
```

**Example – restore dark header footer:**
```bash
git checkout save-dark-header-footer-working
# Check it locally, then:
git checkout main
git merge save-dark-header-footer-working
git push origin main
```

---

### 4️⃣ DEPLOY TO PRODUCTION (after restore/changes)
```bash
cd /Users/camerondenny/Desktop/local-business-interviews
python3 /tmp/sync_tracked_only.py
```

---

## Current Saved Versions

These are your existing restore points:

1. **`snapshot-elegant-header-footer-2026-03-04`** – Dark elegant header/footer design (CURRENT – deployed to production)
2. **`save-dark-header-footer-working`** – *(ready to use if you restore)*

---

## Workflow Example: How to Use This

1. **Current state is working?** Save it:
   ```bash
   git add -A && git commit -m "Save: header footer styled correctly" && git tag "save-header-footer-styled" && git push origin main --tags
   ```

2. **Make risky changes** (test something new)

3. **It broke?** Restore from your save:
   ```bash
   git checkout save-header-footer-styled
   git checkout main
   git merge save-header-footer-styled
   python3 /tmp/sync_tracked_only.py
   ```

4. **It's back to working.** Repeat step 1 with a new description.

---

## Key Points
- ✅ **Name saves by what they represent** – not dates, not random IDs
- ✅ **Save BEFORE risky changes** – not after they break
- ✅ **Use `git tag`** – makes restore points visible and easy to find
- ✅ **Push tags to GitHub** – backup in case your Mac fails
- ✅ **Describe clearly** – future-you will thank present-you

---

## One-Command Quick Save (Copy This)
```bash
git add -A && git commit -m "Save: $1" && git tag "save-$1" && git push origin main --tags
```

Use it like: `, "added contact form"` and it creates `save-added-contact-form`
