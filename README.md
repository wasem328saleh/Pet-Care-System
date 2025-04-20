# Pet Care Management System (Backend API)

A comprehensive Laravel-based backend system for managing animal adoptions, veterinary clinics, products, and training programs. Designed to streamline operations for veterinary professionals, pet owners, and animal trainers.

## 🚀 Features

### **Admin Panel**
- 🛠️ **CRUD Operations**: Manage animals (adoptions), clinics, vets, illnesses, products, and trainings.
- ✅ **Order Management**: Approve/reject adoption, product, and training orders.
- 👥 **User Management**: View/delete users and their activities.

### **User-Facing Features**
- 🐾 **Animal Adoption**: Submit and track adoption requests.
- 🛒 **Product Purchases**: Order pet supplies and view order history.
- 🎓 **Training Programs**: Book and manage animal training sessions.
- 🤒 **Symptom Checker**: Get potential illness diagnoses based on symptoms.

### **Technical Highlights**
- 🔒 **JWT Authentication**: Secure role-based access (users/admins).
- 📹 **YouTube Integration**: Validate animal-related videos via YouTube API.
- 📊 **Dynamic Data**: APIs for clinics, doctors, illnesses, and adoptable animals.

## 💻 Technologies
- **Backend**: Laravel 9+ 
- **Authentication**: JWT (JSON Web Tokens)
- **Database**: MySQL/PostgreSQL
- **Tools**: RESTful API, YouTube Data API

## 🔧 Installation
1. Clone the repo:
   ```bash
   git clone https://github.com/your-username/pet-care-system.git
   ```
2. Install dependencies:
   ```bash
   composer install
   ```
3. Configure `.env` file:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=pet_care_db
   DB_USERNAME=root
   DB_PASSWORD=
   
   JWT_SECRET=your_jwt_secret_key
   YOUTUBE_API_KEY=your_youtube_api_key
   ```
4. Generate JWT secret:
   ```bash
   php artisan jwt:secret
   ```
5. Migrate & seed data:
   ```bash
   php artisan migrate --seed
   ```

## 🌐 API Endpoints (Key Examples)
| Endpoint                          | Method | Description                     | Auth Required |
|-----------------------------------|--------|---------------------------------|---------------|
| `api/login`                       | POST   | User/Admin login                | No            |
| `api/admin/Store_Animal`          | POST   | Add new animal for adoption     | Admin         |
| `api/user/Store_Animal_Adoption`  | POST   | Submit adoption request         | User          |
| `api/Get_All_Clinics`             | GET    | List all clinics                | Public        |
| `api/user/Get_Illness_By_Symptoms`| POST   | Symptom-based diagnosis         | User          |

## 🔧 Notes for Customization:

1. **Replace Placeholders**:
   - `your-username` → Your GitHub username.
   - `your_jwt_secret_key` → Generate using `php artisan jwt:secret`.
   - `your_youtube_api_key` → Obtain from [Google Cloud Console](https://console.cloud.google.com/).

2. **Frontend Integration**:
   - Add links to your frontend apps (e.g., React: `https://github.com/...`, Flutter: `https://github.com/...`).
