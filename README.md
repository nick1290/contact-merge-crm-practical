# contact-merge-crm-practical
Practical Exam Instructions (CRM-like Features)

A modular Laravel application to manage **Contacts** with support for:

- ✅ CRUD (Create, Read, Update, Delete)
- ✅ Custom Fields (dynamic)
- ✅ File uploads (profile image & document)
- ✅ AJAX operations for insert, update, delete
- ✅ Filtering & Search (controller-side)
- ✅ DataTables integration with child table support

## 🚀 Installation Steps

### 1. Clone the Project
```bash
git clone https://github.com/nick1290/contact-merge-crm-practical
cd contact-merge-crm-practical

composer install

### 2. Setup you db
DB_DATABASE=your_database
DB_USERNAME=root
DB_PASSWORD=secret

### 3. Run Migrations 
php artisan migrate --seed


### 4. Useful Commands
php artisan storage:link               # Link uploads to public folder
