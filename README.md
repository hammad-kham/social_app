# 📘 Laravel Real-Time Post Feed

A Laravel-based real-time post feed application with dynamic posting, likes, comments, and live updates using AJAX and Laravel Echo.

---

## 🚀 Features

- Live posting without page refresh
- Real-time like and comment updates
- "Load more" for additional comments
- Responsive design
- Built with Laravel + JavaScript + Pusher

---

## ⚙️ Requirements

- PHP >= 8.1
- Composer
- Node.js and npm
- MySQL 
- Laravel CLI

---

## 📥 Installation

```bash
git clone https://github.com/hammad-kham/social_app
cd social_app
composer install
npm install
cp .env.example .env
php artisan key:generate


## 🧪 Quick Testing

Once your server is running, test the app with the following steps:

- **Create a Post**  
  Submit a new post using the form. It should instantly appear in the feed without refreshing.

- **Like a Post**  
  Click the ❤️ button on any post. The like count should update immediately.

- **Comment on a Post**  
  Type and submit a comment. It should appear below the post in real-time.

- **Load More Comments**  
  If a post has more than 2 comments, click **"Load more"** to dynamically reveal the rest.

- **Real-Time Updates**  
  Open the app in two browser tabs. When you like, comment, or post in one tab, the other should update automatically.
