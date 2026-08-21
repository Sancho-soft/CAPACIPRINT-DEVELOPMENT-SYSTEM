# CapaciPrint — System Architecture, Domain Model & Behavioral Specification

You are the Lead Full-Stack Architect & Principal Software Engineer building **CapaciPrint**, a comprehensive Multi-Branch Printing Management, Production Planning, Capacity Optimization, CRM, Inventory, Procurement, and Financial System built with Laravel, MySQL (`project.sql`), and Blade/Vite.

---

## 1. System Role Hierarchy & Actors

1. **Super Admin**: Highest technical authority; full system control, role/permission modifications, disaster recovery.
2. **Owner**: Executive authority; financial reports, procurement approvals, high-level dashboards, branch performance, audit logs.
3. **System Admin**: IT/operations administrator; user account management, branch setup, machine registration, employee management, system monitoring.
4. **Branch Manager**: Branch operations lead; employee assignments, branch capacity management, production planning, scheduling, machine availability, inventory tracking, purchase requests, branch-level audit/reports.
5. **Production Officer**: Operations planner; job scheduling, branch assignment, queue prioritization, production plans.
6. **Customer Service (CS)**: Customer-facing agent; customer CRM, print requests, quotations/estimations, print job intake, payments/receipts, order fulfillment (pickup/delivery), customer notifications.
7. **Layout Designer**: Creative/pre-press specialist; layout creation, proof upload, design revisions, customer proofing workflow, production-ready file preparation.
8. **Production Operator**: Shop-floor technician; execution queue, start/pause/resume/complete jobs, machine & material logging, machine problem reporting, inventory usage logging.
9. **Customer**: Client portal user; profile management, print request submission, quote review/approval/rejection, proof approval/revision requests, order tracking, invoice/payment viewing.

---

## 2. Exhaustive Module & Use Case Matrix

### Module 1: Authentication & Account Access (All Users)
- `Login`, `Logout`, `Change Password`, `Reset Password`, `Recover Account`, `Manage Session`.
- Session security, role-based redirection upon login, multi-device session invalidation.

### Module 2: User & Access Management (Super Admin, System Admin)
- `Create User Account`, `Activate/Deactivate Account`.
- `Assign Managerial Role`, `Assign Operational Permission`, `Revoke Permission`, `Modify Permission`, `View User Permissions`.
- `Recover Account`.

### Module 3: Branch Management (System Admin, Branch Manager)
- `Create Branch`, `Update Branch`, `View Branch`, `Configure Branch Information`.
- `Assign Staff to Branch`, `View Branch Status`.

### Module 4: Employee & Staff Management (System Admin, Branch Manager)
- `Add Employee`, `Update Employee Information`, `View Employee`.
- `Assign Employee to Branch`, `Set Employment Status`, `View Staff Assignment`.

### Module 5: Customer Management / CRM (Customer Service, Customer)
- `Register Customer`, `Search Customer`, `Update Customer`.
- `View Customer Profile`, `View Customer History`, `View Customer Print Jobs`.

### Module 6: Print Request Management (Customer Service, Customer)
- `Submit Print Request`, `Record Print Specifications` (paper type, size, finish, binding, quantity, colors).
- `Upload Design File`, `Review Request`, `Modify Request`, `Confirm Request`.

### Module 7: Quotation & Estimation (Customer Service, Customer)
- `Create Quotation`, `Calculate Price` (material cost, labor, overhead, machine rate, markup).
- `Edit Quotation`, `View Quotation`, `Send Quotation`.
- `Approve Quotation`, `Reject Quotation`, `Request Revision`.

### Module 8: Print Job Management (Customer Service, Production Operator, Customer)
- `Create Print Job` (from approved quotation/order), `View Job`, `Update Job Information`.
- `Assign Job`, `Reassign Job`, `View Job Status`, `Update Job Status`, `Cancel Job`.

### Module 9: Design & Layout Management (Super Admin, System Admin, Layout Designer, Customer)
- `View Assigned Design`, `Download Design`.
- `Create Layout`, `Edit Layout`, `Upload Design Proof`, `Revise Design`.
- `Submit Design for Approval`, `Approve Design`, `Request Design Revision`, `Submit Final Production File`.

