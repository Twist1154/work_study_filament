Here is the complete operational workflow of your **CPUT Student Workstudy System** on the dashboards. It traces a student's entire journey from their initial invitation to completing onboarding, logging daily hours, generating the PDF timesheet, and exporting payroll [1.2.3].

---

### Phase 1: Student Account Creation & Invitation (Staff Panel)
1. A Staff Member logs into the Staff Panel (`http://localhost:8000/staff/login`) and creates an `Invitation` record using the student’s CPUT email address [1.2.3].
2. The system automatically triggers an email containing a secure activation token link [1.2.3].
3. The student clicks the activation link, sets their password, and logs into the Student Panel (`http://localhost:8000/student/login`) for the first time [1.2.3].

---

### Phase 2: Multi-Step Onboarding (Student Panel)
1. Upon logging in, because the student does not have a profile yet, they are automatically directed to your **Onboarding Wizard** (`/student/register-student`) [1.2.3].
2. They complete the 5 steps of the form:
    * **Biographical Details:** Enters personal details, and records both their **Home Address** and **Current Address** [1.1.2].
    * **Banking Details:** Enters account details. Section C (third-party details) dynamically appears *only* if they select "Joint Account" or "Third Party Account" [1.1.2].
    * **Supporting Documents:** Uploads clear copies of their certified ID, bank proof, tax number, proof of registration, and visa/permit if international [1.1.2].
    * **Terms & Conditions:** Reads regulations, accepts them, and uploads an electronic signature [1.1.2].
    * **Tax Declaration:** Declares working hour limitations and signs [1.2.3].
3. The student clicks **Submit Registration**.
    * A database transaction automatically saves records for `Student`, `Address`, `BankDetail`, `WorkstudyTerm`, `TaxDeclaration`, and `Document` [1.1.2].
    * A `Registration` record is created with the status **`pending_verification`** [1.2.3].
    * The student is redirected to their student dashboard and is locked out of editing the form while verification is in progress [1.2.3].

---

### Phase 3: Step-by-Step Verification & Feedback (Staff Panel)
1. The **Dean’s Assistant** (`dean@university.ac.za`) logs into `/staff` and opens the **Student Onboarding** resource. They find the student's record in the **Pending Verification** tab [1.2.3].
2. They click **View** (the eye icon) on the student's row. A custom details sheet slides open. Here, they can inspect all biographical details, banking details, and click the direct download attachment links in the **Document Library** to review the uploaded certified files [1.1.2, 1.2.3].
3. They close the preview and click **Verify Package** [1.1.2]. This opens an interactive checklist:
    * **If everything is correct:** They leave all toggles enabled and click submit. The registration status advances to **`pending_hod_approval`** [1.2.3].
    * **If any section has an issue:** They toggle that section off and write a correction comment (e.g., *"Certified ID copy is blurred, please re-upload"*). They click submit [1.1.2, 1.2.3].
        * The status changes to **`rejected`** [1.2.3].
        * **The Student Loop:** The student logs back into their panel, is greeted with a red alert box detailing exactly what was rejected and why, updates those specific fields, and resubmits. Resubmission resets their status to `pending_verification` for re-review [1.2.3].

---

### Phase 4: Sign-off & Appointment Generation (Staff Panel)
1. Once verified, the **HOD** (`hod@university.ac.za`) logs into `/staff`. Under the **HOD Sign-Off** tab, they review the package and click **HOD Sign-off** to countersign [1.2.3]. The status advances to **`pending_final`** [1.2.3].
2. The **Dean's Assistant** logs back in, opens the **Pending Final** tab, and clicks **Final Approve & Appoint** [1.2.3].
3. This triggers a database transaction that:
    * Updates the registration status to **`approved`** [1.2.3].
    * **Automatically creates an active `Appointment` record** in your database [1.1.2, 1.2.3].

---

### Phase 5: Daily Work Tracking / Clocking In & Out (Student Panel)
1. The student logs into `http://localhost:8000/student/login` [1.1.2, 1.2.3].
2. Because they now have an active `Appointment` record, their **Daily Work Tracker** page is active (the warning is gone).
3. Every day they report to work:
    * They open this tracker and click **Clock In** [1.1.2]. This creates a `WorkLog` record with their starting time [1.1.2].
    * When they leave, they click **Clock Out** [1.1.2].
    * The system automatically calculates their shift time, **deducts the mandatory 30-minute lunch break**, and saves their net working hours to the `work_logs` table [1.1.2, 1.2.3].
    * They see a clean list of their past 10 clocked shifts directly on their page so they can monitor their logged hours [1.1.2].

---

### Phase 6: Monthly Timesheet Generation & Claim Approval (Staff Panel)
1. At the end of the month, the student's daily work logs are consolidated into a monthly `Claim` record [1.2.3].
2. The **Supervisor** logs into the Staff Panel (`/staff`) and opens the **Workstudy Claims** resource [1.2.3]:
    * To inspect and audit the student's daily hours, they click the green **Download PDF Timesheet** button on the student's row [1.1.2].
    * **The PDF Report:** The system instantly compiles a PDF timesheet matching the **exact physical CPUT paper form** [1.1.2]. It dynamically prints the student’s details, lists their exact clock-in/out times grouped mathematically into Weeks 1–5, calculates their total hours, applies the hourly rate (R50.00), and shows the total calculated payout [1.1.2].
    * If satisfied with the timesheet, the Supervisor clicks **Supervisor Approve**, which locks the claim from further changes [1.2.3].
    * The **Workstudy Coordinator** (`coord@university.ac.za`) reviews the locked claim and clicks **Coordinator Approve** [1.2.3].

---

### Phase 7: Bulk Payroll Export (Staff Panel)
1. The **Workstudy Coordinator** opens the Claims resource and filters the table by the **Approved** tab to see all ready-for-payment claims [1.2.3].
2. They check the boxes next to the claims they wish to process.
3. They select **Export Selected for Payroll (CSV)** from the bulk actions menu [1.1.2].
4. The system instantly streams a payroll-ready CSV sheet detailing [1.1.2, 1.2.3]:
    * Student biographical details and banking configurations [1.1.2].
    * Total hours and rate [1.1.2].
    * **The Fee Allocation Split:** The exact 60% fee account and 40% bank account payouts automatically computed by your model's database logic [1.1.2, 1.2.3]!
