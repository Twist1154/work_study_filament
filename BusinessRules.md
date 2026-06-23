# Business Rules & Modules – Student Workstudy Management System

## Overview
This document describes the system modules in the order they are used, along with the precise business rules enforced by the application and database. Each module corresponds to a distinct phase of the student life‑cycle, from invitation to payroll export.

---

## 1. Invitation & Pre‑Appointment Setup

**Who:** HOD Assistant  
**What:** The HOD Assistant creates an invitation for a student, pre‑selecting the appointment details before the student ever logs in.

### Business Rules
| Rule | Enforcement |
|------|-------------|
| An invitation is required before a student can start registration. | The registration process is only accessible via a valid, unexpired invitation token. |
| Invitations are linked to a specific job category, department, campus, supervisor, and cost centre. | The `invitations` table stores these fields as non‑null foreign keys. |
| The cost centre defaults to `Y269` but can be overridden at invitation creation. | `cost_centre` column has a `DEFAULT 'Y269'` constraint. |
| Invitations expire after a configurable period (e.g., 48 hours). | `expires_at` timestamp is set on creation; token validation checks `NOW() < expires_at`. |
| A student cannot use the same invitation more than once. | `invitation_token` is unique; once a registration is linked, the token is consumed. |
| A student cannot be appointed to more than one department on the same campus at the same time. | Application‑level validation during invitation creation or final approval. |

**Module implementation:**
- `POST /api/v1/invitations` – creates an invitation and emails the link to the student.
- `GET /api/v1/invitations` – lists all invitations (filterable by status) for the HOD Assistant.

---

## 2. Student Onboarding & Registration

**Who:** Student (via invitation link)  
**What:** The student completes a multi‑step form that gathers all personal, financial, and legal information required before an appointment can be created.

### 2.1 Biographical Details & Conditions of Service
| Rule | Enforcement |
|------|-------------|
| Student must provide surname, ID/passport number, nationality, contact, and address. | All mandatory fields are `NOT NULL` in `students` and `address` tables. |
| Student must tick a checkbox to accept the Conditions of Service. | `conditions_accepted` in `registrations` is `NOT NULL DEFAULT FALSE`; final submission blocked if `FALSE`. |
| If the student is a foreign national, they must provide a work/study permit number and expiry date. | `is_foreign_student` flag; `work_permit_number` and `work_permit_expiry` become mandatory when `TRUE`. |

### 2.2 Banking Details
| Rule | Enforcement |
|------|-------------|
| Student must capture account type, account number, bank name, branch name, branch code. | `bank_details` table – all fields except `branch_name` are `NOT NULL`. |
| Ownership type is either `own` or `third_party`. | `CHECK` constraint on `ownership_type`. |
| If ownership type is `third_party`, Section C fields (third‑party name and relationship) become mandatory. | Application‑level validation: if `ownership_type = 'third_party'`, then `third_party_name` and `third_party_relationship` must be provided. |
| Bank details must not be older than 3 months at the time of any future claim submission. | `valid_from` date is stored; when a claim is submitted, the system checks `claim_submission_date - valid_from <= 3 months`. |

### 2.3 Tax Declaration
| Rule | Enforcement |
|------|-------------|
| Student must declare in their own words that they work fewer than 22 hours per completed week and have no other employer. | `declaration_text` stores the student's statement; boolean flags `works_less_than_22hrs` and `no_other_employer` are set to `TRUE`. |
| If a tax declaration is absent at any point after registration, a 25% PAYE deduction applies on all claims. | `tax_rate_applied` in `tax_declarations` defaults to `0.25`; if a declaration exists it is `0.0`. The claim payout calculation uses the rate from the latest declaration. |
| Declaration is stored with signed place and date. | `signed_place`, `declaration_date`. |

### 2.4 Workstudy Terms & Conditions
| Rule | Enforcement |
|------|-------------|
| Student must review the T&C document, accept it, and affix an electronic signature. | `terms_accepted` must be `TRUE`; `student_signature_file`, `student_signed_date`, `student_signed_place` are populated. |
| The HOD will later countersign; until both signatures are present, claims cannot be submitted. | The appointment is only created after final approval, and the claims module checks for a completed `workstudy_terms` record. |