### Module 10: Production Planning (Branch Manager, Production Officer, System Admin)
- `View Production Planning`, `Create Production Plan`.
- `Schedule Job`, `Assign Job to Branch` (multi-branch routing based on workload).
- `Prioritize Job` (rush vs. standard), `Reschedule Job`, `Review Production Plan`.

### Module 11: Branch Capacity Management (Branch Manager, System Admin)
- `View Branch Capacity`, `View Workload`, `Evaluate Capacity`.
- `View Capacity Status`, `Configure Capacity` (shifts, machine throughput, max daily load).
- `Review Branch Recommendation` (load balancing suggestions).

### Module 12: Production Queue & Execution (Branch Manager, Production Operator)
- `View Production Queue`, `View Job Specifications`.
- `Start Production`, `Pause Production`, `Resume Production`.
- `Record Production Progress`, `Record Material Usage`, `Record Machine Used`.
- `Complete Production`, `Report Production Issue/Scrap`.

### Module 13: Machine & Equipment Management (System Admin, Branch Manager, Production Operator)
- `Register Machine`, `Update Machine Information`, `View Machine`.
- `Set Machine Availability` (Active, Under Maintenance, Offline, Busy).
- `Record Machine Status`, `Report Machine Problem`, `Record Maintenance Log`.

### Module 14: Inventory & Materials Management (Branch Manager, Production Operator)
- `View Inventory`, `Add Inventory Item`, `Update Inventory`.
- `Record Material Usage` (auto-deduct on production completion).
- `Monitor Stock Level`, `Set Reorder Level`, `Record Stock Adjustment` (wastage, cycle count).

### Module 15: Purchasing / Procurement (Branch Manager, Owner)
- `Create Purchase Request`, `Review Purchase Request`, `Approve Purchase Request`.
- `Record Purchase`, `Receive Materials` (auto-update inventory stock), `View Purchase History`.

### Module 16: Order Fulfillment (Customer Service, Layout Designer, Customer)
- `Submit for Quality Check`, `Record Completion`.
- `Mark Ready for Pickup`, `Record Pickup`, `Record Delivery`.
- `Update Fulfillment Status`, `View Order Status`.

### Module 17: Payment & Transaction Management (Customer Service, Customer)
- `Record Payment` (downpayment, full payment, cash, online, card), `View Payment`.
- `Update Payment Status`, `View Transaction`, `Generate Receipt / Invoice`, `View Transaction History`.

### Module 18: Notifications & Communication (All Actors, Owner)
- `Send Notification`, `Receive Notification`, `View Notifications`.
- `Notify Customer` (quote ready, proof ready, job done, pickup alert).
- `Notify Staff` (new job assigned, machine breakdown, stock below reorder level).
- `Send Status Update`.

### Module 19: Reports & Dashboard (Owner, Branch Manager, Customer Service)
- `View Dashboard` (role-customized metrics).
- `Generate Report`.
- `View Sales Report`, `View Production Report`, `View Branch Performance Report`.
- `View Capacity Report`, `View Inventory Report`, `View Job Status Report`.

### Module 20: Audit Trail & System Monitoring (Owner, System Admin, Branch Manager)
- `View Audit Logs`, `View User Activity`.
- `View Permission Changes`, `View Role Changes`.
- `Monitor System Activity`.

---

## 3. Implementation Directives & Standards

1. **Database Schema Compliance**: Align Eloquent Models, Relations, Migrations, and Seeders strictly with `project.sql`.
2. **Role-Based Access Control (RBAC)**: Enforce middleware, policies, and gate checks for every route, action, and UI element according to the 9 defined actors.
3. **Multi-Branch Isolation & Awareness**: Operations (queue, capacity, inventory, staff) must support branch scoping while giving Super Admin, Owner, and System Admin system-wide visibility.
4. **Clean, Premium UI / UX**: Modern, professional interface matching print shop workflow standards with real-time cues, status badges, interactive workflow transitions, and responsive views.
5. **Robust Data Integrity**: Transactions on critical multi-table actions (e.g. Completing a Job -> Deduct Inventory + Update Machine Log + Notify CS + Advance Fulfillment State).
