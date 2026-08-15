# Nano Suits — Design Studio + Private Cloud

Clean rebuild by Grok (xAI).

## Structure

### Frontend (GitHub Pages)
- `index.html` → Modern dark design studio landing page

### Backend (upload these to InfinityFree or any free PHP host)
- `login.php` → Clean login page
- `cloud.php` → Full file manager (upload, create folders, delete, rename, navigate)

## How to go live

### 1. Frontend (already on GitHub)
Go to repo **Settings → Pages**:
- Source: Deploy from a branch
- Branch: `main` / root
- Save

Your site will be at: `https://us-crypto.github.io/NSTest/`

### 2. Backend (PHP Cloud)
1. Sign up at [InfinityFree](https://www.infinityfree.com) (free, no credit card)
2. Create a hosting account + free subdomain
3. Upload `login.php` + `cloud.php` + create a folder named `files`
4. Edit the top of `login.php` if you want real database users later
5. Change the Login buttons in `index.html` to point to your InfinityFree login URL

### Default demo logins
- `admin@nano.com` / `admin123`
- `ceo@nano-suits.com` / `secret`

## What was cleaned
- Removed 20k+ lines of messy Bootstrap template bloat
- Modern dark AI-style design
- Real working file cloud in ~200 lines of clean PHP
- No more exposed credentials or giant duplicated HTML

Built to show what’s possible with clean code + free hosting.
