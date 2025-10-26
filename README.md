[![Logo](https://github.com/ElStefanos/Advanced-Web-Tools/assets/46761434/eb7d7fd1-dd8e-42a2-94d3-d45a7417ac25)](https://advancedwebtools.com)

# Advanced Web Tools (AWT)

**Advanced Web Tools** (AWT) is a next-generation PHP-based Content Management System (CMS) and runtime platform designed to deliver exceptional performance, runtime flexibility, and developer control.

---

## For Users

### Simplicity and Speed
AWT is built for those who want a fast, reliable website without the complexity of traditional CMS platforms.  
It installs in minutes, loads instantly, and requires no command-line tools or external dependencies.

### Key Features
- **Blazing performance** — optimized for speed and minimal resource usage.  
- **Simple setup** — configure once, deploy anywhere.  
- **Runtime extensibility** — install new features or modules without downtime.  
- **Built-in CMS capabilities** — manage content, media, themes, and plugins.  
- **Stable and secure core** — always active, never breaks on updates.  

### Perfect for
- Personal or business websites  
- Lightweight e-commerce systems  
- Developer-backed web applications  
- High-performance hosting environments  

---

## For Developers

**AWT** provides a hybrid architecture that merges a static, high-performance **Core** with dynamically loadable **Runtime Packages**.  
This makes it a rare PHP system that can evolve live — without rebuilds or restarts.

### Why choose AWT?
- **Hybrid architecture**: a fixed **Core** plus runtime-loadable **Packages**.  
- **Runtime extensibility**: add, update, or remove functionality while the system runs.  
- **Engineered for speed**: minimal overhead and optimized routing.  
- **Developer-friendly**: event-driven core, clear API, modular design.  
- **CMS out of the box**: theming, caching, media, and plugin marketplace.  

---

## Project Structure

- **/awt_src/** — Core system files (router, renderer, ORM, event bus)  
- **/awt_packages/** — Runtime package directory (modules/plugins)  
- **/awt_data/** — Storage of user data, uploaded media, runtime state  
- **/dev_run/** — Development configurations and utilities  
- **awt_config.php** — Core configuration  
- **awt_db.php** — Database configuration  
- **index.php** — Application entry point  

---

## Installation

### Requirements
- PHP 8.x  
- Web server (Apache or NGINX)  
- MySQL or MariaDB  

### Steps
1. Clone or download this repository.  
2. Create an empty database.  
3. Configure `awt_config.php` with database credentials and paths.  
4. Import `awt_db.sql` into your database.  
5. Deploy files to your web-root or virtual host.  
6. Access your site in a browser and complete setup.

---

## Core Components

| Component | Description |
|------------|-------------|
| **Router** | Maps HTTP requests to controllers or modules |
| **Renderer** | Template rendering and output generation |
| **Data Manager / ORM** | Handles data access and model registration |
| **Session Handler** | User session management |
| **Event System** | Global event bus for inter-module communication |
| **Package Manager** | Handles installation and activation of runtime packages |

---

## Runtime Packages

Runtime packages live in `/awt_packages/` and follow a simple contract with the Core.  
Each package defines metadata, versioning, and event hooks.

**Lifecycle:**
1. Install via Package Manager  
2. Link/activate at runtime  
3. Register event listeners, controllers, and templates  
4. Update or remove — no restart required  

---

## Performance and Stability

AWT’s architecture minimizes blocking operations and runtime overhead.  
With proper configuration, **Lighthouse scores of 95–100** are achievable on production servers.

---

## Contributing

Contributions are welcome.  
See `SECURITY.md` for vulnerability reporting or open an issue for feature requests.  
Follow the coding standard and include tests where possible.

---

## License

This project is licensed under the **GPL-3.0 License**.  
All derivatives must remain open-source under the same terms.

---

**Website:** [https://advancedwebtools.com](https://advancedwebtools.com)  
**Author:** Stefan Crkvenjakov  
**License:** GPL-3.0
