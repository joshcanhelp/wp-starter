This is a WordPress starter theme.

The instructions below walk you through how to adjust this theme to fit your project and remove parts that may not be helpful. They were written for PROPER Web Development projects so, in some cases (like changes to style.css), you'll be making a few more changes. 

## Getting Started

The commands below assume you: 

- have [npm](https://docs.npmjs.com/getting-started/installing-node) installed globally 
- have [Hub](https://github.com/github/hub) installed and aliased to the `git` command)
- have a working install of the latest WordPress version. 

**Note:** All file names and directories are relative from the theme, unless indicated otherwise.

1. Pick a slug for this theme based on the project name. It should only contain lowercase letters, numbers, and dashes. This will be used throughout the theme and in this documentation. 
2. Go to */WP_ROOT/wp-content/themes/* and create a directory for this theme using the slug picked above. 
3. Clone this repo into that directory: `git clone joshcanhelp/wp-starter theme-slug`
4. Make sure that WP_DEBUG is turned on in /wp-config.php
5. Log into wp-admin, go to **Appearance > Themes** and activate the starter theme
6. Make sure there are no errors appearing on the front or back end of the site
7. Delete *.git/* and *.gitignore* 
8. Edit *screenshot.psd* to add the site logo and remove "Starter Theme;" when complete, save for web as an 8-bit *screenshot.png*
9. If the logo is final, delete *screenshot.psd*. Otherwise, leave it there until the screenshot is complete
10. Open *style.css* and make the following changes:
	1. Change "Theme Name" to the customer or project name
	2. Change "Description" to describe the project
	3. Change "Version Number" to 0.1.0
	4. Change "Text Domain" to the the theme slug created above
11. Refresh the **Appearance > Themes** page to make sure the changes show
12. Open *package.json* and make the following changes:
	1. Change "name" to match the theme slug
	2. Change "version" to "0.1.0"
	3. Change "description" to match the Description used in 10.2 above
13. In your CLI (still in the theme directory), install all deps: `npm install`. This should finish without errors
14. Again in the CLI, test Grunt with the following commands (all should finish successfully) 
	1. `grunt sass`
	2. `grunt browserify`
	3. `grunt inlinecss`
15. Open *functions.php* and make the following changes:
	1. Find the first instance of the `PROPER_THEME_VERSION` constant and [PhpStorm] Right Click > Refactor > Rename; for example `PROPER_THEME_VERSION` would change to `THEME_SLUG_THEME_VERSION`
	3. Search/replace within *functions.php* to replace the others
	4. Change `PROPER_ENV` definitions to `THEME_SLUG_ENV` in *functions.php*, then refactor throughout the theme
	5. Remove or uncomment WP_CLI include
	6. Review `proper_hook_init` function for things to remove or change; change the function name and `add_action` call below to match the theme slug, like `theme_slug_hook_init`
	7. Review `proper_hook_after_setup_theme` function for things to remove or change; change the function name and `add_action` call below it to match the theme slug, like `theme_slug_hook_after_setup_theme`
16. Refresh wp-admin and the homepage of the site to make sure there are no errors or notices
17. [PhpStorm] Create a new local scope:
	1. Go to **Preferences > Appearance > Scopes**
	2. Click the "+" to add a new Local scope
	3. For "Name" use the theme slug
	4. Find the theme folder, click to highlight, and click **Include Recursively**
	5. Now expand this folder, click */node_modules* to highlight, and click **Exclude Recursively**
18. [PhpStorm] Now run a Code Inspection on this project so far at **Code > Inspect Code**; make sure to select the custom scope created above