### 2.5 Document Uploads (during registration)
| Rule | Enforcement |
|------|-------------|
| The system tracks a document checklist based on the appointment type and student status. | Required documents are enforced by application logic; missing documents block advancement. |
| Accepted document types: ID Copy, Proof of Registration, CV, Highest Qualification, SARS Tax Certificate, Tutor Training Certificate, Work Permit, Study Permit, Tax Declaration (if uploaded as PDF), Other. | `document_type` field with validation. |
| For foreign students, a Work Permit or Study Permit must be uploaded, and its expiry date recorded. | `permit_expiry_date` column; if student `is_foreign_student = TRUE`, at least one permit document with expiry date is required. |
| Tutor applicants must upload a FUNDANI Tutor Training Certificate before HOD approval. | Document checklist enforces this; if `job_category` is tutor‑related, the certificate is mandatory before status can move to `pending_hod_approval`. |
| Documents uploaded during registration are linked to the `registration` record; reusable documents (ID, CV, etc.) are later promoted to the student's global library. | `documents` table with nullable `registration_id` and `student_id`. |

### Module implementation
- Various `PUT`/`POST` endpoints under `/registrations/:token/*` to save each step.
- `POST /registrations/:token/submit` – final submission, which validates all completeness rules and changes status to `pending_verification`, notifying the Dean's Assistant.

---

## 3. Document Management (Global & Reusable)

**Who:** System, students  
**What:** A central repository for documents that can be reused across multiple appointments.

| Rule | Enforcement |
|------|-------------|
| After final approval, student‑specific documents (ID Copy, CV, Highest Qualification) are automatically copied or linked to the student's global document library. | A database trigger or application logic creates `documents` records with `student_id` set and `registration_id` NULL. |
| Students can later upload additional global documents via `POST /documents`. | Endpoint creates a record with `student_id` only. |
| When a new appointment is made (renewal), the system pre‑fills the document checklist from the global library. | Lookup of `documents` where `student_id = current student` and `document_type` matches a requirement. |

---

## 4. Verification & Approval Workflow

**Who:** Dean's Assistant, HOD, then back to Dean's Assistant.  
**What:** The registration package is reviewed, signed off, and finally approved to create a formal appointment.

### Steps
1. **Verification:** Dean's Assistant reviews all submitted data and documents.
    - **Approve** → status `pending_hod_approval`.
    - **Reject** → status `rejected`; student must edit and resubmit.
2. **HOD Approval:** HOD reviews, then countersigns the Terms & Conditions electronically.
3. **Final Approval:** Dean's Assistant performs a final check and confirms. The system then:
    - Creates the `appointments` record.
    - Generates a PDF claims sheet.
    - Emails the PDF to HR.

