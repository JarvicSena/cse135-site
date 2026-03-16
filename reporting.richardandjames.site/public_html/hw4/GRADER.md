# Grader Instructions

## Live System
Reporting Dashboard:
https://reporting.richardandjames.site/hw4

Collector Endpoint:
https://collector.richardandjames.site

Main Website
https://richardandjames.site

---

# Test Credentials

## Super Admin
Username: superadmin
Password: Cse135Winter

Capabilities:
- manage users
- access all reports
- view analytics dashboards
- access data tables and charts 

---

## Analyst
Username: analyst
Password: Analyst123

Capabilities:
- view analytics dashboards
- analyze charts and tables
- access behavioral, performance, and session reports
- cannot access user management

---

## Viewer
Username: viewer
Password: Viewer123

Capabilities:
- view saved reports only
- cannot access backend analytics dashboards
- cannot access user management

---

# Suggested Grading Walkthrough

The following scenario demonstrates the core functionality of the system.

---

## Step 1 – Access the Reporting Dashboard

Open the reporting dashboard:

https://reporting.richardandjames.site/hw4

You should be redirected to the login page.

---

## Step 2 – Login as Super Admin

Use the superadmin credentials.

Verify that the dashboard loads.

The navigation menu should allow access to:

- charts
- analytics data tables
- behavioral reports
- performance reports
- session reports
- user management

---

## Step 3 – Test User Management

Navigate to **Manage Users**.

Verify that:

- users can be viewed
- roles can be modified
- new users can be created
- existing users can be deleted

---

## Step 4 – Test Reports

Navigate to each report:

- Behavioral Report
- Performance Report
- Session Report

Each report should contain:

- a chart visualization
- a data table
- analyst commentary explaining the data

---

## Step 5 – Test Export Functionality

From a report page, click the **Export PDF** button.

Verify that a PDF version of the report is generated.

---

## Step 6 – Test Analyst Access

Log out and log in as the **analyst**.

Verify that:

- reports are accessible
- charts and analytics tables load correctly
- user management is not accessible

---

## Step 7 – Test Viewer Access

Log out and log in as the **viewer**.

Verify that:

- only saved reports are visible
- analytics dashboards are not accessible
- user management is not accessible

---

# Potential Areas of Concern

Possible edge cases that may affect the system include:

- extremely large analytics datasets during import
- concurrent writes to the SQLite database
- browser differences when rendering exported PDFs

These limitations were considered acceptable given the scope and time constraints of the assignment.
