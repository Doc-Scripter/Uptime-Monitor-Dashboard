# Frontend Documentation

## Architecture

The frontend is built with **Vue 3** (Composition API) and **TypeScript**, using **Vite** as the build tool.

### Key Technologies
- **Framework**: Vue 3.5+
- **State Management**: Pinia
- **Routing**: Vue Router 4
- **Styling**: TailwindCSS v4
- **Charts**: Chart.js with vue-chartjs wrapper
- **HTTP Client**: Axios

## Project Structure

```
src/
├── assets/          # Static assets and global CSS
├── components/      # Vue components
│   ├── charts/      # Chart.js wrappers
│   ├── common/      # Reusable UI components (buttons, dropdowns)
│   ├── dashboard/   # Dashboard-specific widgets
│   ├── forms/       # Form components
│   ├── layout/      # Layout components (Sidebar, Navbar)
│   └── modals/      # Modal dialogs
├── composables/     # Reusable logic (hooks)
├── router/          # Route definitions
├── services/        # API client modules
├── stores/          # Pinia state stores
├── types/           # TypeScript interfaces
└── views/           # Page components
```

## State Management

We use **Pinia** for global state management, split into modular stores:

1. **monitorStore**: Manages the list of monitors, CRUD operations, and statistics.
2. **uiStore**: Handles UI state like dark mode, sidebar visibility, and modals.
3. **networkStore**: Manages cached network intelligence data.

## Styling & Dark Mode

Styling is handled via **TailwindCSS**. 

- **Dark Mode**: Implemented using the `dark` class on the `html` element.
- **Theme**: Custom colors are defined in `src/assets/main.css` using CSS variables and Tailwind's `@theme` directive.
- **Icons**: Google Material Symbols Outlined are used throughout the app.

## Components

### Layout
The application uses a `MainLayout` wrapper that includes the `Sidebar` and `TopNavBar`. The `Sidebar` is collapsible on mobile devices.

### Dashboard
The dashboard is composed of several widgets:
- **StatsCard**: Displays key metrics.
- **MonitorsTable**: Main list of monitors with sorting and filtering.
- **MonitorDetailsPanel**: Tabbed view for charts and logs.
- **HealthLogsPanel**: List of recent events.

## Development

### Setup
```bash
npm install
```

### Run Dev Server
```bash
npm run dev
```

### Build for Production
```bash
npm run build
```

### Linting
```bash
npm run lint
```
