# WordPress Plugin #
A simple WordPress plugin boilerplate

## Description ##

This is a foundation for building WordPress plugins. It provides a structured, organized and maintainable codebase for
plugin developers to build upon. This boilerplate includes best practices for security, performance, and extensibility,
as well as tools for testing and deployment. It follows the WordPress coding standards and is built using object-oriented
design principles. By using this, developers can focus on building their plugin's unique functionality, rather than
worrying about the underlying code structure.

### Technologies ###

- [**Vite**](https://vitejs.dev/) Next Generation Frontend Tooling.
- [**tailwindcss**](https://tailwindcss.com/) Rapidly build modern websites without ever leaving your HTML.
- [**vue.js**](https://vuejs.org/) The Progressive JavaScript Framework.
- [**Notyf**](https://github.com/caroso1222/notyf) A minimalistic JavaScript library for toast notifications.
- [**jQuery**](https://jquery.com/) A minimalistic JavaScript library for toast notifications.

### Features ###

- Check used [Technologies](#technologies) features.
- Can generate independent CSS rules for the frontend and admin that work with almost any theme without conflict.
- The frontend and backend teams can work on the same plugin at the same time without any issues.
- By using a bash script, you can create a WordPress.org build package. As a result, your source code and production code will be distinct.
- Jquery is supported by frontend scripts. If your developer is unfamiliar with vue.js, he can generate admin and website assets with jquery.
- Shortcode generator can collect assets dynamically. All you have to do is pass shortcode tag in Shortcode Class.
- It will load classes based on the current screen of WordPress

#### 25/02/2023 ####
- Admin Dashboard
    - added datatable and datalist component
    - added .env support for development and production
    - added axios as default http client
- Plugin
    - Upgraded php 5.6 to 7.4
    - added rest api for admin panel
    - updated log system with WordPress default timezone
    - added cors support for development.
    - started license management system, need to improve.
- Scripts
    - added a build script. It will make a zip file for production build. Also return code from source to development

#### 23/06/2023 ####
- Admin Dashboard
  - update ui with responsive sidebar.
  - removed unnecessary packages.
  - implemented an automate WordPress coding standards checker.
  - added assets load based on project mode.
- Plugin
  - restructured facades.
  - added traits to reuse codes.
  - updated logging system with the default WordPress file system.
- Web
  - added notification for form submit.


## Requirements ##
- **node.js** v18.12.1
- **npm** v9.2.0
- **PHP** v7.1
- **VS Code extension** DEVSENSE.phptools-vscode

## Installation ##

### For `PHP Developer`, ###

1. Go to your `plugins` folder of your local WordPress installation and run
```shell
composer create-project devkabir/wordpress-plugin your-plugin-name
```
2. run for open codes
```shell
cd your-plugin-name
code .
```
### For `Frontend Developer`, ###
1. For working on admin ui
```shell
cd assets/admin
npm run dev
```
2. admin ui will open on `http://localhost:5173/`

The same goes for website assets

### For visual learners ###
![](https://youtu.be/ZXu4Y2Wt3-k)



