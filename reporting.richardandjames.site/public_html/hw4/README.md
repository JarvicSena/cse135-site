# CSE 135 Analytics Platform

## Project Overview
This project implements a web analytics platform that collects, stores, and visualizes user interaction data from our websites. The system was built incrementally across multiple assignments and integrates a data collection pipeline with a reporting dashboard that allows different user roles to view and analyze collected data.

The platform includes a collector service, a reporting dashboard, authentication and authorization controls, and visual reports using charts and tables.

The goal of the system is to demonstrate how analytics data can be collected from a website, processed on the backend, and presented in a way that allows analysts to interpret user behavior.

# Live Deployment
Main site:
https://richardandjames.site

Collector service:
https://collector.richardandjames.site

Reporting dashboard:
https://reporting.richardandjames.site/hw4

# Repository
GitHub Repository:
https://github.com/JarvicSena/cse135-site

---

# Technologies Used
- PHP
- SQLite
- JavaScript
- Chart.js
- HTML/CSS
- Nginx
- Apache
- Linux (Ubuntu server)

---

# System Architecture
The system consists of three main components:

### 1. Data Collection Layer
Client-side JavaScript collects analytics events such as user clicks, scroll behavior, and page load metrics. These events are sent to the collector service where they are stored as event records.

### 2. Import Pipeline
Analytics events collected by the collector are imported into a SQLite reporting database. This allows the reporting system to query the data efficiently.

### 3. Reporting Dashboard
The reporting dashboard provides an authenticated interface where analysts and viewers can access analytics reports, charts, and tables.

---

# Authentication & Authorization
The system implements a full authentication system with role-based access control.

Three user roles are supported:

### Super Admin
- Full system access
- Can manage users
- Can access all analytics reports

### Analyst
- Can view analytics dashboards
- Can analyze data and define reports

### Viewer
- Can only access saved reports

Authentication uses password hashing with `password_hash()` and `password_verify()`.

---

# Analytics Data Collection
Client-side scripts collect user events such as:
- clicks
- scroll events
- page load performance
- session activity

These events are sent to the collector service and stored in a datastore.

---

# Reporting Dashboard
The reporting system visualizes analytics data using charts and tables.

Reports include both visualizations and written analysis to interpret the meaning of the collected data.

Report categories include:

- Behavioral Reports
- Performance Reports
- Session Reports

Charts are rendered using Chart.js.

---

# Data Storage
Analytics events are imported into a SQLite reporting database. The database is queried by the reporting dashboard to generate tables and charts for analysts and viewers.

---

# Extra Features
In addition to the required assignment features, the following improvements were implemented:

- Role-aware dashboard navigation that dynamically hides pages users cannot access
- Persistent user datastore using a JSON file instead of hardcoded accounts
- Organized dashboard UI using card layouts for reports and analytics tools
- Error handling pages (403 and 404) to handle unauthorized or missing pages
- PDF export capability for reports

These features improve usability, maintainability, and overall system design beyond the minimum project requirements.

---

# Future Improvements
Given more time, the following improvements could be implemented:
- additional analytics visualizations
- interactive report filtering
- scheduled report generation
- automated email delivery of exported reports
- improved UI using a CSS framework
- performance optimization for larger datasets

---

# AI Usage
AI tools were used to assist with debugging server configuration, to help troubleshoot deployment issues, and to improve the readability of HTML and CSS.

All core implementation decisions, system design, and final code integration were written and verified manually.
