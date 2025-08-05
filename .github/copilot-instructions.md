# Copilot Instructions for app-sekolah

## Project Overview
This is a Laravel-based school management application. The codebase is organized using Laravel conventions, with custom models, resources, and Filament admin panels for managing entities such as Rombel (class groups), Siswa (students), Jadwal (schedule), and more.

## Architecture & Key Components
- **Models**: Located in `app/Models/`, each model represents a database entity (e.g., `Rombel`, `Siswa`, `Schedull`, `Day`). Relationships are defined using Eloquent (`hasMany`, `belongsToMany`, etc.).
- **Filament Resources**: Found in `app/Filament/Resources/`, these provide admin interfaces for CRUD operations. RelationManagers are used for managing related entities (e.g., students in a class group).
- **Enums & Traits**: Custom enums and traits are in `app/Enums/` and `app/Traits/` to encapsulate business logic and reusable code.
- **Migrations & Seeders**: Database structure and initial data are managed in `database/migrations/` and `database/seeders/`.
- **Routes**: Defined in `routes/web.php` and `routes/console.php`.

## Developer Workflows
- **Build & Serve**: Use `php artisan serve` to run the development server. For asset compilation, use `npm run dev`.
- **Testing**: Run tests with `php artisan test` or `vendor/bin/pest` for Pest tests. Test files are in `tests/Feature/` and `tests/Unit/`.
- **Database**: Use `php artisan migrate` for migrations and `php artisan db:seed` for seeding.
- **Debugging**: Laravel Debugbar is available in `storage/debugbar/`.

## Project-Specific Patterns
- **Filament RelationManagers**: When using Filament's `Select::make()->relationship()`, ensure the relationship name matches the Eloquent method in the model and the field uses the correct foreign key (e.g., `siswa_id`).
- **Custom Info Columns**: Table columns often use computed values via `getStateUsing` for displaying combined info (see `RombelResource`).
- **Status Toggles**: Boolean status fields are managed with `Toggle` components in Filament forms.
- **Pivot Tables**: Many-to-many relationships use custom pivot tables (e.g., `rombels_siswas`, `rombels_subjects`).

## Integration Points
- **Filament Admin**: All admin CRUD and relation management is handled via Filament resources and panels.
- **External Packages**: Uses Pest for testing, Filament for admin UI, and Laravel Debugbar for debugging.

## Examples
- To add a student to a class group, use the `siswas` relationship in `Rombel` and ensure the Filament form uses `siswa_id` as the field.
- To display a class's info, use computed columns in Filament tables (see `info_singkat` in `RombelResource`).

## References
- Models: `app/Models/`
- Filament Resources: `app/Filament/Resources/`
- Migrations: `database/migrations/`
- Tests: `tests/`
- Routes: `routes/web.php`

---
If any section is unclear or missing important project-specific details, please provide feedback to improve these instructions.
