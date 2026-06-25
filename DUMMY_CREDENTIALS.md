# Dummy Credentials for Testing

After running `php artisan migrate:fresh --seed`, use these test accounts to explore the application [1.2.1, 1.2.2].

## Staff & Admin Accounts

These accounts have access to the staff dashboard and approval workflows [1.1.3].

| Email | Password | Role | Panel URL | Purpose |
|-------|----------|------|-----------|---------|
| `admin@university.ac.za` | `Admin@123` | Admin | `http://localhost:8000/app/login` | System administration & user management |
| `hod@university.ac.za` | `HOD@123` | HOD | `http://localhost:8000/staff/login` | Head of Department contract sign-off |
| `dean@university.ac.za` | `Dean@123` | Dean_Assistant | `http://localhost:8000/staff/login` | Student registration verification & final approval |
| `coord@university.ac.za` | `Coord@123` | Coordinator | `http://localhost:8000/staff/login` | Claims oversight & payroll exports |

---

## Student Accounts

These accounts are used to test student registration, file uploads, and claim submissions [1.1.3].

| Email | Password | Student Number | Purpose | Panel URL |
|-------|----------|----------------|---------|-----------|
| `applicant@university.ac.za` | `Student@123` | *N/A (Enter manually)* | **Test registration wizard from scratch** [2.1.3] | `http://localhost:8000/student/login` |
| `student1@university.ac.za` | `Student@123` | ST001 | Test active dashboard & submitting claims | `http://localhost:8000/student/login` |
| `student2@university.ac.za` | `Student@123` | ST002 | Test active dashboard & submitting claims | `http://localhost:8000/student/login` |
| `student3@university.ac.za` | `Student@123` | ST003 | Test active dashboard & submitting claims | `http://localhost:8000/student/login` |

---

## How to Login

### Web Interface
1. Open the URL corresponding to the user type in the tables above.
2. Enter the email and password.
3. Click "Sign in".

---

## Test Workflows

### 1. Test Student Registration (Onboarding Wizard)
1. Navigate to the student login page: `http://localhost:8000/student/login`
2. Login as the clean testing account: `applicant@university.ac.za` / `Student@123` [2.1.3].
3. You will be automatically presented with the multi-step registration wizard at `http://localhost:8000/student/register-student` [2.1.3].
4. Complete the form steps:
   * **Biographical Details:** Fill out personal details and provide separate Home and Current addresses.
   * **Banking Details:** Enter account specifications. Section C will dynamically appear only if you select "Joint Account" or "Third Party Account".
   * **Supporting Documents:** Upload required files (Certified ID, Bank Proof, Student Registration Proof, and Study Permit if foreign).
   * **Terms & Conditions:** Review regulations, accept, and upload an electronic signature file.
   * **Tax Declaration:** Tick the statement checkboxes, enter your declaration in your own words, and submit.
5. Click **Submit Registration** at the end. Your data and uploads will be persisted to the database and linked to the user account [1.1.2].

### 2. Test Staff Verification & Approval Workflow
1. Navigate to the staff login page: `http://localhost:8000/staff/login`
2. Login as the Dean's Assistant: `dean@university.ac.za` / `Dean@123`.
3. Open the **Registrations** resource.
4. Click **View** (the eye icon) on a student's row. A custom **Review Student Onboarding Package** modal will slide open.
5. Inspect the student's biographical data, banking details, and click the direct attachment links inside the **Document Library** repeatable list to review or download their uploaded files [1.1.2].
6. Use the custom row actions to advance the registration status:
   * **Verify:** Advances a newly submitted record from `pending_student` $\rightarrow$ `pending_hod_approval`.
   * **HOD Sign (Run as `hod@university.ac.za`):** Adds HOD signature statement and advances status to `pending_final`.
   * **Final Approve:** Moves status to `approved` and automatically generates the corresponding `Appointment` record in your database [1.1.2].

---

## Database Management (Artisan Commands)

Since the system has been migrated to Laravel 12, all database management is handled cleanly via the Laravel Artisan CLI [1.2.1, 1.2.2].

### Re-seeding the Database
To clear all data, re-run all migrations from scratch, and seed your testing accounts, run [1.2.1, 1.2.2]:
```bash
php artisan migrate:fresh --seed
```

**Warning:** This will wipe all existing data in your local tables.

### Creating a New Administrative Account
If you need to quickly create an administrative user manually via the CLI, run [2.1.3]:
```bash
php artisan make:filament-user
```
Here are the exact login credentials and URLs you should use for each of the three dashboards:

---

### 1. Unregistered Student Dashboard (Onboarding Wizard)
Use this login to test the multi-step registration wizard, file uploads, and database insertion logic [2.1.3].

* **URL:** **`http://localhost:8000/student/login`**
* **Email:** `applicant@university.ac.za`
* **Password:** `Student@123`

*(Once logged in, because this account does not have a pre-existing profile in the `students` table, it will open the onboarding page at `/student/register-student` [2.1.3]).*

---

### 2. Staff Dashboard
Use these logins to review registration details, perform HOD sign-offs, and run the verification workflow [1.1.2].

* **URL:** **`http://localhost:8000/staff/login`**
* **Available Accounts:**
    * **Dean Assistant** *(Performs initial verification & final approvals)*:
        * **Email:** `dean@university.ac.za`
        * **Password:** `Dean@123`
    * **Head of Department (HOD)** *(Performs contract countersigning)*:
        * **Email:** `hod@university.ac.za`
        * **Password:** `HOD@123`
    * **Workstudy Coordinator** *(Manages active claims & exports payroll CSVs)*:
        * **Email:** `coord@university.ac.za`
        * **Password:** `Coord@123`

---

### 3. Admin Dashboard
Use this login to manage system settings, campuses, department structures, job categories, and core database tables.

* **URL:** **`http://localhost:8000/app/login`**
* **Email:** `admin@university.ac.za`
* **Password:** `Admin@123`
