# Riyana Immobilien

Riyana Immobilien is a comprehensive property management system built with Laravel. It provides a robust platform for administrators to manage real estate assets and a streamlined portal for tenants to access essential information and manage utility costs.

## Key Features

### 🏢 Property & Unit Management
- **Full CRUD for Properties & Units**: Manage your real estate portfolio with ease.
- **Multi-Photo Support**: Upload and manage multiple high-quality images for each property and unit using a polymorphic photo system.
- **Status Tracking**: Monitor the occupancy and availability status of every unit.

### 💰 Utility Cost Management (Nebenkosten)
- **Flexible Categories**: Define custom utility categories (e.g., Cold Water, Heating).
- **Automated Calculation**: Calculate costs based on meter readings or fixed monthly entries.
- **Yearly Billing**: Support for defining billing periods and generating PDF statements for tenants.

### 🏠 Tenant Portal
- **Dashboard**: Quick overview of occupied units and upcoming rent/utility deadlines.
- **Utility Tracking**: Tenants can view their recorded entries and historical costs.
- **Secure Access**: Dedicated login for tenants with restricted access to their specific data.

### 🛡️ Admin & Security Controls
- **Admin-Only Registration**: Public registration is disabled to ensure security.
- **Automated Provisioning**: Admins create tenants, and the system automatically generates credentials and sends them via email.
- **Force Password Change**: New users are required to change their temporary password upon their first login.
- **Account Management**: Admins can activate or deactivate accounts with a single click.

### 🇩🇪 Full Localization
- The entire application (Admin, Tenant, and Public views) is fully localized into **German**.

## Technology Stack
- **Framework**: Laravel 11
- **Frontend**: Tailwind CSS, Alpine.js (via Laravel Breeze)
- **Database**: MySQL
- **Tooling**: Vite, Composer

## Installation & Setup

1. **Clone the repository**:
   ```bash
   git clone https://github.com/vivekk1989/riyana-immobilien.git
   ```

2. **Run the setup script**:
   ```bash
   composer run setup
   ```
   *This will install dependencies, generate the app key, run migrations, and execute the `AdminSeeder`.*

3. **Default Admin Credentials**:
   - **Email**: `info@riyana-immobilien.de`
   - **Password**: `ChangeYourPassword`
   *(You will be prompted to change this on your first login)*

## Deployment

The project includes a custom PowerShell script `build_deploy.ps1` designed for **"Jailed" Shared Hosting** environments. It flattens the structure and secures the core application files, making it ready for direct FTP upload.

---
© 2026 Riyana Immobilien
