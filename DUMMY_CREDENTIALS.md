# Dummy Credentials for Testing

After running `npm run db:seed`, use these test accounts to explore the application.

## Staff Accounts

These accounts have access to the staff dashboard and approval workflows.

| Email | Password | Role | Purpose |
|-------|----------|------|---------|
| `admin@university.ac.za` | `Admin@123` | Admin | System administration |
| `hod@university.ac.za` | `HOD@123` | HOD | Head of Department approval |
| `dean@university.ac.za` | `Dean@123` | Dean_Assistant | Registration verification |
| `coord@university.ac.za` | `Coord@123` | Coordinator | Claims and payroll coordination |

## Student Accounts

These accounts are pre-populated in the system for testing workflows.

| Email | Password | Student Number |
|-------|----------|-----------------|
| `student1@university.ac.za` | `Student@123` | ST001 |
| `student2@university.ac.za` | `Student@123` | ST002 |
| `student3@university.ac.za` | `Student@123` | ST003 |

## How to Login

### Web Interface
1. Open http://localhost:5173/login
2. Enter an email from the tables above
3. Enter the corresponding password
4. Click "Login"

### API (curl)
```bash
curl -X POST http://localhost:4000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"dean@university.ac.za","password":"Dean@123"}'
```

## Test Workflows

### Test Staff Approval Workflow
1. Login as `dean@university.ac.za` (Dean_Assistant)
2. Navigate to Staff Dashboard
3. View pending registrations
4. Approve or reject student registrations

### Test Student Registration
1. Login as a student account
2. Start a new registration if available
3. Complete the multi-step form:
   - Biographical information
   - Banking details
   - Tax declaration
   - Terms acceptance
   - Document uploads
4. Submit for verification

### Test Appointment & Claims
1. Login as a student with an active appointment
2. View active appointments on dashboard
3. Submit a claim for the current month
4. Verify salary calculations and tax rates

## Seeding the Database

The seed script creates:
- 1 Campus (Main Campus)
- 1 Department (Computer Science)
- 1 Job Category (Tutoring)
- 4 Staff accounts with different roles
- 3 Student accounts

To run the seed script:

```bash
cd backend
npm run db:seed
```

**Note:** The seed script uses `upsert` operations, so it's safe to run multiple times. It won't create duplicates.

## Resetting Test Data

To clear and re-seed the database:

```bash
cd backend
# Reset all data and run migrations
npx prisma migrate reset

# Re-run the seed script
npm run db:seed
```

**Warning:** This will delete all existing data in the database.

## Changing Passwords

To update a test account password, use the API:

```bash
# First, login with current credentials
TOKEN=$(curl -s -X POST http://localhost:4000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"student1@university.ac.za","password":"Student@123"}' \
  | jq -r '.accessToken')

# Then change password (if API endpoint exists)
# See backend/README.md for available endpoints
```

## Custom Seed Data

To add more test accounts, edit `backend/prisma/seed.ts`:

1. Add entries to `staffAccounts` or `studentAccounts` arrays
2. Run `npm run db:seed` again

Example:
```typescript
{ email: 'mytest@university.ac.za', password: 'MyTest@123', name: 'Test User', role: 'Coordinator', number: 'S005' }
```
