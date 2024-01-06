# WordPress Plugin

A simple WordPress plugin boilerplate

## Description

This is a foundation for building WordPress plugins.

### Technologies

- [**vue.js**](https://vuejs.org/) The Progressive JavaScript Framework.
- [**Vite**](https://vitejs.dev/) Next Generation Frontend Tooling.
- [**tailwindcss**](https://tailwindcss.com/) Rapidly build modern websites without ever leaving your HTML.
- [**Notyf**](https://github.com/caroso1222/notyf) A minimalistic JavaScript library for toast notifications.
- [**jQuery**](https://jquery.com/) \* A minimalistic JavaScript library for frontend. It's optional. you can use this wordpress script dependency.

### Features

- Check used [Technologies](#technologies) features.
- It replaces all placeholder strings during plugin creation.
- It generates independent styles for both frontend and backend with any conflict with WordPress core and any plugin or themes.
- It makes zip for test plugin throughout a team by one command.
- It makes production build, push updates to GitHub and revert all changes from production to development with one command.
- You can manage your plugin license and support with this. N.B. You will need a management system. you can check out my [WordPress Plugin Management System](https://github.com/devkabir/wordpress-plugin-management-system)

## Requirements

- **node.js** v18.12.1
- **npm** v9.2.0
- **PHP** v7.4
- **VS Code extension** DEVSENSE.phptools-vscode

## For Fresh Start

### Dev version with dummy content

```bash
composer create-project devkabir/wordpress-plugin:dev-master <your-plugin-name>
```

### Dev version with no content

```bash
composer create-project devkabir/wordpress-plugin:dev-fresh <your-plugin-name>
```

### Dev version for admin side only

```bash
composer create-project devkabir/wordpress-plugin:dev-only-admin <your-plugin-name>
```

## Documentation

- [How to install](https://github.com/devkabir/wordpress-plugin/wiki#how-to-install)
- [Project Organization](https://github.com/devkabir/wordpress-plugin/wiki#project-organization)
- Components
  - [List](https://github.com/devkabir/wordpress-plugin/wiki/DataList)
  - [Table](https://github.com/devkabir/wordpress-plugin/wiki/DataTable)

## Recommended Plugins

For nice error page, install [wp-debugger](https://github.com/devkabir/a-wp-plugin-debugger)

## For visual learners

### Featuring Dev version for admin side only
[![Video Title](https://img.youtube.com/vi/_bM5dqUTgNA/0.jpg)](https://www.youtube.com/watch?v=_bM5dqUTgNA)