### Business Rules
| Rule | Enforcement |
|------|-------------|
| All checklist items (biographical, bank, T&C, tax declaration, required documents) must be complete before the registration can be verified. | `POST /registrations/:token/submit` performs a full validation; incomplete registrations cannot be submitted. |
| A registration cannot be approved without a countersigned Terms & Conditions record. | HOD approval step (`PATCH /registrations/:id/hod-sign`) requires the T&C to have been accepted by the student; HOD signature is stored. |
| The final approval (Dean's Assistant) cannot be performed unless HOD has already signed. | Status flow validation: only `pending_hod_approval` can transition to `pending_final`. |
| Once final approval is given, the appointment is automatically created with the pre‑filled details from the invitation. | Database transaction: insert into `appointments`, link to registration. |
| For foreign students, the appointment termination date is automatically capped at the permit expiry date. | If `work_permit_expiry < commencement_date + expected_duration`, then `termination_date = work_permit_expiry`. |

### Module implementation
- `GET /registrations/pending` – list for Dean's Assistant and HOD.
- `PATCH /registrations/:id/verify` (Dean's Assistant)
- `PATCH /registrations/:id/hod-sign` (HOD)
- `PATCH /registrations/:id/final-approve` (Dean's Assistant)

---

## 5. Active Appointments & Claims Management

**Who:** Student (submission), Supervisor (approval), Coordinator (oversight)  
**What:** Students with active appointments can submit monthly claims for hours worked.

### 5.1 Appointment Lifecycle
| Rule | Enforcement |
|------|-------------|
| An appointment is either `New Appointment` or `Renewal`. | `appointment_type` column with `CHECK` constraint. |
| A student may have only one active appointment per campus at a time. | Application validation at creation time. (Since registration is linked to a single appointment, this is inherited). |
| Appointment status transitions: `active` → `completed` (when termination date passes) → `terminated` (if manually ended). | Status management logic. |

### 5.2 Claim Submission
| Rule | Enforcement |
|------|-------------|
| Claims are submitted per appointment, per calendar month. | Composite unique constraint or validation: only one claim per `(appointment_id, claim_month, claim_year)`. |
| Maximum hours cap per month: 80 hours for IT Centre, E‑Learning Labs, and Library roles. | The `job_categories.max_hours_per_month` field is checked: `hours_worked <= get_max_hours(appointment.job_category_id)`. |
| Students are paid only for actual hours worked; lunch breaks are excluded. | The claim form accepts clock‑in/clock‑out times; the system subtracts a configurable lunch duration (default 30 minutes) and stores net `hours_worked`. |
| Salary split is calculated automatically: | |
| – If fees outstanding: 60% to fee account, 40% to bank. | `students.fee_account_outstanding = TRUE`. |
| – If fees settled: 100% to bank. | `fee_account_outstanding = FALSE`. |
| – NSFAS students: 60/40 split until fee account settled. | `nsfas_funded = TRUE` and `fee_account_outstanding = TRUE`. |
| – Full bursary holders: 60/40 split unless fees settled before 2nd semester. | `full_bursary_holder = TRUE`; if `bursary_settled_before_sem2 = FALSE`, 60/40 applies. |
| Claims submitted after the monthly payroll cut‑off date are marked as late. | A `settings` table holds the cut‑off day (`payroll_cutoff_day`). If `submission_day > cutoff_day`, `is_late_claim = TRUE`; such claims are paid in the following month's payroll run. |
| A student cannot submit bulk claims for more than 2 months at a time. | A submission may contain at most two distinct calendar months; additionally, a student may not have more than 2 open (unpaid) claims simultaneously. |
| Disciplinary action is triggered if a claim form is altered after supervisor approval. | Once `locked_after_supervisor_approval = TRUE`, any attempt to update the record (except by an authorised override) triggers an audit log entry and a notification to the Workstudy Coordinator. |
| Tax rate applied on the claim is taken from the student's latest tax declaration. | `tax_rate_applied` column on the claim is populated at submission time (`0.0` or `0.25`). |

### 5.3 Claim Approval & Export
| Rule | Enforcement |
|------|-------------|
| Supervisor approves the claim, which locks the form. | `PATCH /claims/:id/approve` sets `status = 'supervisor_approved'` and `locked_after_supervisor_approval = TRUE`. |
| Coordinator can view all claims and export approved ones for payroll. | `GET /claims/export` returns a CSV/Excel file for the payroll department. |

### Module implementation
- `POST /students/me/claims` – student submits claim.
- `GET /students/me/claims` – student views own claims.
- `PATCH /claims/:id/approve` – supervisor approval.
- `GET /claims` – Coordinator list (filterable).
- `GET /claims/export` – Payroll export.

---

## 6. Notifications & Alerts

**What:** Automated emails triggered by system events.

### Triggering Rules
| Event | Recipient |
|-------|-----------|
| Invitation created | Student (registration link) |
| Registration submitted for verification | Dean's Assistant |
| Verification approved | HOD |
| Rejected (any step) | Student (with reason) |
| Final approval & claims sheet ready | HR department |
| Work/study permit expires in 30 days | Student, Coordinator |
| Disciplinary alert (claim altered after lock) | Coordinator |

**Implementation:**
- `notifications` table records all sent alerts.
- A background job scans for upcoming permit expiries daily.

---

## 7. System Settings

Configurable parameters stored in a `settings` table:

| Setting | Description | Default |
|---------|-------------|---------|
| `payroll_cutoff_day` | Day of the month after which claims are late. | `20`    |
| `lunch_break_minutes` | Duration subtracted from clock‑in/out to compute net hours. | `30`    |
| `default_cost_centre` | Cost centre applied to new invitations/appointments. | `Y269`  |
| `invitation_expiry_hours` | How long an invitation link is valid. | `48`    |

**API:** `GET /api/v1/settings`, `PUT /api/v1/settings` (Admin only) — extend as needed.

---

This arrangement follows the natural student life‑cycle, ensuring every rule is enforced at the correct stage and no step can be bypassed.